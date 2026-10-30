<?php
/**
 * The site's emails in the JoDyn design (option A, chosen 2026-10-30): a forest header with the
 * white logo, a white body with a lime label, soft green boxes, a forest button and a forest
 * footer with the contact details. Email-safe HTML: 600px tables, inline styles, a PNG logo.
 *
 * - jodyn_email_html() builds one email from blocks; jodyn_email_send() sends it as HTML with a
 *   plain-text version alongside, for mail apps that don't show HTML.
 * - Used by the contact form (inc/enquiries.php: the team's notification and the reply to the
 *   sender) and registration (inc/registration.php: the confirmation, copied to the team).
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The email colours and font, from the design tokens (assets/css/tokens.css).
 *
 * @return array<string, string>
 */
function jodyn_email_style() {
	return array(
		'forest' => '#233831',
		'lime'   => '#e5fd4a',
		'soft'   => '#f3f6f2',
		'canvas' => '#eef2ef',
		'ink'    => '#1b2621',
		'muted'  => '#4a5a54',
		'line'   => '#dbe2de',
		'onDark' => '#b9c8c1',
		'font'   => "'Google Sans','Google Sans Flex',Helvetica,Arial,sans-serif",
	);
}

/**
 * A paragraph.
 *
 * @param string $html   Escaped HTML.
 * @param array  $css    Overrides: size, color, margin, weight.
 * @return string
 */
function jodyn_email_p( $html, $css = array() ) {
	$s   = jodyn_email_style();
	$css = wp_parse_args(
		$css,
		array(
			'size'   => 16,
			'color'  => $s['ink'],
			'margin' => '0 0 16px',
			'weight' => '400',
		)
	);
	return sprintf(
		'<p style="margin:%1$s;font-family:%2$s;font-size:%3$dpx;line-height:1.6;color:%4$s;font-weight:%5$s">%6$s</p>',
		esc_attr( $css['margin'] ),
		esc_attr( $s['font'] ),
		(int) $css['size'],
		esc_attr( $css['color'] ),
		esc_attr( $css['weight'] ),
		$html
	);
}

/**
 * One email.
 *
 * @param array $args {
 *     @type string $preheader The preview line mail apps show after the subject.
 *     @type string $eyebrow   The lime label, e.g. "Message received".
 *     @type string $heading   The heading.
 *     @type string $intro     The opening paragraph (escaped HTML; <b> for emphasis).
 *     @type array  $blocks    What follows, in order: array( 'box', title, rows (label => value
 *                             or label => array( text, url )), quote, note ), array( 'text', html )
 *                             or array( 'title', text ).
 *     @type array  $button    Optional array( label, url ).
 *     @type array  $signoff   Lines: greeting, team, company.
 *     @type string $why       The small print at the bottom.
 * }
 * @return string
 */
