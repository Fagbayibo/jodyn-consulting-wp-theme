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
require_once get_template_directory() . '/inc/forms.php';
require_once get_template_directory() . '/inc/emails.php';
require_once get_template_directory() . '/inc/calendar-download.php';
require_once get_template_directory() . '/inc/registration.php';
require_once get_template_directory() . '/inc/enquiries.php';
require_once get_template_directory() . '/inc/insights.php';
require_once get_template_directory() . '/inc/contact.php';
require_once get_template_directory() . '/inc/legal.php';
require_once get_template_directory() . '/inc/consent.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/faqs.php';
require_once get_template_directory() . '/inc/programme-page.php';
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

	// Each page's own styles (assets/css) and scripts (assets/js, run after the motion stack):
	// array( shown?, handle => css file, handle => js file ).
	$pages = array(
		array( is_page( 'about-us' ), array( 'jodyn-about' => 'about' ), array( 'jodyn-about' => 'about' ) ),
		array( is_page( 'advisory' ), array( 'jodyn-advisory' => 'advisory' ), array( 'jodyn-advisory' => 'advisory' ) ),
		array( is_page( 'systems-audit' ), array( 'jodyn-systems-audit' => 'systems-audit' ), array( 'jodyn-systems-audit' => 'systems-audit' ) ),
		array( is_page( 'tax-consultancy' ), array( 'jodyn-tax-consultancy' => 'tax-consultancy' ), array( 'jodyn-tax-consultancy' => 'tax-consultancy' ) ),
		array( is_singular( 'jodyn_programme' ), array( 'jodyn-programme' => 'programme' ), array( 'jodyn-programme' => 'programme' ) ),
		array( is_page( 'faqs' ), array( 'jodyn-faqs' => 'faqs' ), array( 'jodyn-faqs' => 'faqs' ) ),
		array( (bool) jodyn_legal_current(), array( 'jodyn-legal' => 'legal' ), array( 'jodyn-legal' => 'legal' ) ),
		array( is_page( 'programmes' ), array( 'jodyn-training' => 'training' ), array( 'jodyn-training' => 'training' ) ),
		array( is_page( 'calendar' ), array( 'jodyn-calendar' => 'calendar' ), array( 'jodyn-calendar' => 'calendar' ) ),
		array( is_page( 'contact-us' ), array( 'jodyn-contact' => 'contact' ), array( 'jodyn-contact' => 'contact' ) ),
		array( is_home() || is_category() || is_singular( 'post' ), array( 'jodyn-insights' => 'insights' ), array() ),
		array( is_singular( 'post' ), array(), array( 'jodyn-article' => 'article' ) ),
		array( is_page( 'register' ), array( 'jodyn-register' => 'register' ), array( 'jodyn-register' => 'register' ) ),
		array(
			is_front_page(),
			array(
				'jodyn-front'           => 'front-page',
				'jodyn-home-who'        => 'home-who',
				'jodyn-home-help'       => 'home-help',
				'jodyn-home-programmes' => 'home-programmes',
				'jodyn-home-work'       => 'home-work',
				'jodyn-home-why'        => 'home-why',
				'jodyn-home-serve'      => 'home-serve',
				'jodyn-home-outcomes'   => 'home-outcomes',
				'jodyn-home-insights'   => 'home-insights',
			),
			array(
				'jodyn-who'      => 'who-we-are',
				'jodyn-help'     => 'how-we-help',
				'jodyn-work'     => 'how-we-work',
				'jodyn-why'      => 'why-jodyn',
				'jodyn-outcomes' => 'outcomes',
			),
		),
	);
	foreach ( $pages as list( $shown, $styles, $scripts ) ) {
		if ( ! $shown ) {
			continue;
		}
		foreach ( $styles as $handle => $file ) {
			wp_enqueue_style( $handle, "$uri/assets/css/$file.css", array( 'jodyn-base' ), jodyn_asset_version( "assets/css/$file.css" ) );
		}
		foreach ( $scripts as $handle => $file ) {
			jodyn_enqueue_script( $handle, "$file.js", array( 'jodyn-motion' ) );
		}
	}

	// The home hero plays its film without the motion stack.
	if ( is_front_page() ) {
		jodyn_enqueue_script( 'jodyn-hero', 'hero.js' );
	}

	// Pages with the "Download Training Calendar" button.
	if ( is_front_page() || is_page( 'programmes' ) || is_page( 'calendar' ) ) {
		jodyn_enqueue_calendar_download();
	}

	if ( is_page( 'contact-us' ) ) {
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

	if ( is_singular( 'post' ) ) {
		wp_localize_script(
			'jodyn-article',
			'jodynArticle',
			array(
				'copied' => __( 'Link copied', 'jodyn' ),
				'failed' => __( 'Could not copy the link', 'jodyn' ),
			)
		);
	}

	if ( is_page( 'register' ) ) {
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
 * Google Sans Flex (SIL OFL, assets/fonts/OFL.txt) is served from the theme, so no visitor's
 * address goes to Google before they have chosen in the cookie banner. theme.json declares the
 * two files (Latin, and Latin Extended for ₦); the Latin one is on every page, so the browser
 * starts fetching it straight away.
 */
function jodyn_preload_font() {
	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( get_template_directory_uri() . '/assets/fonts/google-sans-flex-latin.woff2' )
	);
}
add_action( 'wp_head', 'jodyn_preload_font', 1 );

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
