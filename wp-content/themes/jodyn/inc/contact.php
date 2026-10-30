<?php
/**
 * Contact details and social links (Customizer → Contact & social), plus the footer
 * quick-links fallback. Used by the footer and anywhere else that shows contact details.
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Social networks the footer can show, in display order.
 *
 * @return array<string, array{label: string, path: string}> Keyed by setting suffix.
 */
function jodyn_social_networks() {
	return array(
		'linkedin'  => array(
			'label' => 'LinkedIn',
			'path'  => '<path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9h4v12H3zM9 9h3.8v1.7h.05c.53-1 1.83-2.05 3.77-2.05C20.6 8.65 21 11.2 21 14.4V21h-4v-5.8c0-1.4-.03-3.2-1.95-3.2-1.95 0-2.25 1.5-2.25 3.1V21H9z"/>',
		),
		'instagram' => array(
			'label' => 'Instagram',
			'path'  => '<path fill-rule="evenodd" d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9a5.5 5.5 0 0 1-5.5 5.5h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4zM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm5.25-3.5a1.25 1.25 0 1 1 0 2.5 1.25 1.25 0 0 1 0-2.5z"/>',
		),
		'facebook'  => array(
			'label' => 'Facebook',
			'path'  => '<path d="M14 8h3V4h-3c-2.8 0-4.5 1.7-4.5 4.5V11H7v4h2.5v7h4v-7h3l.5-4h-3.5V8.8c0-.5.3-.8.5-.8z"/>',
		),
		'x'         => array(
			'label' => 'X (Twitter)',
			'path'  => '<path d="M17.75 3h3.07l-6.7 7.66L22 21h-6.17l-4.83-6.32L5.47 21H2.4l7.17-8.2L2 3h6.33l4.37 5.78zM16.67 19.2h1.7L7.4 4.7H5.57z"/>',
		),
		'youtube'   => array(
			'label' => 'YouTube',
			'path'  => '<path fill-rule="evenodd" d="M21.6 7.2a2.5 2.5 0 0 0-1.77-1.77C18.27 5 12 5 12 5s-6.27 0-7.83.43A2.5 2.5 0 0 0 2.4 7.2 26 26 0 0 0 2 12a26 26 0 0 0 .4 4.8 2.5 2.5 0 0 0 1.77 1.77C5.73 19 12 19 12 19s6.27 0 7.83-.43a2.5 2.5 0 0 0 1.77-1.77A26 26 0 0 0 22 12a26 26 0 0 0-.4-4.8zM10 15V9l5.2 3z"/>',
		),
	);
}

/**
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function jodyn_contact_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'jodyn_contact',
		array(
			'title'       => __( 'Contact & social', 'jodyn' ),
			'description' => __( 'Shown in the footer and on the Contact page. Leave a field empty to hide it.', 'jodyn' ),
			'priority'    => 32,
		)
	);

	$fields = array(
		'jodyn_contact_address'  => array( __( 'Office address', 'jodyn' ), 'textarea', 'sanitize_textarea_field' ),
		'jodyn_contact_phone'    => array( __( 'Phone number', 'jodyn' ), 'text', 'sanitize_text_field' ),
		'jodyn_contact_email'    => array( __( 'Email address', 'jodyn' ), 'email', 'sanitize_email' ),
		'jodyn_contact_whatsapp' => array( __( 'WhatsApp number', 'jodyn' ), 'text', 'sanitize_text_field', __( 'With the country code, e.g. +234 803 000 0000. Shows the Chat on WhatsApp button on the Contact page.', 'jodyn' ) ),
		'jodyn_contact_hours'    => array( __( 'Office hours', 'jodyn' ), 'text', 'sanitize_text_field', __( 'e.g. Monday to Friday, 9:00 am to 5:00 pm (WAT)', 'jodyn' ) ),
		'jodyn_contact_privacy_email' => array( __( 'Privacy email (optional)', 'jodyn' ), 'email', 'sanitize_email', __( 'For data protection questions, shown on the Privacy and Cookie policies. Leave empty to use the main email address.', 'jodyn' ) ),
		'jodyn_contact_response' => array( __( 'Response time', 'jodyn' ), 'text', 'sanitize_text_field', __( 'How soon you reply, e.g. 48 to 72 hours. Used on the Contact page, the FAQs and the reply email.', 'jodyn' ) ),
	);
	// Contact page, "Ways to reach us": each line can have its own email and phone
	foreach ( jodyn_contact_lines() as $key => $line ) {
		/* translators: %s: enquiry line, e.g. Training Enquiries. */
		$fields[ "jodyn_contact_{$key}_email" ] = array( sprintf( __( '%s: email (optional)', 'jodyn' ), $line ), 'email', 'sanitize_email', __( 'Leave empty to use the main email address.', 'jodyn' ) );
		/* translators: %s: enquiry line, e.g. Training Enquiries. */
		$fields[ "jodyn_contact_{$key}_phone" ] = array( sprintf( __( '%s: phone (optional)', 'jodyn' ), $line ), 'text', 'sanitize_text_field', __( 'Leave empty to use the main phone number.', 'jodyn' ) );
	}
	foreach ( jodyn_social_networks() as $key => $network ) {
		/* translators: %s: social network name. */
		$fields[ "jodyn_social_$key" ] = array( sprintf( __( '%s link', 'jodyn' ), $network['label'] ), 'url', 'esc_url_raw' );
	}

	foreach ( $fields as $id => $field ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => '',
				'sanitize_callback' => $field[2],
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'       => $field[0],
				'description' => isset( $field[3] ) ? $field[3] : '',
				'section'     => 'jodyn_contact',
				'type'        => $field[1],
			)
		);
	}
}
add_action( 'customize_register', 'jodyn_contact_customize_register' );

