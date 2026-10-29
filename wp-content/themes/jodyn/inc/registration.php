<?php
/**
 * Programme registrations (the form on /register/, template-parts/register/form.php).
 *
 * - Each registration is saved as a "Registration" (admin menu, read only) with every answer.
 * - The participant gets the confirmation email (content/pages/registration.md, section 5) and the
 *   training team is copied in. Sending needs the live site's email set up (an SMTP plugin); the
 *   registration is saved either way.
 * - Customize → Training registration: the training email, bank details and how many days before
 *   the programme the joining instructions go out. Empty fields show as placeholders.
 * - Spam guard: a hidden honeypot field and a limit of 5 registrations per visitor per 10 minutes
 *   (inc/forms.php).
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * "How did you hear about us?" choices.
 *
 * @return array value => label
 */
function jodyn_registration_sources() {
	return array(
		'linkedin'  => __( 'LinkedIn', 'jodyn' ),
		'instagram' => __( 'Instagram', 'jodyn' ),
		'facebook'  => __( 'Facebook', 'jodyn' ),
		'google'    => __( 'Google search', 'jodyn' ),
		'referral'  => __( 'Referral', 'jodyn' ),
		'email'     => __( 'Email', 'jodyn' ),
		'other'     => __( 'Other', 'jodyn' ),
	);
}

/**
 * A Customize → Training registration value, or its placeholder when empty.
 *
 * @param string $key         Setting key without the jodyn_reg_ prefix.
 * @param string $placeholder Shown until the client fills it in.
 * @return string
 */
function jodyn_registration_setting( $key, $placeholder ) {
	$value = trim( (string) get_theme_mod( 'jodyn_reg_' . $key, '' ) );
	return '' === $value ? $placeholder : $value;
}

/**
 * The training team's email: Customize → Training registration, else the footer's email.
 *
 * @return string
 */
function jodyn_registration_email() {
	$email = sanitize_email( (string) get_theme_mod( 'jodyn_reg_email', '' ) );
	if ( ! $email ) {
		$email = sanitize_email( (string) get_theme_mod( 'jodyn_contact_email', '' ) );
	}
	return $email;
}

/**
 * Programmes people can register for: scheduled from today on and not fully booked.
 *
 * @return array[] jodyn_programme_data() for each, soonest first.
 */
function jodyn_registration_programmes() {
	return array_values( wp_list_filter( array_map( 'jodyn_programme_data', jodyn_get_upcoming_programmes( -1 ) ), array( 'open' => true ) ) );
}

/**
 * Registrations list in the admin (read only: created by the form, never by hand).
 */
function jodyn_register_registrations() {
	jodyn_form_post_type(
		'jodyn_registration',
		array(
			'name'          => __( 'Registrations', 'jodyn' ),
			'singular_name' => __( 'Registration', 'jodyn' ),
			'all_items'     => __( 'Registrations', 'jodyn' ),
			'edit_item'     => __( 'Registration', 'jodyn' ),
			'search_items'  => __( 'Search registrations', 'jodyn' ),
			'not_found'     => __( 'No registrations yet', 'jodyn' ),
		),
		'dashicons-id-alt'
	);
}
add_action( 'init', 'jodyn_register_registrations' );

add_filter(
	'manage_jodyn_registration_posts_columns',
	function ( $columns ) {
		return array(
			'cb'                => $columns['cb'],
			'title'             => __( 'Name', 'jodyn' ),
			'jodyn_programme'   => __( 'Programme', 'jodyn' ),
			'jodyn_type'        => __( 'Type', 'jodyn' ),
			'jodyn_contact'     => __( 'Email and phone', 'jodyn' ),
			'jodyn_total'       => __( 'Participants and fee', 'jodyn' ),
			'date'              => __( 'Registered', 'jodyn' ),
		);
	}
);
add_action(
	'manage_jodyn_registration_posts_custom_column',
	function ( $column, $post_id ) {
		$meta = get_post_meta( $post_id, '_jodyn_registration', true );
		if ( ! is_array( $meta ) ) {
			return;
		}
		if ( 'jodyn_programme' === $column ) {
			echo esc_html( $meta['programme_title'] );
			echo '<br><span class="description">' . esc_html( $meta['programme_when'] ) . '</span>';
		} elseif ( 'jodyn_type' === $column ) {
			echo 'organisation' === $meta['type'] ? esc_html( $meta['organisation'] ) : esc_html__( 'Individual', 'jodyn' );
		} elseif ( 'jodyn_contact' === $column ) {
			printf( '<a href="mailto:%1$s">%2$s</a><br>%3$s', esc_attr( $meta['email'] ), esc_html( $meta['email'] ), esc_html( $meta['phone'] ) );
		} elseif ( 'jodyn_total' === $column ) {
			echo esc_html( $meta['participants'] . ' · ' . $meta['fee_line'] );
		}
	},
	10,
	2
);

