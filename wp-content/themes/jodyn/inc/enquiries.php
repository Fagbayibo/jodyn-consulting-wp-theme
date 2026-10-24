<?php
/**
 * Contact form messages (the form on /contact-us/, template-parts/contact/form.php).
 *
 * - Each message is saved as an "Enquiry" (admin menu, read only) with every answer.
 * - It is emailed to the matching line's address (Customize → Contact & social: advisory, audit
 *   and tax go to the advisory line, training to the training line, the rest to general; each
 *   falls back to the main email), with Reply-To set to the sender. Sending needs the live site's
 *   email set up (an SMTP plugin); the enquiry is saved either way.
 * - Spam guard: a hidden honeypot field and a limit of 5 messages per visitor per 10 minutes.
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enquiry types, in dropdown order (content/pages/contact.md, section 3). Other pages link to the
 * form with ?enquiry=<key> to select one.
 *
 * @return array key => array( label, line ) where line is the "Ways to reach us" card it belongs to.
 */
function jodyn_enquiry_types() {
	return array(
		'general'         => array( __( 'General Enquiry', 'jodyn' ), 'general' ),
		'advisory'        => array( __( 'Advisory Services', 'jodyn' ), 'advisory' ),
		'audit'           => array( __( 'Systems Audit', 'jodyn' ), 'advisory' ),
		'tax'             => array( __( 'Tax Consultancy', 'jodyn' ), 'advisory' ),
		'training'        => array( __( 'Training Programme', 'jodyn' ), 'training' ),
		'custom-training' => array( __( 'In-House or Custom Training', 'jodyn' ), 'training' ),
		'facilitator'     => array( __( 'Facilitator Application', 'jodyn' ), 'general' ),
		'partnership'     => array( __( 'Partnership', 'jodyn' ), 'general' ),
		'media'           => array( __( 'Media', 'jodyn' ), 'general' ),
	);
}

/**
 * The promised response time (Customize → Contact & social), or its placeholder.
 *
 * @return string
 */
function jodyn_response_time() {
	$value = trim( (string) get_theme_mod( 'jodyn_contact_response', '' ) );
	return '' === $value ? '[one working day]' : $value;
}

/**
 * Enquiries list in the admin (read only: created by the form, never by hand).
 */
