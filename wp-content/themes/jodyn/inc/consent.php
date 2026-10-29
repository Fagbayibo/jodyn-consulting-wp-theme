<?php
/**
 * Cookie consent and Google Analytics.
 *
 * Google Analytics (GA4) only loads after the visitor chooses Accept in the cookie banner
 * (template-parts/cookie-banner.php, option D: a lime card in the bottom-right corner). The choice
 * is kept in the jodyn_consent cookie for 12 months (assets/js/consent.js). The footer's
 * "Cookie settings" link opens the banner again. With no Measurement ID in Customize there is
 * nothing optional to ask about, so neither the banner nor the link is shown.
 * Copy: content/pages/legal.md, Part 4 (approved); Cookie Policy, section 2.
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customize → Analytics: the GA4 Measurement ID.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function jodyn_consent_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'jodyn_analytics',
		array(
			'title'       => __( 'Analytics', 'jodyn' ),
			'description' => __( 'Google Analytics only loads for visitors who choose Accept in the cookie banner. The banner and the footer "Cookie settings" link appear once an ID is entered.', 'jodyn' ),
			'priority'    => 34,
		)
	);
	$wp_customize->add_setting(
		'jodyn_ga4_id',
		array(
			'default'           => '',
			'sanitize_callback' => 'jodyn_sanitize_ga4_id',
		)
	);
	$wp_customize->add_control(
		'jodyn_ga4_id',
		array(
			'label'       => __( 'Google Analytics Measurement ID', 'jodyn' ),
			'description' => __( 'Starts with G-, e.g. G-AB12CD34EF. Find it in Google Analytics → Admin → Data streams.', 'jodyn' ),
			'section'     => 'jodyn_analytics',
			'type'        => 'text',
		)
	);
}
add_action( 'customize_register', 'jodyn_consent_customize_register' );

/**
 * Keeps only a well-formed GA4 Measurement ID.
 *
 * @param string $value Entered ID.
 * @return string
 */
function jodyn_sanitize_ga4_id( $value ) {
	$value = strtoupper( trim( (string) $value ) );
	return preg_match( '/^G-[A-Z0-9]{4,20}$/', $value ) ? $value : '';
}

/**
 * The GA4 Measurement ID, or '' when analytics is off.
 *
 * @return string
 */
function jodyn_ga4_id() {
	return jodyn_sanitize_ga4_id( get_theme_mod( 'jodyn_ga4_id', '' ) );
}

/**
 * The banner's style and script, on every page while analytics is on.
 */
function jodyn_consent_enqueue() {
	if ( ! jodyn_ga4_id() ) {
		return;
	}
	wp_enqueue_style( 'jodyn-consent', get_template_directory_uri() . '/assets/css/consent.css', array( 'jodyn-base' ), jodyn_asset_version( 'assets/css/consent.css' ) );
	jodyn_enqueue_script( 'jodyn-consent', 'consent.js' );
}
add_action( 'wp_enqueue_scripts', 'jodyn_consent_enqueue', 20 );

/**
 * The banner itself, at the end of the page. It starts hidden; consent.js shows it when the
 * visitor has not chosen yet, so the page can be cached the same for everyone.
 */
function jodyn_consent_banner() {
	if ( jodyn_ga4_id() && ! is_admin() ) {
		get_template_part( 'template-parts/cookie-banner' );
	}
}
add_action( 'wp_footer', 'jodyn_consent_banner', 5 );