/**
 * The registration's answers on its admin screen.
 */
add_action(
	'add_meta_boxes_jodyn_registration',
	function () {
		add_meta_box(
			'jodyn-registration',
			__( 'Registration details', 'jodyn' ),
			function ( $post ) {
				$meta = get_post_meta( $post->ID, '_jodyn_registration', true );
				if ( ! is_array( $meta ) ) {
					return;
				}
				$sources = jodyn_registration_sources();
				$rows    = array(
					__( 'Programme', 'jodyn' )            => $meta['programme_title'] . ' (' . $meta['programme_when'] . ')',
					__( 'Format', 'jodyn' )               => $meta['format_line'],
					__( 'Fee', 'jodyn' )                  => $meta['fee_line'],
					__( 'Registering', 'jodyn' )          => 'organisation' === $meta['type'] ? __( 'On behalf of an organisation', 'jodyn' ) : __( 'As an individual', 'jodyn' ),
					__( 'Full name', 'jodyn' )            => $meta['name'],
					__( 'Email address', 'jodyn' )        => $meta['email'],
					__( 'Phone number', 'jodyn' )         => $meta['phone'],
					__( 'Job title', 'jodyn' )            => $meta['job_title'],
					__( 'Organisation', 'jodyn' )         => $meta['organisation'],
					__( 'Number of participants', 'jodyn' ) => $meta['participants'],
				);
				foreach ( $meta['others'] as $i => $other ) {
					/* translators: %d: participant number. */
					$rows[ sprintf( __( 'Participant %d', 'jodyn' ), $i + 2 ) ] = implode( ' · ', array_filter( $other ) );
				}
				$rows[ __( 'Invoicing contact', 'jodyn' ) ]       = implode( ' · ', array_filter( $meta['invoice'] ) );
				$rows[ __( 'Heard about us', 'jodyn' ) ]          = isset( $sources[ $meta['source'] ] ) ? $sources[ $meta['source'] ] : '';
				$rows[ __( 'Special requirements', 'jodyn' ) ]    = $meta['requirements'];
				$rows[ __( 'Wants updates', 'jodyn' ) ]           = $meta['updates'] ? __( 'Yes', 'jodyn' ) : __( 'No', 'jodyn' );
				$rows[ __( 'Confirmation email sent', 'jodyn' ) ] = $meta['emailed'] ? __( 'Yes', 'jodyn' ) : __( 'No (check the site’s email setup)', 'jodyn' );
				jodyn_form_table( $rows, 220 );
			},
			'jodyn_registration',
			'normal',
			'high'
		);
	}
);

/**
 * Customize → Training registration.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function jodyn_registration_customize( $wp_customize ) {
	$wp_customize->add_section(
		'jodyn_registration',
		array(
			'title'       => __( 'Training registration', 'jodyn' ),
			'description' => __( 'Used in the registration form and the confirmation email people receive. Registrations are listed under Registrations.', 'jodyn' ),
			'priority'    => 37,
		)
	);
	$fields = array(
		'email'        => array( __( 'Training team email', 'jodyn' ), __( 'Receives a copy of every registration and is shown on the form. Leave empty to use the footer email.', 'jodyn' ), 'email' ),
		'bank'         => array( __( 'Bank name', 'jodyn' ), '', 'text' ),
		'account_name' => array( __( 'Account name', 'jodyn' ), '', 'text' ),
		'account'      => array( __( 'Account number', 'jodyn' ), '', 'text' ),
		'days'         => array( __( 'Joining instructions are sent (days before)', 'jodyn' ), __( 'For example 3.', 'jodyn' ), 'text' ),
	);
	foreach ( $fields as $key => $field ) {
		$wp_customize->add_setting(
			'jodyn_reg_' . $key,
			array(
				'default'           => '',
				'sanitize_callback' => 'email' === $field[2] ? 'sanitize_email' : 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'jodyn_reg_' . $key,
			array(
				'label'       => $field[0],
				'description' => $field[1],
				'section'     => 'jodyn_registration',
				'type'        => $field[2],
			)
		);
	}
}
add_action( 'customize_register', 'jodyn_registration_customize' );

/**
 * POST /wp-json/jodyn/v1/register
 */