function jodyn_register_enquiries() {
	register_post_type(
		'jodyn_enquiry',
		array(
			'labels'       => array(
				'name'          => __( 'Enquiries', 'jodyn' ),
				'singular_name' => __( 'Enquiry', 'jodyn' ),
				'all_items'     => __( 'Enquiries', 'jodyn' ),
				'edit_item'     => __( 'Enquiry', 'jodyn' ),
				'search_items'  => __( 'Search enquiries', 'jodyn' ),
				'not_found'     => __( 'No enquiries yet', 'jodyn' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'menu_icon'    => 'dashicons-email-alt',
			'supports'     => array( 'title' ),
			'map_meta_cap' => true,
			'capabilities' => array( 'create_posts' => 'do_not_allow' ),
		)
	);
}
add_action( 'init', 'jodyn_register_enquiries' );

add_filter(
	'manage_jodyn_enquiry_posts_columns',
	function ( $columns ) {
		return array(
			'cb'            => $columns['cb'],
			'title'         => __( 'Name', 'jodyn' ),
			'jodyn_type'    => __( 'Enquiry type', 'jodyn' ),
			'jodyn_contact' => __( 'Email and phone', 'jodyn' ),
			'jodyn_org'     => __( 'Organisation', 'jodyn' ),
			'date'          => __( 'Received', 'jodyn' ),
		);
	}
);
add_action(
	'manage_jodyn_enquiry_posts_custom_column',
	function ( $column, $post_id ) {
		$meta = get_post_meta( $post_id, '_jodyn_enquiry', true );
		if ( ! is_array( $meta ) ) {
			return;
		}
		$types = jodyn_enquiry_types();
		if ( 'jodyn_type' === $column ) {
			echo esc_html( isset( $types[ $meta['enquiry'] ] ) ? $types[ $meta['enquiry'] ][0] : $meta['enquiry'] );
		} elseif ( 'jodyn_contact' === $column ) {
			printf( '<a href="mailto:%1$s">%2$s</a><br>%3$s', esc_attr( $meta['email'] ), esc_html( $meta['email'] ), esc_html( $meta['phone'] ) );
		} elseif ( 'jodyn_org' === $column ) {
			echo esc_html( trim( $meta['organisation'] . ( $meta['job_title'] ? ' · ' . $meta['job_title'] : '' ), ' ·' ) );
		}
	},
	10,
	2
);

/**
 * The message on its admin screen.
 */
add_action(
	'add_meta_boxes_jodyn_enquiry',
	function () {
		add_meta_box(
			'jodyn-enquiry',
			__( 'Enquiry', 'jodyn' ),
			function ( $post ) {
				$meta = get_post_meta( $post->ID, '_jodyn_enquiry', true );
				if ( ! is_array( $meta ) ) {
					return;
				}
				$types = jodyn_enquiry_types();
				$rows  = array(
					__( 'Enquiry type', 'jodyn' )  => isset( $types[ $meta['enquiry'] ] ) ? $types[ $meta['enquiry'] ][0] : $meta['enquiry'],
					__( 'Name', 'jodyn' )          => $meta['first_name'] . ' ' . $meta['last_name'],
					__( 'Email address', 'jodyn' ) => $meta['email'],
					__( 'Phone number', 'jodyn' )  => $meta['phone'],
					__( 'Organisation', 'jodyn' )  => $meta['organisation'],
					__( 'Job title', 'jodyn' )     => $meta['job_title'],
					__( 'Message', 'jodyn' )       => $meta['message'],
					__( 'Emailed to', 'jodyn' )    => $meta['emailed'] ? $meta['to'] : __( 'Not sent (check the site’s email setup)', 'jodyn' ),
				);
				echo '<table class="widefat striped"><tbody>';
				foreach ( $rows as $label => $value ) {
					if ( '' !== trim( (string) $value ) ) {
						printf( '<tr><th style="width:180px">%1$s</th><td>%2$s</td></tr>', esc_html( $label ), nl2br( esc_html( $value ) ) );
					}
				}
				echo '</tbody></table>';
				if ( is_email( $meta['email'] ) ) {
					printf( '<p><a class="button button-primary" href="mailto:%1$s">%2$s</a></p>', esc_attr( $meta['email'] ), esc_html__( 'Reply by email', 'jodyn' ) );
				}
			},
			'jodyn_enquiry',
			'normal',
			'high'
		);
	}
);

/**
 * POST /wp-json/jodyn/v1/contact
 */
function jodyn_enquiry_route() {
	register_rest_route(
		'jodyn/v1',
		'/contact',
		array(
			'methods'             => 'POST',
			'permission_callback' => '__return_true', // Public form; guarded by the honeypot and rate limit below.
			'callback'            => 'jodyn_enquiry_submit',
		)
	);
}
add_action( 'rest_api_init', 'jodyn_enquiry_route' );

/**
 * Check, save and email a contact form message.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function jodyn_enquiry_submit( $request ) {
	$in   = (array) $request->get_json_params();
	$text = static function ( $key, $max = 160 ) use ( $in ) {
		return isset( $in[ $key ] ) && is_scalar( $in[ $key ] ) ? mb_substr( sanitize_text_field( (string) $in[ $key ] ), 0, $max ) : '';
	};

	// Bots fill every field; people never see this one. Pretend it worked.
	if ( '' !== $text( 'website' ) ) {
		return rest_ensure_response( array( 'ok' => true ) );
	}

	$ip    = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$limit = 'jodyn_msg_' . md5( $ip );
	$count = (int) get_transient( $limit );
	if ( $count >= 5 ) {
		return new WP_Error( 'jodyn_rate_limited', __( 'Too many messages. Please try again in a few minutes.', 'jodyn' ), array( 'status' => 429 ) );
	}

	$types   = jodyn_enquiry_types();
	$enquiry = $text( 'enquiry' );
	$message = isset( $in['message'] ) && is_scalar( $in['message'] ) ? mb_substr( sanitize_textarea_field( (string) $in['message'] ), 0, 5000 ) : '';
	$meta    = array(
		'first_name'   => $text( 'first_name', 80 ),
		'last_name'    => $text( 'last_name', 80 ),
		'email'        => sanitize_email( $text( 'email' ) ),
		'phone'        => $text( 'phone', 40 ),
		'organisation' => $text( 'organisation' ),
		'job_title'    => $text( 'job_title' ),
		'enquiry'      => $enquiry,
		'message'      => $message,
		'emailed'      => false,
		'to'           => '',
	);
	if ( '' === $meta['first_name'] || '' === $meta['last_name'] || ! is_email( $meta['email'] ) || ! isset( $types[ $enquiry ] ) || '' === trim( $message ) || empty( $in['privacy'] ) ) {
		return new WP_Error( 'jodyn_enquiry_invalid', __( 'Please complete the fields marked *.', 'jodyn' ), array( 'status' => 400 ) );
	}
	set_transient( $limit, $count + 1, 10 * MINUTE_IN_SECONDS );

	$name = $meta['first_name'] . ' ' . $meta['last_name'];
	$id   = wp_insert_post(
		array(
			'post_type'   => 'jodyn_enquiry',
			'post_status' => 'private',
			'post_title'  => $name,
		),
		true
	);
	if ( is_wp_error( $id ) ) {
		return new WP_Error( 'jodyn_enquiry_failed', __( 'Something went wrong. Please try again.', 'jodyn' ), array( 'status' => 500 ) );
	}

	// To the line this enquiry type belongs to, else the main email, else the site admin
	$line = jodyn_contact_line( $types[ $enquiry ][1] );
	$to   = $line['email'] ? $line['email'] : get_option( 'admin_email' );
	$body = array(
		/* translators: %s: enquiry type. */
		sprintf( __( 'New enquiry from the website: %s', 'jodyn' ), $types[ $enquiry ][0] ),
		'',
		'Name: ' . $name,
		'Email: ' . $meta['email'],
		'Phone: ' . ( $meta['phone'] ? $meta['phone'] : '—' ),
		'Organisation: ' . ( $meta['organisation'] ? $meta['organisation'] : '—' ),
		'Job title: ' . ( $meta['job_title'] ? $meta['job_title'] : '—' ),
		'',
		'Message:',
		$message,
		'',
		'Reply to this email to answer ' . $meta['first_name'] . ' directly. Every enquiry is also listed under Enquiries in the WordPress admin.',
	);
	/* translators: 1: enquiry type, 2: name. */
	$subject         = sprintf( __( '%1$s from %2$s', 'jodyn' ), $types[ $enquiry ][0], $name );
	$meta['to']      = $to;
	$meta['emailed'] = (bool) wp_mail( $to, $subject, implode( "\n", $body ), array( 'Reply-To: ' . $name . ' <' . $meta['email'] . '>' ) );
	update_post_meta( $id, '_jodyn_enquiry', $meta );

	return rest_ensure_response( array( 'ok' => true ) );
}
