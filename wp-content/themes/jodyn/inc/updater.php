<?php
/**
 * Theme updates from GitHub Releases.
 *
 * - Each release on github.com/Fagbayibo/jodyn-consulting-wp-theme is tagged vX.Y.Z (matching
 *   "Version:" in style.css) and carries jodyn.zip (npm run release builds and publishes both).
 * - The latest tag comes from the github.com/…/releases/latest redirect; if that fails, from the
 *   GitHub API (which allows only 60 requests an hour per IP, and shared hosts share IPs, so it
 *   is the fallback, not the first choice).
 * - A newer version shows under Dashboard → Updates (and Appearance → Themes) with the usual
 *   one-click update within about 10 minutes of a release: the answer is cached for 10 minutes,
 *   and it is added whenever WordPress reads its list of theme updates in the admin, not only
 *   when WordPress runs its own check twice a day.
 * - "Check again" on Dashboard → Updates skips the cache; an update clears it. If GitHub can't be
 *   reached, Dashboard → Updates says so (and it is retried after 10 minutes).
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const JODYN_UPDATE_REPO  = 'Fagbayibo/jodyn-consulting-wp-theme';
const JODYN_UPDATE_ASSET = 'jodyn.zip';
const JODYN_UPDATE_CACHE = 'jodyn_update_release';

/**
 * The latest release: array( version, tag, package ), or null when GitHub can't be reached
 * or the release has no zip yet.
 *
 * @param bool $force Skip the cache.
 * @return array|null
 */
function jodyn_update_latest_release( $force = false ) {
	$cached = get_site_transient( JODYN_UPDATE_CACHE );
	if ( ! $force && is_array( $cached ) ) {
		return empty( $cached['version'] ) ? null : $cached;
	}

	$base  = 'https://github.com/' . JODYN_UPDATE_REPO . '/releases';
	$args  = array(
		'redirection' => 0,
		'timeout'     => 8,
	);
	$tag   = '';
	$error = '';

	// 1. …/releases/latest answers with a redirect to …/releases/tag/vX.Y.Z.
	$response = wp_remote_head( $base . '/latest', $args );
	$location = wp_remote_retrieve_header( $response, 'location' );
	if ( is_string( $location ) && preg_match( '~/releases/tag/(v?\d+\.\d+\.\d+)$~', $location, $m ) ) {
		$tag = $m[1];
	} else {
		$error = is_wp_error( $response ) ? $response->get_error_message() : 'github.com answered ' . wp_remote_retrieve_response_code( $response );

		// 2. The GitHub API.
		$api = wp_remote_get(
			'https://api.github.com/repos/' . JODYN_UPDATE_REPO . '/releases/latest',
			array(
				'timeout' => 8,
				'headers' => array( 'Accept' => 'application/vnd.github+json' ),
			)
		);
		$data = json_decode( (string) wp_remote_retrieve_body( $api ), true );
		if ( is_array( $data ) && isset( $data['tag_name'] ) && preg_match( '~^v?\d+\.\d+\.\d+$~', $data['tag_name'] ) ) {
			$tag = $data['tag_name'];
		} elseif ( is_wp_error( $api ) ) {
			$error .= '; api.github.com: ' . $api->get_error_message();
		}
	}

	$release = null;
	if ( $tag ) {
		$package = $base . '/download/' . $tag . '/' . JODYN_UPDATE_ASSET;
		// The download redirects to GitHub's file storage when the zip exists (404 while it's missing).
		$code = (int) wp_remote_retrieve_response_code( wp_remote_head( $package, $args ) );
		if ( $code >= 200 && $code < 400 ) {
			$release = array(
				'version' => ltrim( $tag, 'v' ),
				'tag'     => $tag,
				'package' => $package,
			);
			$error   = '';
		} else {
			$error = $tag . ' has no ' . JODYN_UPDATE_ASSET . ' yet';
		}
	}

	set_site_transient( JODYN_UPDATE_CACHE, $release ? $release : array( 'error' => $error ), 10 * MINUTE_IN_SECONDS );
	return $release;
}

/**
 * Put this theme's update (or its "up to date" entry) into WordPress's list of theme updates.
 *
 * @param object $transient The update_themes site transient.
 * @param bool   $force     Skip the cache.
 * @return object
 */
