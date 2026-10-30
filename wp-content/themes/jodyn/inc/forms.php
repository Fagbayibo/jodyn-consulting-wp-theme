<?php
/**
 * What the site's three forms share: the contact form (inc/enquiries.php), registration
 * (inc/registration.php) and the calendar download (inc/calendar-download.php).
 *
 * - Each saves its submissions as a read-only list in the admin (jodyn_form_post_type()).
 * - Spam guard: a hidden honeypot field (jodyn_form_is_bot()) and a limit per visitor
 *   (jodyn_form_rate_limit()), counted by the visitor's real IP even behind Cloudflare or a host's
 *   proxy (jodyn_client_ip()).
 * - Their personal data can be exported or erased from Tools → Export / Erase Personal Data.
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register a read-only post type for a form's submissions (created by the form, never by hand).
 *
 * @param string $type   Post type.
 * @param array  $labels name, singular_name, all_items, search_items, not_found (and edit_item).
 * @param string $icon   Dashicon.
 */
function jodyn_form_post_type( $type, $labels, $icon ) {
	register_post_type(
		$type,
		array(
			'labels'       => $labels,
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'menu_icon'    => $icon,
			'supports'     => array( 'title' ),
			'map_meta_cap' => true,
			'capabilities' => array( 'create_posts' => 'do_not_allow' ),
		)
	);
}

/**
 * A submitted text field: trimmed of tags and line breaks and cut to $max characters, or ''.
 *
 * @param array  $in        The submitted fields.
 * @param string $key       Field name.
 * @param int    $max       Longest allowed.
 * @param bool   $multiline Keep line breaks (a message box).
 * @return string
 */
function jodyn_form_text( $in, $key, $max = 160, $multiline = false ) {
	if ( ! isset( $in[ $key ] ) || ! is_scalar( $in[ $key ] ) ) {
		return '';
	}
	$value = $multiline ? sanitize_textarea_field( (string) $in[ $key ] ) : sanitize_text_field( (string) $in[ $key ] );
	return mb_substr( $value, 0, $max );
}

/**
 * Bots fill every field; people never see the honeypot one ("website").
 *
 * @param array $in The submitted fields.
 * @return bool
 */
function jodyn_form_is_bot( $in ) {
	return '' !== jodyn_form_text( $in, 'website' );
}

/**
 * Whether an address is a proxy in front of the site: on the host's own network (a private or
 * reserved address) or Cloudflare's.
 *
 * @param string $ip Address.
 * @return bool
 */
function jodyn_ip_is_proxy( $ip ) {
	return ! filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) || jodyn_ip_is_cloudflare( $ip );
}

/**
 * The visitor's IP address. Behind Cloudflare or a host's proxy every request seems to come from
 * the proxy, so the address it passes on is used instead. Forwarded headers can be typed by
 * anyone, so only the parts a proxy itself writes are believed:
 * - straight from Cloudflare: CF-Connecting-IP, which Cloudflare always overwrites;
 * - from the host's own proxy: X-Forwarded-For read from the right, where each proxy adds the
 *   address that connected to it, skipping proxies. Whatever a visitor typed sits further left.
 * A connection from anywhere else is the visitor, whatever its headers say.
 *
 * @return string
 */
function jodyn_client_ip() {
	$remote = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	if ( ! filter_var( $remote, FILTER_VALIDATE_IP ) ) {
		return '';
	}
	/**
	 * Whether to read the address a proxy passes on (true when the connection comes from one).
	 * Return false to always count by the connecting address.
	 *
	 * @param bool   $proxied Whether the request comes from a known proxy.
	 * @param string $remote  The connecting address.
	 */
	if ( ! apply_filters( 'jodyn_trusted_proxy', jodyn_ip_is_proxy( $remote ), $remote ) ) {
		return $remote;
	}

	if ( jodyn_ip_is_cloudflare( $remote ) && ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
		$ip = trim( sanitize_text_field( wp_unslash( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) );
		return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : $remote;
	}

	$chain = empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? array() : explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) );
	foreach ( array_reverse( $chain ) as $ip ) {
		$ip = trim( $ip );
		if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
			break; // A garbled entry: nothing to its left can be trusted.
		}
		if ( ! jodyn_ip_is_proxy( $ip ) ) {
			return $ip;
		}
	}
	return $remote;
}

/**
 * Whether an address is one of Cloudflare's (cloudflare.com/ips, October 2026).
 *
 * @param string $ip IPv4 or IPv6 address.
 * @return bool
 */
