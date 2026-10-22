<?php
/**
 * Training calendar download, gated by name and email.
 *
 * - The PDF is chosen in Customize → Training calendar.
 * - Each request is saved as a "Calendar download" (admin menu) with the name and email,
 *   then the visitor gets the file. Nothing is emailed yet.
 * - Spam guard: a hidden honeypot field and a limit of 5 requests per visitor per 10 minutes.
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Leads list in the admin (read only: created by the form, never by hand).
 */
function jodyn_register_leads() {
	register_post_type(
		'jodyn_lead',
		array(
			'labels'       => array(
				'name'          => __( 'Calendar downloads', 'jodyn' ),
				'singular_name' => __( 'Calendar download', 'jodyn' ),
				'all_items'     => __( 'Calendar downloads', 'jodyn' ),
				'search_items'  => __( 'Search downloads', 'jodyn' ),
				'not_found'     => __( 'No downloads yet', 'jodyn' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'menu_icon'    => 'dashicons-download',
			'supports'     => array( 'title' ),
			'map_meta_cap' => true,
			'capabilities' => array( 'create_posts' => 'do_not_allow' ),
		)
	);
}
add_action( 'init', 'jodyn_register_leads' );

add_filter(
	'manage_jodyn_lead_posts_columns',
	function ( $columns ) {
		return array(
			'cb'          => $columns['cb'],
			'title'       => __( 'Name', 'jodyn' ),
			'jodyn_email' => __( 'Email', 'jodyn' ),
			'date'        => __( 'Requested', 'jodyn' ),
		);
	}
);
add_action(
	'manage_jodyn_lead_posts_custom_column',
	function ( $column, $post_id ) {
		if ( 'jodyn_email' === $column ) {
			$email = get_post_meta( $post_id, '_jodyn_email', true );
			printf( '<a href="mailto:%1$s">%2$s</a>', esc_attr( $email ), esc_html( $email ) );
		}
	},
	10,
	2
);

/**
 * Customize → Training calendar: the PDF file.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function jodyn_calendar_customize( $wp_customize ) {
	$wp_customize->add_section(
		'jodyn_training',
		array(
			'title'       => __( 'Training calendar', 'jodyn' ),
			'description' => __( 'The PDF people receive after entering their name and email. Requests are listed under Calendar downloads.', 'jodyn' ),
			'priority'    => 36,
		)
	);
	$wp_customize->add_setting(
		'jodyn_calendar_pdf',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'jodyn_calendar_pdf',
			array(
				'label'     => __( 'Training calendar (PDF)', 'jodyn' ),
				'section'   => 'jodyn_training',
				'mime_type' => 'application/pdf',
			)
		)
	);
}
add_action( 'customize_register', 'jodyn_calendar_customize' );

/**
 * URL of the calendar PDF, or '' when none is set.
 */
function jodyn_calendar_pdf_url() {
	$id = (int) get_theme_mod( 'jodyn_calendar_pdf', 0 );
	return $id ? (string) wp_get_attachment_url( $id ) : '';
}

/**
 * POST /wp-json/jodyn/v1/calendar-download  { name, email, website (honeypot) }
 */
function jodyn_calendar_route() {
	register_rest_route(
		'jodyn/v1',
		'/calendar-download',
		array(
			'methods'             => 'POST',
			'permission_callback' => '__return_true', // Public form; guarded by the honeypot and rate limit below.
			'args'                => array(
				'name'    => array(
					'required'          => true,
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
					'validate_callback' => function ( $value ) {
						$value = trim( (string) $value );
						return '' !== $value && strlen( $value ) <= 120;
					},
				),
				'email'   => array(
					'required'          => true,
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_email',
					'validate_callback' => function ( $value ) {
						return (bool) is_email( $value );
					},
				),
				'website' => array(
					'type'    => 'string',
					'default' => '',
				),
			),
			'callback'            => 'jodyn_calendar_download',
		)
	);
}
add_action( 'rest_api_init', 'jodyn_calendar_route' );

/**
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function jodyn_calendar_download( $request ) {
	$url = jodyn_calendar_pdf_url();

	// Bots fill every field; people never see this one. Pretend it worked.
	if ( '' !== trim( (string) $request['website'] ) ) {
		return rest_ensure_response( array( 'url' => $url ) );
	}

	$ip    = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key   = 'jodyn_dl_' . md5( $ip );
	$count = (int) get_transient( $key );
	if ( $count >= 5 ) {
		return new WP_Error( 'jodyn_rate_limited', __( 'Too many requests. Please try again in a few minutes.', 'jodyn' ), array( 'status' => 429 ) );
	}
	set_transient( $key, $count + 1, 10 * MINUTE_IN_SECONDS );

	$lead = wp_insert_post(
		array(
			'post_type'   => 'jodyn_lead',
			'post_status' => 'private',
			'post_title'  => $request['name'],
		),
		true
	);
	if ( ! is_wp_error( $lead ) ) {
		update_post_meta( $lead, '_jodyn_email', $request['email'] );
	}

	if ( ! $url ) {
		return new WP_Error( 'jodyn_no_calendar', __( 'The calendar is not available yet. Please check back soon.', 'jodyn' ), array( 'status' => 503 ) );
	}
	return rest_ensure_response( array( 'url' => $url ) );
}
