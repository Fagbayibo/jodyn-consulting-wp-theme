<?php
/**
 * Legal pages: Privacy Policy (/privacy-policy/), Terms of Use (/terms/) and Cookie Policy
 * (/cookie-policy/). All three share template-parts/legal/page.php; their wording is in
 * inc/legal-content.php. Copy: content/pages/legal.md (approved).
 *
 * @package JoDyn
 */

require_once get_template_directory() . '/inc/legal-content.php';

/**
 * The three policies in tab order, with the line used in "Our other policies".
 *
 * @return array slug => array( title, line )
 */
function jodyn_legal_policies() {
	return array(
		'privacy-policy' => array( __( 'Privacy Policy', 'jodyn' ), __( 'How we collect, use and protect your personal data.', 'jodyn' ) ),
		'terms'          => array( __( 'Terms of Use', 'jodyn' ), __( 'The terms for using this website and registering for our programmes.', 'jodyn' ) ),
		'cookie-policy'  => array( __( 'Cookie Policy', 'jodyn' ), __( 'Which cookies we use, why, and how to control them.', 'jodyn' ) ),
	);
}

/**
 * A policy's URL: WordPress's own privacy page if one is set, otherwise the page by its path.
 *
 * @param string $slug privacy-policy, terms or cookie-policy.
 * @return string
 */
function jodyn_legal_url( $slug ) {
	if ( 'privacy-policy' === $slug && get_privacy_policy_url() ) {
		return get_privacy_policy_url();
	}
	return home_url( "/$slug/" );
}

/**
 * The email for privacy questions: Customize → Contact & social → Privacy email, or the main email.
 *
 * @return string
 */
function jodyn_privacy_email() {
	$email = trim( (string) get_theme_mod( 'jodyn_contact_privacy_email', '' ) );
	return $email ? $email : jodyn_contact()['email'];
}

/**
 * Fills a policy's tokens: contact details from Customize (as links) or the bracketed
 * placeholder until they are set, and the other policies' URLs.
 *
 * @param string $html Wording from jodyn_legal_content().
 * @return string
 */
function jodyn_legal_fill( $html ) {
	$c     = jodyn_contact();
	$email = function ( $address, $fallback ) {
		return $address ? '<a href="mailto:' . esc_attr( antispambot( $address ) ) . '">' . esc_html( antispambot( $address ) ) . '</a>' : $fallback;
	};
	return strtr(
		$html,
		array(
			'{privacy_email}' => $email( jodyn_privacy_email(), '[privacy@jodynconsulting.com]' ),
			'{email}'         => $email( $c['email'], '[info@jodynconsulting.com]' ),
			'{phone}'         => $c['phone'] ? '<a href="tel:' . esc_attr( $c['tel'] ) . '">' . esc_html( $c['phone'] ) . '</a>' : '[phone number]',
			'{address}'       => $c['address'] ? esc_html( preg_replace( '/\s*\n\s*/', ', ', $c['address'] ) ) : '[office address, City, State, Nigeria]',
			'{privacy_url}'   => esc_url( jodyn_legal_url( 'privacy-policy' ) ),
			'{cookie_url}'    => esc_url( jodyn_legal_url( 'cookie-policy' ) ),
		)
	);
}

/**
 * The help card beside each policy: its question and which email it points to.
 *
 * @param string $slug privacy-policy, terms or cookie-policy.
 * @return array{title: string, email: string}
 */
function jodyn_legal_help( $slug ) {
	$cards = array(
		'privacy-policy' => array( __( 'Questions about your data?', 'jodyn' ), '{privacy_email}' ),
		'terms'          => array( __( 'Questions about these terms?', 'jodyn' ), '{email}' ),
		'cookie-policy'  => array( __( 'Questions about cookies?', 'jodyn' ), '{privacy_email}' ),
	);
	return array(
		'title' => $cards[ $slug ][0],
		'email' => $cards[ $slug ][1],
	);
}

/**
 * The slug of the policy being viewed, if any.
 *
 * @return string|null
 */
function jodyn_legal_current() {
	foreach ( array_keys( jodyn_legal_policies() ) as $slug ) {
		if ( is_page( $slug ) || ( 'privacy-policy' === $slug && is_page() && (int) get_option( 'wp_page_for_privacy_policy' ) === get_queried_object_id() ) ) {
			return $slug;
		}
	}
	return null;
}