/**
 * The saved contact details. Empty strings for anything not filled in.
 *
 * @return array{address: string, phone: string, tel: string, email: string, whatsapp: string, wa: string, hours: string}
 */
function jodyn_contact() {
	$phone    = trim( (string) get_theme_mod( 'jodyn_contact_phone', '' ) );
	$whatsapp = trim( (string) get_theme_mod( 'jodyn_contact_whatsapp', '' ) );
	return array(
		'address'  => trim( (string) get_theme_mod( 'jodyn_contact_address', '' ) ),
		'phone'    => $phone,
		'tel'      => preg_replace( '/[^\d+]/', '', $phone ),
		'email'    => trim( (string) get_theme_mod( 'jodyn_contact_email', '' ) ),
		'whatsapp' => $whatsapp,
		'wa'       => preg_replace( '/\D/', '', $whatsapp ), // wa.me wants digits only, country code first
		'hours'    => trim( (string) get_theme_mod( 'jodyn_contact_hours', '' ) ),
	);
}

/**
 * The Contact page's three ways to reach us (content/pages/contact.md, section 2).
 *
 * @return array key => title
 */
function jodyn_contact_lines() {
	return array(
		'advisory' => __( 'Advisory, Audit and Tax Enquiries', 'jodyn' ),
		'training' => __( 'Training Enquiries', 'jodyn' ),
		'general'  => __( 'General Enquiries', 'jodyn' ),
	);
}

/**
 * One line's email and phone, falling back to the main details.
 *
 * @param string $key advisory, training or general.
 * @return array{email: string, phone: string, tel: string}
 */
function jodyn_contact_line( $key ) {
	$main  = jodyn_contact();
	$email = trim( (string) get_theme_mod( "jodyn_contact_{$key}_email", '' ) );
	$phone = trim( (string) get_theme_mod( "jodyn_contact_{$key}_phone", '' ) );
	$phone = $phone ? $phone : $main['phone'];
	return array(
		'email' => $email ? $email : $main['email'],
		'phone' => $phone,
		'tel'   => preg_replace( '/[^\d+]/', '', $phone ),
	);
}

/**
 * Social links that have a URL, in display order.
 *
 * @return array<int, array{label: string, path: string, url: string}>
 */
function jodyn_social_links() {
	$links = array();
	foreach ( jodyn_social_networks() as $key => $network ) {
		$url = get_theme_mod( "jodyn_social_$key", '' );
		if ( $url ) {
			$links[] = $network + array( 'url' => $url );
		}
	}
	return $links;
}

/**
 * Footer quick links when no menu is assigned to the "Footer navigation" location.
 *
 * @param array $args wp_nav_menu() arguments.
 */
function jodyn_footer_nav_fallback( $args ) {
	$blog  = (int) get_option( 'page_for_posts' );
	$links = array(
		__( 'Home', 'jodyn' )                => home_url( '/' ),
		__( 'About', 'jodyn' )               => home_url( '/about-us/' ),
		__( 'Advisory', 'jodyn' )            => home_url( '/services/advisory/' ),
		__( 'Systems Audit', 'jodyn' )       => home_url( '/services/systems-audit/' ),
		__( 'Tax Consultancy', 'jodyn' )     => home_url( '/services/tax-consultancy/' ),
		__( 'Training Programmes', 'jodyn' ) => home_url( '/training/programmes/' ),
		__( 'Training Calendar', 'jodyn' )   => home_url( '/training/calendar/' ),
		__( 'Insights', 'jodyn' )            => $blog ? get_permalink( $blog ) : home_url( '/insights/' ),
		__( 'FAQs', 'jodyn' )                => home_url( '/faqs/' ),
		__( 'Contact', 'jodyn' )             => home_url( '/contact-us/' ),
	);

	$out = '';
	foreach ( $links as $label => $url ) {
		$out .= sprintf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	$out = sprintf( '<ul class="%s">%s</ul>', esc_attr( $args['menu_class'] ), $out );

	if ( ! isset( $args['echo'] ) || $args['echo'] ) {
		echo $out; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts.
		return null;
	}
	return $out;
}