function jodyn_registration_route() {
	register_rest_route(
		'jodyn/v1',
		'/register',
		array(
			'methods'             => 'POST',
			'permission_callback' => '__return_true', // Public form; guarded by the honeypot and rate limit below.
			'callback'            => 'jodyn_registration_submit',
		)
	);
}
add_action( 'rest_api_init', 'jodyn_registration_route' );

/**
 * Check and save a registration, then send the confirmation email.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function jodyn_registration_submit( $request ) {
	$in   = (array) $request->get_json_params();
	$text = static function ( $key, $max = 160 ) use ( $in ) {
		return jodyn_form_text( $in, $key, $max );
	};
	$fail = static function ( $message ) {
		return new WP_Error( 'jodyn_registration_invalid', $message, array( 'status' => 400 ) );
	};

	// Bots fill every field; people never see this one. Pretend it worked.
	if ( jodyn_form_is_bot( $in ) ) {
		return rest_ensure_response( array( 'ok' => true ) );
	}

	// The programme must still be open for registration
	$programme = null;
	foreach ( jodyn_registration_programmes() as $p ) {
		if ( (int) $p['id'] === (int) $text( 'programme' ) ) {
			$programme = $p;
		}
	}
	if ( ! $programme ) {
		return $fail( __( 'This programme is no longer open for registration. Please choose another.', 'jodyn' ) );
	}

	$invalid = __( 'Please complete the fields marked *.', 'jodyn' );
	$type    = 'organisation' === $text( 'type' ) ? 'organisation' : 'individual';
	$email   = sanitize_email( $text( 'email' ) );
	$name    = $text( 'name', 120 );
	$phone   = $text( 'phone', 40 );
	$org     = $text( 'organisation' );
	$pref    = in_array( $text( 'preferred_format' ), array( 'in-person', 'online' ), true ) ? $text( 'preferred_format' ) : '';
	if ( '' === $name || ! is_email( $email ) || '' === $phone || empty( $in['terms'] ) ) {
		return $fail( $invalid );
	}
	if ( 'hybrid' === $programme['format_key'] && ! $pref ) {
		return $fail( $invalid );
	}
	if ( 'organisation' === $type && '' === $org ) {
		return $fail( $invalid );
	}

	$participants = 'organisation' === $type ? max( 2, min( 30, (int) $text( 'participants' ) ) ) : 1;
	$others       = array();
	if ( 'organisation' === $type && isset( $in['others'] ) && is_array( $in['others'] ) ) {
		foreach ( array_slice( $in['others'], 0, $participants - 1 ) as $other ) {
			$other    = is_array( $other ) ? $other : array();
			$others[] = array(
				'name'      => isset( $other['name'] ) && is_scalar( $other['name'] ) ? sanitize_text_field( (string) $other['name'] ) : '',
				'email'     => isset( $other['email'] ) && is_scalar( $other['email'] ) ? sanitize_email( (string) $other['email'] ) : '',
				'job_title' => isset( $other['job_title'] ) && is_scalar( $other['job_title'] ) ? sanitize_text_field( (string) $other['job_title'] ) : '',
			);
		}
	}
	$limited = jodyn_form_rate_limit( 'reg', __( 'Too many registrations. Please try again in a few minutes.', 'jodyn' ) );
	if ( $limited ) {
		return $limited;
	}

	$formats     = jodyn_programme_formats();
	$format_line = $programme['format_name'];
	if ( 'hybrid' === $programme['format_key'] ) {
		/* translators: %s: In-person or Online. */
		$format_line .= ' ' . sprintf( __( '(attending %s)', 'jodyn' ), strtolower( $formats[ $pref ] ) );
	}
	if ( $programme['venue'] ) {
		$format_line .= ', ' . $programme['venue'];
	}
	$amount   = $programme['fee_amount'];
	$fee_line = $amount ? '₦' . number_format( $amount * $participants ) : (string) $programme['fee'];
	/* translators: %d: number of participants. */
	$fee_line .= 1 === $participants ? ' ' . __( 'per participant', 'jodyn' ) : ' ' . sprintf( __( 'total for %d participants', 'jodyn' ), $participants );

	$source = $text( 'source' );
	$meta   = array(
		'programme_id'    => $programme['id'],
		'programme_title' => $programme['title'],
		'programme_when'  => $programme['when'],
		'format_line'     => $format_line,
		'fee_line'        => $fee_line,
		'type'            => $type,
		'name'            => $name,
		'email'           => $email,
		'phone'           => $phone,
		'job_title'       => $text( 'job_title' ),
		'organisation'    => $org,
		'participants'    => $participants,
		'others'          => $others,
		'invoice'         => array(
			'name'  => $text( 'invoice_name' ),
			'email' => sanitize_email( $text( 'invoice_email' ) ),
			'phone' => $text( 'invoice_phone', 40 ),
		),
		'source'          => array_key_exists( $source, jodyn_registration_sources() ) ? $source : '',
		'requirements'    => jodyn_form_text( $in, 'requirements', 1000, true ),
		'updates'         => ! empty( $in['updates'] ),
		'emailed'         => false,
	);

	$id = wp_insert_post(
		array(
			'post_type'   => 'jodyn_registration',
			'post_status' => 'private',
			'post_title'  => $name,
		),
		true
	);
	if ( is_wp_error( $id ) ) {
		return new WP_Error( 'jodyn_registration_failed', __( 'Something went wrong. Please try again.', 'jodyn' ), array( 'status' => 500 ) );
	}
	$meta['emailed'] = jodyn_registration_send( $meta, $programme );
	update_post_meta( $id, '_jodyn_registration', $meta );

	return rest_ensure_response(
		array(
			'ok'    => true,
			'title' => $programme['title'],
			'email' => $email,
		)
	);
}