function jodyn_update_apply( $transient, $force = false ) {
	if ( ! is_object( $transient ) ) {
		return $transient;
	}
	$slug    = get_template();
	$theme   = wp_get_theme( $slug );
	$release = jodyn_update_latest_release( $force );
	if ( ! $release ) {
		return $transient;
	}

	$item = array(
		'theme'        => $slug,
		'new_version'  => $release['version'],
		'url'          => 'https://github.com/' . JODYN_UPDATE_REPO . '/releases/tag/' . $release['tag'],
		'package'      => $release['package'],
		'requires'     => $theme->get( 'RequiresWP' ),
		'requires_php' => $theme->get( 'RequiresPHP' ),
	);

	if ( ! isset( $transient->response ) || ! is_array( $transient->response ) ) {
		$transient->response = array();
	}
	if ( ! isset( $transient->no_update ) || ! is_array( $transient->no_update ) ) {
		$transient->no_update = array();
	}
	if ( version_compare( $release['version'], $theme->get( 'Version' ), '>' ) ) {
		$transient->response[ $slug ] = $item;
		unset( $transient->no_update[ $slug ] );
	} else {
		$transient->no_update[ $slug ] = $item;
		unset( $transient->response[ $slug ] );
	}
	return $transient;
}

/**
 * When WordPress saves its theme update check (twice a day, and on Dashboard → Updates).
 *
 * @param object $transient The update_themes site transient.
 * @return object
 */
function jodyn_update_check( $transient ) {
	if ( ! is_object( $transient ) || empty( $transient->checked ) ) {
		return $transient;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only flag WordPress itself sets on "Check again".
	$force = is_admin() && ! empty( $_GET['force-check'] );
	return jodyn_update_apply( $transient, $force );
}
add_filter( 'pre_set_site_transient_update_themes', 'jodyn_update_check' );

/**
 * Whenever the admin (or the background updater) reads the list, so a new release shows within
 * 10 minutes instead of waiting for WordPress's next check. Visitors' pages never ask GitHub.
 *
 * @param object|false $transient The update_themes site transient.
 * @return object|false
 */
function jodyn_update_read( $transient ) {
	if ( ! is_admin() && ! wp_doing_cron() ) {
		return $transient;
	}
	return jodyn_update_apply( $transient );
}
add_filter( 'site_transient_update_themes', 'jodyn_update_read' );

/**
 * On Dashboard → Updates, say so when GitHub couldn't be reached, so a missing update isn't a
 * mystery.
 */
function jodyn_update_notice() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	$cached = get_site_transient( JODYN_UPDATE_CACHE );
	if ( ! $screen || 'update-core' !== $screen->id || ! current_user_can( 'update_themes' ) || ! is_array( $cached ) || empty( $cached['error'] ) ) {
		return;
	}
	printf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html(
			sprintf(
				/* translators: %s: the error. */
				__( 'JoDyn theme: could not check GitHub for a new version (%s). It tries again in 10 minutes. If this keeps showing, ask your host to allow outgoing connections to github.com and api.github.com, or update by uploading the new jodyn.zip under Appearance → Themes → Add New Theme.', 'jodyn' ),
				$cached['error']
			)
		)
	);
}
add_action( 'admin_notices', 'jodyn_update_notice' );

/**
 * Leave this theme out of the WordPress.org update check, so a theme there with the same
 * folder name is never offered in its place.
 *
 * @param array  $args HTTP request arguments.
 * @param string $url  Request URL.
 * @return array
 */
function jodyn_update_skip_wporg( $args, $url ) {
	if ( false === strpos( $url, '//api.wordpress.org/themes/update-check/' ) || empty( $args['body']['themes'] ) ) {
		return $args;
	}
	$themes = json_decode( $args['body']['themes'], true );
	if ( is_array( $themes ) && isset( $themes['themes'][ get_template() ] ) ) {
		unset( $themes['themes'][ get_template() ] );
		$args['body']['themes'] = wp_json_encode( $themes );
	}
	return $args;
}
add_filter( 'http_request_args', 'jodyn_update_skip_wporg', 10, 2 );

/**
 * After this theme is updated, forget the cached release so the next check starts fresh.
 *
 * @param WP_Upgrader $upgrader Unused.
 * @param array       $extra    What was updated.
 */
function jodyn_update_clear_cache( $upgrader, $extra ) {
	// One theme updated from Dashboard → Updates arrives as 'theme'; a bulk update as 'themes'.
	$themes = array_merge( (array) ( $extra['theme'] ?? array() ), (array) ( $extra['themes'] ?? array() ) );
	if ( isset( $extra['type'] ) && 'theme' === $extra['type'] && in_array( get_template(), $themes, true ) ) {
		delete_site_transient( JODYN_UPDATE_CACHE );
	}
}
add_action( 'upgrader_process_complete', 'jodyn_update_clear_cache', 10, 2 );