function jodyn_ip_is_cloudflare( $ip ) {
	$ranges = array(
		'173.245.48.0/20', '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22', '141.101.64.0/18', '108.162.192.0/18',
		'190.93.240.0/20', '188.114.96.0/20', '197.234.240.0/22', '198.41.128.0/17', '162.158.0.0/15', '104.16.0.0/13',
		'104.24.0.0/14', '172.64.0.0/13', '131.0.72.0/22',
		'2400:cb00::/32', '2606:4700::/32', '2803:f800::/32', '2405:b500::/32', '2405:8100::/32', '2a06:98c0::/29', '2c0f:f248::/32',
	);
	$bin = inet_pton( $ip );
	if ( false === $bin ) {
		return false;
	}
	foreach ( $ranges as $range ) {
		list( $net, $bits ) = explode( '/', $range );
		$net = inet_pton( $net );
		if ( strlen( $net ) !== strlen( $bin ) ) {
			continue;
		}
		$bytes = intdiv( (int) $bits, 8 );
		$rest  = (int) $bits % 8;
		if ( substr( $bin, 0, $bytes ) !== substr( $net, 0, $bytes ) ) {
			continue;
		}
		if ( ! $rest || ( ord( $bin[ $bytes ] ) >> ( 8 - $rest ) ) === ( ord( $net[ $bytes ] ) >> ( 8 - $rest ) ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Count a submission against the visitor's limit: 5 per form every 10 minutes.
 *
 * @param string $form    Short form name, e.g. msg, reg, dl.
 * @param string $message Shown when the limit is reached.
 * @return WP_Error|null An error once the limit is reached, else null (and the submission counts).
 */
function jodyn_form_rate_limit( $form, $message ) {
	$key   = 'jodyn_' . $form . '_' . md5( jodyn_client_ip() );
	$count = (int) get_transient( $key );
	if ( $count >= 5 ) {
		return new WP_Error( 'jodyn_rate_limited', $message, array( 'status' => 429 ) );
	}
	set_transient( $key, $count + 1, 10 * MINUTE_IN_SECONDS );
	return null;
}

/**
 * A form's email is sent while the visitor waits (the entry is saved first, so nothing is lost
 * if sending fails). Sending after the answer had gone was tried in 0.1.13, but the live host
 * stops the script once the visitor's connection closes, so those emails never left.
 * Instead, a slow mail server gets 20 seconds per step rather than PHPMailer's 5 minutes, so
 * nobody is left on "Sending…". Runs after WP Mail SMTP's own settings (priority 10).
 *
 * @param PHPMailer\PHPMailer\PHPMailer $mailer The mailer.
 */
function jodyn_mail_timeout( $mailer ) {
	$mailer->Timeout = 20; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- PHPMailer's property.
}
add_action( 'phpmailer_init', 'jodyn_mail_timeout', 20 );

/**
 * A submission's answers as a table on its admin screen. Empty answers are left out.
 *
 * @param array $rows  label => value.
 * @param int   $width Label column width in pixels.
 */
function jodyn_form_table( $rows, $width = 180 ) {
	echo '<table class="widefat striped"><tbody>';
	foreach ( $rows as $label => $value ) {
		if ( '' !== trim( (string) $value ) ) {
			printf( '<tr><th style="width:%1$dpx">%2$s</th><td>%3$s</td></tr>', (int) $width, esc_html( $label ), nl2br( esc_html( $value ) ) );
		}
	}
	echo '</tbody></table>';
}

/*
 * ---- Personal data: Tools → Export Personal Data and Erase Personal Data ----
 *
 * The three lists are found by the email address someone gave. Enquiries and calendar downloads
 * are deleted on request; registrations are kept for the accounting records (Privacy Policy, how
 * long we keep data) and reported as kept, so the team can delete them by hand if they decide to.
 */

/**
 * Every saved submission of a type whose details include an email address.
 *
 * @param string $type  jodyn_enquiry, jodyn_registration or jodyn_lead.
 * @param string $email Address to look for.
 * @return array<int, array> post ID => its details (label => value)
 */
function jodyn_form_find( $type, $email ) {
	$email = strtolower( trim( $email ) );
	$found = array();
	$ids   = get_posts(
		array(
			'post_type'   => $type,
			'post_status' => 'any',
			'numberposts' => -1,
			'fields'      => 'ids',
		)
	);
	foreach ( $ids as $id ) {
		if ( 'jodyn_lead' === $type ) {
			$details = array(
				__( 'Name', 'jodyn' )          => get_the_title( $id ),
				__( 'Email address', 'jodyn' ) => get_post_meta( $id, '_jodyn_email', true ),
				__( 'Organisation', 'jodyn' )  => get_post_meta( $id, '_jodyn_organisation', true ),
				__( 'Job title', 'jodyn' )     => get_post_meta( $id, '_jodyn_job_title', true ),
			);
			$emails  = array( $details[ __( 'Email address', 'jodyn' ) ] );
		} else {
			$meta = get_post_meta( $id, 'jodyn_enquiry' === $type ? '_jodyn_enquiry' : '_jodyn_registration', true );
			if ( ! is_array( $meta ) ) {
				continue;
			}
			$emails = array( $meta['email'] ?? '' );
			if ( 'jodyn_registration' === $type ) {
				$emails[] = $meta['invoice']['email'] ?? '';
				foreach ( (array) ( $meta['others'] ?? array() ) as $other ) {
					$emails[] = $other['email'] ?? '';
				}
			}
			$details = jodyn_form_flatten( $meta );
		}
		if ( in_array( $email, array_map( 'strtolower', array_map( 'trim', $emails ) ), true ) ) {
			$found[ $id ] = $details;
		}
	}
	return $found;
}

/**
 * Saved details as flat label => value pairs: "Invoice email", "Others 2 name". Empty and
 * yes/no values are left out.
 *
 * @param array  $meta   Saved details.
 * @param string $prefix Label so far.
 * @return array<string, string>
 */
function jodyn_form_flatten( $meta, $prefix = '' ) {
	$out = array();
	foreach ( $meta as $key => $value ) {
		$label = trim( $prefix . ' ' . ( is_int( $key ) ? $key + 2 : str_replace( '_', ' ', $key ) ) );
		if ( is_array( $value ) ) {
			$out += jodyn_form_flatten( $value, $label );
		} elseif ( is_scalar( $value ) && ! is_bool( $value ) && '' !== (string) $value ) {
			$out[ ucfirst( $label ) ] = (string) $value;
		}
	}
	return $out;
}

/**
 * The three lists, with how they show in an export.
 *
 * @return array post type => label
 */
function jodyn_form_types() {
	return array(
		'jodyn_enquiry'      => __( 'Contact form enquiries', 'jodyn' ),
		'jodyn_registration' => __( 'Programme registrations', 'jodyn' ),
		'jodyn_lead'         => __( 'Training calendar downloads', 'jodyn' ),
	);
}

add_filter(
	'wp_privacy_personal_data_exporters',
	static function ( $exporters ) {
		$exporters['jodyn-forms'] = array(
			'exporter_friendly_name' => __( 'JoDyn forms', 'jodyn' ),
			'callback'               => static function ( $email ) {
				$data = array();
				foreach ( jodyn_form_types() as $type => $label ) {
					foreach ( jodyn_form_find( $type, $email ) as $id => $details ) {
						$items = array(
							array(
								'name'  => __( 'Received', 'jodyn' ),
								'value' => get_the_date( 'j F Y, g:i a', $id ),
							),
						);
						foreach ( $details as $name => $value ) {
							$items[] = array(
								'name'  => $name,
								'value' => $value,
							);
						}
						$data[] = array(
							'group_id'    => $type,
							'group_label' => $label,
							'item_id'     => "$type-$id",
							'data'        => $items,
						);
					}
				}
				return array(
					'data' => $data,
					'done' => true,
				);
			},
		);
		return $exporters;
	}
);

add_filter(
	'wp_privacy_personal_data_erasers',
	static function ( $erasers ) {
		$erasers['jodyn-forms'] = array(
			'eraser_friendly_name' => __( 'JoDyn forms', 'jodyn' ),
			'callback'             => static function ( $email ) {
				$removed  = false;
				$retained = false;
				$messages = array();
				foreach ( array( 'jodyn_enquiry', 'jodyn_lead' ) as $type ) {
					foreach ( array_keys( jodyn_form_find( $type, $email ) ) as $id ) {
						$removed = (bool) wp_delete_post( $id, true ) || $removed;
					}
				}
				$kept = count( jodyn_form_find( 'jodyn_registration', $email ) );
				if ( $kept ) {
					$retained   = true;
					$messages[] = sprintf(
						/* translators: %d: number of registrations. */
						_n( '%d programme registration was kept for the accounting records. Delete it under Registrations if it is no longer needed.', '%d programme registrations were kept for the accounting records. Delete them under Registrations if they are no longer needed.', $kept, 'jodyn' ),
						$kept
					);
				}
				return array(
					'items_removed'  => $removed,
					'items_retained' => $retained,
					'messages'       => $messages,
					'done'           => true,
				);
			},
		);
		return $erasers;
	}
);
