<?php
/**
 * JoDyn theme setup.
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/class-jodyn-nav-walker.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/programmes.php';
require_once get_template_directory() . '/inc/calendar-download.php';
require_once get_template_directory() . '/inc/registration.php';
require_once get_template_directory() . '/inc/enquiries.php';
require_once get_template_directory() . '/inc/contact.php';
require_once get_template_directory() . '/inc/updater.php';
require_once get_template_directory() . '/inc/setup.php';

/**
 * Theme supports and menu locations.
 */
function jodyn_setup() {
	load_theme_textdomain( 'jodyn', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary navigation', 'jodyn' ),
			'footer'  => __( 'Footer navigation', 'jodyn' ),
		)
	);
}
add_action( 'after_setup_theme', 'jodyn_setup' );

/**
 * Version a theme asset by its modification time so browsers pick up edits.
 *
 * @param string $relative Path inside the theme, e.g. 'assets/css/header.css'.
 */
function jodyn_asset_version( $relative ) {
	$file = get_template_directory() . '/' . $relative;
	return file_exists( $file ) ? (string) filemtime( $file ) : wp_get_theme()->get( 'Version' );
}

/**
 * Styles and scripts.
 */
function jodyn_enqueue_assets() {
	$uri = get_template_directory_uri();

	// Google Sans Flex (SIL OFL). Swap for self-hosted files in assets/fonts before launch.
	wp_enqueue_style( 'jodyn-font', 'https://fonts.googleapis.com/css2?family=Google+Sans+Flex:wght@300..800&display=swap', array(), null );

	foreach ( array( 'tokens', 'base', 'header', 'footer' ) as $sheet ) {
		$deps = 'tokens' === $sheet ? array() : array( 'jodyn-tokens' );
		wp_enqueue_style( "jodyn-$sheet", "$uri/assets/css/$sheet.css", $deps, jodyn_asset_version( "assets/css/$sheet.css" ) );
	}

	wp_enqueue_script(
		'jodyn-header',
		"$uri/assets/js/header.js",
		array(),
		jodyn_asset_version( 'assets/js/header.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	wp_localize_script(
		'jodyn-header',
		'jodynHeader',
		array(
			'openLabel'  => __( 'Open menu', 'jodyn' ),
			'closeLabel' => __( 'Close menu', 'jodyn' ),
		)
	);

	// Every page ends with the footer: its card and columns animate in, over the particle field.
	jodyn_register_motion();
	jodyn_enqueue_script( 'jodyn-particles', 'particles.js' );
	wp_enqueue_script( 'jodyn-motion' );

	if ( is_page( 'about-us' ) ) {
		wp_enqueue_style( 'jodyn-about', "$uri/assets/css/about.css", array( 'jodyn-base' ), jodyn_asset_version( 'assets/css/about.css' ) );
		jodyn_enqueue_script( 'jodyn-about', 'about.js', array( 'jodyn-motion' ) );
	}

	if ( is_page( 'advisory' ) ) {
		wp_enqueue_style( 'jodyn-advisory', "$uri/assets/css/advisory.css", array( 'jodyn-base' ), jodyn_asset_version( 'assets/css/advisory.css' ) );
		jodyn_enqueue_script( 'jodyn-advisory', 'advisory.js', array( 'jodyn-motion' ) );
	}

	if ( is_page( 'programmes' ) ) {
		wp_enqueue_style( 'jodyn-training', "$uri/assets/css/training.css", array( 'jodyn-base' ), jodyn_asset_version( 'assets/css/training.css' ) );
		jodyn_enqueue_script( 'jodyn-training', 'training.js', array( 'jodyn-motion' ) );
		jodyn_enqueue_calendar_download();
	}

	if ( is_page( 'calendar' ) ) {
		wp_enqueue_style( 'jodyn-calendar', "$uri/assets/css/calendar.css", array( 'jodyn-base' ), jodyn_asset_version( 'assets/css/calendar.css' ) );
		jodyn_enqueue_script( 'jodyn-calendar', 'calendar.js', array( 'jodyn-motion' ) );
		jodyn_enqueue_calendar_download();
	}

	if ( is_page( 'contact-us' ) ) {
		wp_enqueue_style( 'jodyn-contact', "$uri/assets/css/contact.css", array( 'jodyn-base' ), jodyn_asset_version( 'assets/css/contact.css' ) );
		jodyn_enqueue_script( 'jodyn-contact', 'contact.js', array( 'jodyn-motion' ) );
		wp_localize_script(
			'jodyn-contact',
			'jodynContact',
			array(
				'endpoint' => esc_url_raw( rest_url( 'jodyn/v1/contact' ) ),
				'i18n'     => array(
					'invalid' => __( 'Please complete the fields marked *.', 'jodyn' ),
					'sending' => __( 'Sending your message…', 'jodyn' ),
					'error'   => __( 'Something went wrong. Please try again.', 'jodyn' ),
				),
			)
		);
	}

	if ( is_page( 'register' ) ) {
		wp_enqueue_style( 'jodyn-register', "$uri/assets/css/register.css", array( 'jodyn-base' ), jodyn_asset_version( 'assets/css/register.css' ) );
		jodyn_enqueue_script( 'jodyn-register', 'register.js', array( 'jodyn-motion' ) );
		wp_localize_script(
			'jodyn-register',
			'jodynRegister',
			array(
				'endpoint' => esc_url_raw( rest_url( 'jodyn/v1/register' ) ),
				'i18n'     => array(
					'invalid' => __( 'Please complete the fields marked *.', 'jodyn' ),
					'sending' => __( 'Sending your registration…', 'jodyn' ),
					'error'   => __( 'Something went wrong. Please try again.', 'jodyn' ),
				),
			)
		);
	}

	if ( is_front_page() ) {
		wp_enqueue_style( 'jodyn-front', "$uri/assets/css/front-page.css", array( 'jodyn-base' ), jodyn_asset_version( 'assets/css/front-page.css' ) );
		wp_enqueue_style( 'jodyn-home-who', "$uri/assets/css/home-who.css", array( 'jodyn-base' ), jodyn_asset_version( 'assets/css/home-who.css' ) );
		wp_enqueue_style( 'jodyn-home-help', "$uri/assets/css/home-help.css", array( 'jodyn-base' ), jodyn_asset_version( 'assets/css/home-help.css' ) );
		wp_enqueue_style( 'jodyn-home-programmes', "$uri/assets/css/home-programmes.css", array( 'jodyn-base' ), jodyn_asset_version( 'assets/css/home-programmes.css' ) );
		wp_enqueue_style( 'jodyn-home-work', "$uri/assets/css/home-work.css", array( 'jodyn-base' ), jodyn_asset_version( 'assets/css/home-work.css' ) );
		wp_enqueue_style( 'jodyn-home-why', "$uri/assets/css/home-why.css", array( 'jodyn-base' ), jodyn_asset_version( 'assets/css/home-why.css' ) );
		wp_enqueue_style( 'jodyn-home-serve', "$uri/assets/css/home-serve.css", array( 'jodyn-base' ), jodyn_asset_version( 'assets/css/home-serve.css' ) );
		wp_enqueue_style( 'jodyn-home-outcomes', "$uri/assets/css/home-outcomes.css", array( 'jodyn-base' ), jodyn_asset_version( 'assets/css/home-outcomes.css' ) );
		wp_enqueue_style( 'jodyn-home-insights', "$uri/assets/css/home-insights.css", array( 'jodyn-base' ), jodyn_asset_version( 'assets/css/home-insights.css' ) );
		jodyn_enqueue_script( 'jodyn-hero', 'hero.js' );
		jodyn_enqueue_script( 'jodyn-who', 'who-we-are.js', array( 'jodyn-motion' ) );
		jodyn_enqueue_script( 'jodyn-help', 'how-we-help.js', array( 'jodyn-motion' ) );
		jodyn_enqueue_script( 'jodyn-work', 'how-we-work.js', array( 'jodyn-motion' ) );
		jodyn_enqueue_script( 'jodyn-why', 'why-jodyn.js', array( 'jodyn-motion' ) );
		jodyn_enqueue_script( 'jodyn-outcomes', 'outcomes.js', array( 'jodyn-motion' ) );
		jodyn_enqueue_calendar_download();
	}
}

/**
 * The training calendar download dialog (template-parts/calendar-download.php): its styles,
 * script and endpoint. Called on every page that shows the "Download Training Calendar" button.
 */
function jodyn_enqueue_calendar_download() {
	$uri = get_template_directory_uri();
	wp_enqueue_style( 'jodyn-download', "$uri/assets/css/calendar-download.css", array( 'jodyn-base' ), jodyn_asset_version( 'assets/css/calendar-download.css' ) );
	jodyn_enqueue_script( 'jodyn-download', 'calendar-download.js' );
	wp_localize_script(
		'jodyn-download',
		'jodynDownload',
		array(
			'endpoint' => esc_url_raw( rest_url( 'jodyn/v1/calendar-download' ) ),
			'i18n'     => array(
				'invalid' => __( 'Please enter your name and a valid email address.', 'jodyn' ),
				'sending' => __( 'Preparing your download…', 'jodyn' ),
				'success' => __( 'Your download is starting.', 'jodyn' ),
				'error'   => __( 'Something went wrong. Please try again.', 'jodyn' ),
			),
		)
	);
}

/**
 * Enqueue a theme script from assets/js, deferred in the footer.
 *
 * @param string   $handle Script handle.
 * @param string   $file   File name inside assets/js.
 * @param string[] $deps   Dependencies.
 */
function jodyn_enqueue_script( $handle, $file, $deps = array() ) {
	wp_enqueue_script(
		$handle,
		get_template_directory_uri() . "/assets/js/$file",
		$deps,
		jodyn_asset_version( "assets/js/$file" ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}

/**
 * Register the shared motion stack (bundled GSAP, ScrollTrigger, SplitText, Lenis, and
 * assets/js/motion.js). Section scripts list 'jodyn-motion' as a dependency to pull it in.
 * See assets/vendor/README.md for versions and licences.
 */
function jodyn_register_motion() {
	$vendor = get_template_directory_uri() . '/assets/vendor/';
	$args   = array(
		'in_footer' => true,
		'strategy'  => 'defer',
	);

	wp_register_script( 'gsap', $vendor . 'gsap.min.js', array(), '3.13.0', $args );
	wp_register_script( 'gsap-scrolltrigger', $vendor . 'ScrollTrigger.min.js', array( 'gsap' ), '3.13.0', $args );
	wp_register_script( 'gsap-splittext', $vendor . 'SplitText.min.js', array( 'gsap' ), '3.13.0', $args );
	wp_register_script( 'lenis', $vendor . 'lenis.min.js', array(), '1.1.13', $args );
	wp_register_script(
		'jodyn-motion',
		get_template_directory_uri() . '/assets/js/motion.js',
		array( 'gsap', 'gsap-scrolltrigger', 'gsap-splittext', 'lenis' ),
		jodyn_asset_version( 'assets/js/motion.js' ),
		$args
	);
}
add_action( 'wp_enqueue_scripts', 'jodyn_enqueue_assets' );

/**
 * Connect early to Google Fonts.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type.
 */
function jodyn_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'jodyn_resource_hints', 10, 2 );

/**
 * Mark pages whose header floats over a full-screen hero.
 *
 * @param string[] $classes Body classes.
 */
function jodyn_body_classes( $classes ) {
	$classes[] = jodyn_header_is_overlay() ? 'has-overlay-header' : 'has-solid-header';
	return $classes;
}
add_filter( 'body_class', 'jodyn_body_classes' );
