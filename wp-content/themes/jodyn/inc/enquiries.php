<?php
/**
 * Contact form messages (the form on /contact-us/, template-parts/contact/form.php).
 *
 * - Each message is saved as an "Enquiry" (admin menu, read only) with every answer.
 * - It is emailed to the matching line's address (Customize → Contact & social: advisory, audit
 *   and tax go to the advisory line, training to the training line, the rest to general; each
 *   falls back to the main email), with Reply-To set to the sender. Sending needs the live site's
 *   email set up (an SMTP plugin); the enquiry is saved either way.
 * - A link from a programme page (?programme=<slug>) names that programme in the form, the
 *   email and the admin.
 * - Spam guard: a hidden honeypot field and a limit of 5 messages per visitor per 10 minutes
 *   (inc/forms.php).
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
	jodyn_form_post_type(
		'jodyn_enquiry',
		array(
			'name'          => __( 'Enquiries', 'jodyn' ),
			'singular_name' => __( 'Enquiry', 'jodyn' ),
			'all_items'     => __( 'Enquiries', 'jodyn' ),
			'edit_item'     => __( 'Enquiry', 'jodyn' ),
			'search_items'  => __( 'Search enquiries', 'jodyn' ),
			'not_found'     => __( 'No enquiries yet', 'jodyn' ),
		),
		'dashicons-email-alt'
	);
}

/**
 * The programme a contact form link names (?programme=<slug>, from a programme page's Enquire
 * and Request In-House Delivery buttons), or null.
 *
 * @param string $slug Programme slug.
 * @return WP_Post|null
 */
function jodyn_enquiry_programme( $slug ) {
	$post = $slug ? get_page_by_path( sanitize_title( $slug ), OBJECT, 'jodyn_programme' ) : null;
	return $post && 'publish' === $post->post_status ? $post : null;
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
					__( 'Programme', 'jodyn' )     => $meta['programme'] ?? '',
					__( 'Name', 'jodyn' )          => $meta['first_name'] . ' ' . $meta['last_name'],
					__( 'Email address', 'jodyn' ) => $meta['email'],
					__( 'Phone number', 'jodyn' )  => $meta['phone'],
					__( 'Organisation', 'jodyn' )  => $meta['organisation'],
					__( 'Job title', 'jodyn' )     => $meta['job_title'],
					__( 'Message', 'jodyn' )       => $meta['message'],
					__( 'Emailed to', 'jodyn' )    => $meta['emailed'] ? $meta['to'] : __( 'Not sent (check the site’s email setup)', 'jodyn' ),
				);
				jodyn_form_table( $rows );
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
	$in = (array) $request->get_json_params();

	// Bots fill every field; people never see this one. Pretend it worked.
	if ( jodyn_form_is_bot( $in ) ) {
		return rest_ensure_response( array( 'ok' => true ) );
	}

	$types     = jodyn_enquiry_types();
	$enquiry   = jodyn_form_text( $in, 'enquiry' );
	$message   = jodyn_form_text( $in, 'message', 5000, true );
	$programme = jodyn_enquiry_programme( jodyn_form_text( $in, 'programme' ) );
	$meta      = array(
		'first_name'   => jodyn_form_text( $in, 'first_name', 80 ),
		'last_name'    => jodyn_form_text( $in, 'last_name', 80 ),
		'email'        => sanitize_email( jodyn_form_text( $in, 'email' ) ),
		'phone'        => jodyn_form_text( $in, 'phone', 40 ),
		'organisation' => jodyn_form_text( $in, 'organisation' ),
		'job_title'    => jodyn_form_text( $in, 'job_title' ),
		'enquiry'      => $enquiry,
		'programme'    => $programme ? get_the_title( $programme ) : '',
		'message'      => $message,
		'emailed'      => false,
		'to'           => '',
	);
	if ( '' === $meta['first_name'] || '' === $meta['last_name'] || ! is_email( $meta['email'] ) || ! isset( $types[ $enquiry ] ) || '' === trim( $message ) || empty( $in['privacy'] ) ) {
		return new WP_Error( 'jodyn_enquiry_invalid', __( 'Please complete the fields marked *.', 'jodyn' ), array( 'status' => 400 ) );
	}
	$limited = jodyn_form_rate_limit( 'msg', __( 'Too many messages. Please try again in a few minutes.', 'jodyn' ) );
	if ( $limited ) {
		return $limited;
	}

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
		$meta['programme'] ? 'Programme: ' . $meta['programme'] : null, // Left out below when empty.
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
	$subject         = sprintf( __( '%1$s from %2$s', 'jodyn' ), $types[ $enquiry ][0] . ( $meta['programme'] ? ': ' . $meta['programme'] : '' ), $name );
	$meta['to']      = $to;
	$meta['emailed'] = (bool) wp_mail( $to, $subject, implode( "\n", array_filter( $body, 'is_string' ) ), array( 'Reply-To: ' . $name . ' <' . $meta['email'] . '>' ) );
	update_post_meta( $id, '_jodyn_enquiry', $meta );

	return rest_ensure_response( array( 'ok' => true ) );
}
