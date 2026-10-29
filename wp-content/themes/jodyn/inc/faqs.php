<?php
/**
 * FAQs page (/faqs/): every page's questions together, in five topics.
 * Layout: template-parts/faqs/page.php; wording: inc/faqs-content.php.
 * Copy: content/pages/faqs.md (approved).
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/faqs-content.php';

/**
 * Fills an answer's tokens: {url:/path/} with a link on this site, {response} with the response
 * time from Customize (or its placeholder).
 *
 * @param string $html Answer from jodyn_faqs_content().
 * @return string
 */
function jodyn_faqs_fill( $html ) {
	$html = str_replace( '{response}', esc_html( jodyn_response_time() ), $html );
	return preg_replace_callback(
		'/\{url:([^}]+)\}/',
		static function ( $m ) {
			return esc_url( home_url( $m[1] ) );
		},
		$html
	);
}

/**
 * The FAQs page ends with its own footer card.
 * Copy: content/pages/faqs.md, Footer card (approved).
 */
add_filter(
	'jodyn_footer_cta',
	static function ( $cta ) {
		if ( ! is_page( 'faqs' ) ) {
			return $cta;
		}
		return array(
			'title'   => 'Still Have a <em>Question?</em>',
			/* translators: %s: response time, e.g. one working day. */
			'text'    => sprintf( __( 'Our team is happy to help. Send us a message and we will get back to you within %s.', 'jodyn' ), jodyn_response_time() ),
			'buttons' => array(
				array( __( 'Contact Us', 'jodyn' ), '/contact-us/' ),
			),
		);
	}
);