/**
 * The confirmation email (registration.md, section 5), copied to the training team.
 *
 * @param array $meta      The saved registration.
 * @param array $programme jodyn_programme_data() for the programme.
 * @return bool Whether the email was handed to the mail server.
 */
function jodyn_registration_send( $meta, $programme ) {
	$team  = jodyn_registration_email();
	$phone = trim( (string) get_theme_mod( 'jodyn_contact_phone', '' ) );
	$first = strtok( $meta['name'], ' ' );
	$date  = $programme['when'] . ( $programme['time'] ? ' · Time: ' . $programme['time'] : '' );
	$lines = array(
		"Dear {$first},",
		'',
		"Thank you for registering for {$meta['programme_title']} with JoDyn Advisory and Consulting. We are delighted to have you.",
		'',
		'Programme details',
		"Programme: {$meta['programme_title']}",
		"Date: {$date}",
		"Format: {$meta['format_line']}",
		"Fee: {$meta['fee_line']}",
		'',
		'Payment details',
		'Bank: ' . jodyn_registration_setting( 'bank', '[Bank name]' ) . ' · Account name: ' . jodyn_registration_setting( 'account_name', '[Account name]' ) . ' · Account number: ' . jodyn_registration_setting( 'account', '[Account number]' ),
		'Please use your full name or organisation name as the payment reference, and send proof of payment to ' . ( $team ? $team : '[training@jodynconsulting.com]' ) . '. Your seat is confirmed once payment is received.',
		'',
		'What happens next?',
		'Once payment is confirmed, we will send your joining instructions ' . jodyn_registration_setting( 'days', '[X]' ) . ' days before the programme.',
		'',
		'If you have any questions, simply reply to this email or call ' . ( $phone ? $phone : '[phone number]' ) . '.',
		'',
		'Warm regards,',
		'The JoDyn Training Team',
		'JoDyn Advisory and Consulting',
	);
	$headers = array();
	if ( $team ) {
		$headers[] = 'Cc: ' . $team;
		$headers[] = 'Reply-To: ' . $team;
	}
	/* translators: %s: programme title. */
	$subject = sprintf( __( 'Your registration for %s is received.', 'jodyn' ), $meta['programme_title'] );
	return (bool) wp_mail( $meta['email'], $subject, implode( "\n", $lines ), $headers );
}