function jodyn_email_html( $args ) {
	$s = jodyn_email_style();
	$c = jodyn_contact();
	$a = wp_parse_args(
		$args,
		array(
			'preheader' => '',
			'eyebrow'   => '',
			'heading'   => '',
			'intro'     => '',
			'blocks'    => array(),
			'button'    => array(),
			'signoff'   => array(),
			'why'       => '',
		)
	);

	$body = '';
	if ( $a['eyebrow'] ) {
		$body .= sprintf( '<span style="display:inline-block;background:%1$s;color:%2$s;font-family:%3$s;font-size:12px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;padding:6px 12px;border-radius:999px">%4$s</span>', $s['lime'], $s['forest'], esc_attr( $s['font'] ), esc_html( $a['eyebrow'] ) );
	}
	$body .= sprintf( '<h1 style="margin:16px 0 14px;font-family:%1$s;font-size:30px;line-height:1.2;color:%2$s;font-weight:700">%3$s</h1>', esc_attr( $s['font'] ), $s['ink'], esc_html( $a['heading'] ) );
	if ( $a['intro'] ) {
		$body .= jodyn_email_p( $a['intro'], array( 'margin' => '0 0 24px' ) );
	}

	foreach ( $a['blocks'] as $block ) {
		if ( 'text' === $block[0] ) {
			$body .= jodyn_email_p( $block[1] );
		} elseif ( 'title' === $block[0] ) {
			$body .= jodyn_email_p( esc_html( $block[1] ), array( 'margin' => '24px 0 6px', 'weight' => '700', 'color' => $s['forest'] ) );
		} elseif ( 'box' === $block[0] ) {
			$rows = '';
			foreach ( (array) ( $block[2] ?? array() ) as $label => $value ) {
				if ( is_array( $value ) ) {
					$value = '' === (string) $value[0] ? '' : sprintf( '<a href="%1$s" style="color:%2$s;text-decoration:underline">%3$s</a>', esc_url( $value[1] ), $s['forest'], esc_html( $value[0] ) );
				} else {
					$value = esc_html( (string) $value );
				}
				if ( '' === $value ) {
					continue;
				}
				$rows .= sprintf(
					'<tr><td style="padding:10px 12px 10px 0;border-bottom:1px solid %1$s;font-family:%2$s;font-size:14px;color:%3$s;width:150px;vertical-align:top">%4$s</td><td style="padding:10px 0;border-bottom:1px solid %1$s;font-family:%2$s;font-size:15px;color:%5$s;vertical-align:top">%6$s</td></tr>',
					$s['line'],
					esc_attr( $s['font'] ),
					$s['muted'],
					esc_html( $label ),
					$s['ink'],
					$value
				);
			}
			$inner = $block[1] ? jodyn_email_p( esc_html( $block[1] ), array( 'size' => 13, 'color' => $s['forest'], 'margin' => '0 0 4px', 'weight' => '700' ) ) : '';
			if ( $rows ) {
				$inner .= '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse">' . $rows . '</table>';
			}
			if ( ! empty( $block[3] ) ) {
				$inner .= sprintf(
					'<table role="presentation" width="100%%" cellpadding="0" cellspacing="0" style="margin-top:14px"><tr><td style="width:4px;background:%1$s"></td><td style="background:#ffffff;padding:14px 18px;font-family:%2$s;font-size:15px;line-height:1.6;color:%3$s">%4$s</td></tr></table>',
					$s['forest'],
					esc_attr( $s['font'] ),
					$s['ink'],
					nl2br( esc_html( $block[3] ) )
				);
			}
			if ( ! empty( $block[4] ) ) {
				$inner .= jodyn_email_p( $block[4], array( 'size' => 14, 'margin' => '14px 0 0' ) );
			}
			$body .= sprintf( '<table role="presentation" width="100%%" cellpadding="0" cellspacing="0" style="background:%1$s;border-radius:16px;margin:0 0 8px"><tr><td style="padding:20px 24px">%2$s</td></tr></table>', $s['soft'], $inner );
		}
	}

	if ( $a['button'] ) {
		$body .= sprintf(
			'<table role="presentation" cellpadding="0" cellspacing="0" style="margin-top:16px"><tr><td style="border-radius:999px;background:%1$s"><a href="%2$s" style="display:inline-block;padding:14px 28px;font-family:%3$s;font-size:15px;font-weight:700;color:#ffffff;text-decoration:none;border-radius:999px">%4$s &rarr;</a></td></tr></table>',
			$s['forest'],
			esc_url( $a['button'][1] ),
			esc_attr( $s['font'] ),
			esc_html( $a['button'][0] )
		);
	}
	if ( $a['signoff'] ) {
		$lines = array_values( $a['signoff'] );
		$body .= jodyn_email_p(
			esc_html( $lines[0] ) . ( isset( $lines[1] ) ? '<br><b>' . esc_html( $lines[1] ) . '</b>' : '' ) . ( isset( $lines[2] ) ? '<br><span style="color:' . $s['muted'] . '">' . esc_html( $lines[2] ) . '</span>' : '' ),
			array( 'size' => 15, 'margin' => '28px 0 0' )
		);
	}

	// Footer: the logo and the contact details from Customize → Contact & social.
	$site    = wp_parse_url( home_url(), PHP_URL_HOST );
	$details = array_filter( array( $c['phone'], $c['email'] ) );
	$footer  = sprintf( '<img src="%1$s" width="84" alt="JoDyn Advisory and Consulting" style="display:inline-block;width:84px;height:auto;border:0;margin-bottom:14px">', esc_url( get_template_directory_uri() . '/assets/img/logo-white.png' ) );
	if ( $c['address'] || $details ) {
		$footer .= jodyn_email_p( trim( esc_html( preg_replace( '/\s*\n\s*/', ', ', $c['address'] ) ) . ( $c['address'] && $details ? '<br>' : '' ) . esc_html( implode( ' · ', $details ) ) ), array( 'size' => 13, 'color' => $s['onDark'], 'margin' => '0 0 10px' ) );
	}
	$footer .= jodyn_email_p( sprintf( '<a href="%1$s" style="color:%2$s;text-decoration:none;font-weight:700">%3$s</a>', esc_url( home_url( '/' ) ), $s['lime'], esc_html( $site ) ), array( 'size' => 13, 'margin' => '0 0 14px' ) );
	if ( $a['why'] ) {
		$footer .= jodyn_email_p( esc_html( $a['why'] ), array( 'size' => 12, 'color' => '#8fa39a', 'margin' => '0' ) );
	}

	return '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>' . esc_html( $a['heading'] ) . '</title>'
		. '<style>@media (max-width:620px){.jd-w{width:100%!important}.jd-px{padding-left:22px!important;padding-right:22px!important}}</style></head>'
		. '<body style="margin:0;padding:0;background:' . $s['canvas'] . '">'
		. ( $a['preheader'] ? '<div style="display:none;max-height:0;overflow:hidden;opacity:0">' . esc_html( $a['preheader'] ) . '</div>' : '' )
		. '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:' . $s['canvas'] . '"><tr><td align="center" style="padding:32px 12px">'
		. '<table role="presentation" class="jd-w" width="600" cellpadding="0" cellspacing="0" style="width:600px;max-width:600px;background:#ffffff;border-radius:20px;overflow:hidden">'
		. sprintf( '<tr><td style="background:%1$s;padding:32px 40px;text-align:center"><img src="%2$s" width="120" alt="JoDyn Advisory and Consulting" style="width:120px;height:auto;border:0"></td></tr>', $s['forest'], esc_url( get_template_directory_uri() . '/assets/img/logo-white.png' ) )
		. '<tr><td class="jd-px" style="padding:36px 40px">' . $body . '</td></tr>'
		. '<tr><td class="jd-px" style="background:' . $s['forest'] . ';padding:28px 40px;text-align:center">' . $footer . '</td></tr>'
		. '</table></td></tr></table></body></html>';
}

/**
 * Send an email as HTML, with a plain-text version for mail apps that don't show HTML.
 *
 * @param string|string[] $to      Recipient(s).
 * @param string          $subject Subject.
 * @param string          $html    From jodyn_email_html().
 * @param string          $text    Plain-text version.
 * @param string[]        $headers Extra headers (Cc, Reply-To).
 * @return bool Whether the email was handed to the mail server.
 */
function jodyn_email_send( $to, $subject, $html, $text, $headers = array() ) {
	$alt = static function ( $mailer ) use ( $text ) {
		$mailer->AltBody = $text; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- PHPMailer's property.
	};
	add_action( 'phpmailer_init', $alt );
	$headers[] = 'Content-Type: text/html; charset=UTF-8';
	$sent      = (bool) wp_mail( $to, $subject, $html, $headers );
	remove_action( 'phpmailer_init', $alt );
	return $sent;
}
