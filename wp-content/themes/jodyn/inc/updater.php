<?php
/**
 * Theme updates from GitHub Releases.
 *
 * - Each release on github.com/Fagbayibo/jodyn-consulting-wp-theme is tagged vX.Y.Z (matching
 *   "Version:" in style.css) and carries jodyn.zip (npm run release builds and publishes both).
 * - When WordPress checks for theme updates, this finds the latest tag through the
 *   github.com/…/releases/latest redirect rather than the GitHub API, which allows only 60
 *   requests an hour per IP and shared hosts share IPs. A newer version shows under
 *   Dashboard → Updates with the usual one-click update.
 * - The answer is cached for an hour (a failed lookup is retried after 15 minutes).
 *   "Check again" on Dashboard → Updates skips the cache; an update clears it.
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

	$release = null;
	$base    = 'https://github.com/' . JODYN_UPDATE_REPO . '/releases';
	$args    = array(
		'redirection' => 0,
		'timeout'     => 10,
	);

	// …/releases/latest answers with a redirect to …/releases/tag/vX.Y.Z.
	$location = wp_remote_retrieve_header( wp_remote_head( $base . '/latest', $args ), 'location' );
	if ( is_string( $location ) && preg_match( '~/releases/tag/(v?(\d+\.\d+\.\d+))$~', $location, $m ) ) {
		$package = $base . '/download/' . $m[1] . '/' . JODYN_UPDATE_ASSET;
		// The download redirects to GitHub's file storage when the zip exists (404 while it's missing).
		$code = (int) wp_remote_retrieve_response_code( wp_remote_head( $package, $args ) );
		if ( $code >= 200 && $code < 400 ) {
			$release = array(
				'version' => $m[2],
				'tag'     => $m[1],
				'package' => $package,
			);
		}
	}

	set_site_transient( JODYN_UPDATE_CACHE, $release ? $release : array(), $release ? HOUR_IN_SECONDS : 15 * MINUTE_IN_SECONDS );
	return $release;
}

/**
 * Add this theme's update (or its "up to date" entry) whenever WordPress saves its theme update check.
 *
 * @param object $transient The update_themes site transient.
 * @return object
 */
function jodyn_update_check( $transient ) {
	if ( ! is_object( $transient ) || empty( $transient->checked ) ) {
		return $transient;
	}

	$slug  = get_template();
	$theme = wp_get_theme( $slug );
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only flag WordPress itself sets on "Check again".
	$force   = is_admin() && ! empty( $_GET['force-check'] );
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

	if ( version_compare( $release['version'], $theme->get( 'Version' ), '>' ) ) {
		$transient->response[ $slug ] = $item;
		unset( $transient->no_update[ $slug ] );
	} else {
		$transient->no_update[ $slug ] = $item;
		unset( $transient->response[ $slug ] );
	}
	return $transient;
}
add_filter( 'pre_set_site_transient_update_themes', 'jodyn_update_check' );

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
	if ( isset( $extra['type'] ) && 'theme' === $extra['type'] && in_array( get_template(), (array) ( $extra['themes'] ?? array() ), true ) ) {
		delete_site_transient( JODYN_UPDATE_CACHE );
	}
}
add_action( 'upgrader_process_complete', 'jodyn_update_clear_cache', 10, 2 );
