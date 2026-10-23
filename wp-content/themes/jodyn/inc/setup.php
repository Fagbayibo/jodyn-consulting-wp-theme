<?php
/**
 * Site setup: makes a fresh install match the site map, so every header and footer link works.
 *
 * Runs when the theme is activated, and once more after an update that raises
 * JODYN_SETUP_VERSION (on the next admin page load). It only adds what is missing; it never
 * edits or deletes existing content.
 * - Pretty permalinks (/%postname%/) if the site still uses plain ?p=123 links: every link in
 *   the theme (/about-us/, /training/programmes/) depends on them.
 * - The pages in jodyn_site_pages() that don't exist yet. The theme's page-<slug>.php templates
 *   pick them up by slug, so they need no content.
 * - Home as the front page and Insights as the posts page, if none is chosen.
 * - A "Primary" menu built from jodyn_site_map(), if no menu is assigned to the header.
 * - The training programme catalogue (titles and areas, no dates) if there are no programmes.
 * - The home hero film, fetched once from GitHub if no video is set (jodyn_maybe_import_hero_film).
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const JODYN_SETUP_VERSION = 1;

/**
 * The header menu: top-level items, and the dropdowns under Services and Training.
 * 'page' is the page path; a parent without one opens its dropdown only. Descriptions are
 * condensed from the approved home page copy (content/pages/home.md).
 *
 * @return array
 */
function jodyn_site_map() {
	return array(
		array(
			'title' => __( 'About us', 'jodyn' ),
			'page'  => 'about-us',
		),
		array(
			'title'    => __( 'Services', 'jodyn' ),
			'children' => array(
				array(
					'title' => __( 'Advisory', 'jodyn' ),
					'page'  => 'services/advisory',
					'desc'  => __( 'Expert guidance to make better decisions and perform at your best', 'jodyn' ),
				),
				array(
					'title' => __( 'Systems Audit', 'jodyn' ),
					'page'  => 'services/systems-audit',
					'desc'  => __( 'Independent reviews of your systems, processes and controls', 'jodyn' ),
				),
				array(
					'title' => __( 'Tax Consultancy', 'jodyn' ),
					'page'  => 'services/tax-consultancy',
					'desc'  => __( 'Practical tax advice and compliance support', 'jodyn' ),
				),
			),
		),
		array(
			'title'    => __( 'Training', 'jodyn' ),
			'children' => array(
				array(
					'title' => __( 'Training programmes', 'jodyn' ),
					'page'  => 'training/programmes',
					'desc'  => __( 'Practical learning in person, online or hybrid', 'jodyn' ),
				),
				array(
					'title' => __( 'Training calendar', 'jodyn' ),
					'page'  => 'training/calendar',
					'desc'  => __( 'Secure your seat on an upcoming open programme', 'jodyn' ),
				),
			),
		),
		array(
			'title' => __( 'Insights', 'jodyn' ),
			'page'  => 'insights',
		),
		array(
			'title' => __( 'Careers', 'jodyn' ),
			'page'  => 'careers',
		),
	);
}

/**
 * Every page the theme links to, by path (parents before their children). The two policy
 * pages start empty for the client's wording; WordPress's own Privacy Policy draft is kept if it
 * already exists.
 *
 * @return array path => title
 */
function jodyn_site_pages() {
	return array(
		'home'                     => __( 'Home', 'jodyn' ),
		'about-us'                 => __( 'About us', 'jodyn' ),
		'services'                 => __( 'Services', 'jodyn' ),
		'services/advisory'        => __( 'Advisory', 'jodyn' ),
		'services/systems-audit'   => __( 'Systems Audit', 'jodyn' ),
		'services/tax-consultancy' => __( 'Tax Consultancy', 'jodyn' ),
		'training'                 => __( 'Training', 'jodyn' ),
		'training/programmes'      => __( 'Programmes', 'jodyn' ),
		'training/calendar'        => __( 'Training calendar', 'jodyn' ),
		'insights'                 => __( 'Insights', 'jodyn' ),
		'careers'                  => __( 'Careers', 'jodyn' ),
		'contact-us'               => __( 'Contact us', 'jodyn' ),
		'privacy-policy'           => __( 'Privacy Policy', 'jodyn' ),
		'cookie-policy'            => __( 'Cookie Policy', 'jodyn' ),
	);
}

/**
 * The client's programme titles by area (content/pages/training.md, section 3). Dates, fees
 * and formats are added per run in Programmes.
 *
 * @return array area key => titles
 */
function jodyn_programme_catalogue() {
	return array(
		'leadership'   => array( 'Leadership Essentials for New Managers', 'Leading High-Performance Teams', 'Strategic Leadership for Senior Executives', 'Coaching and Mentoring Skills for Managers', 'Emotional Intelligence for Leaders', 'Leading Through Change' ),
		'strategy'     => array( 'Strategic Thinking and Planning', 'From Strategy to Execution: Delivering Results', 'Setting Goals, KPIs and OKRs', 'Problem Solving and Decision Making', 'Business Planning for Growth' ),
		'hr'           => array( 'Performance Management That Works', 'Talent Management and Succession Planning', 'Building a High-Performance Culture', 'Employee Engagement and Retention Strategies', 'HR for Non-HR Managers', 'Training Needs Analysis and Learning Evaluation' ),
		'professional' => array( 'Effective Communication and Presentation Skills', 'Time Management and Personal Productivity', 'Business Writing and Report Writing', 'Teamwork and Collaboration', 'Stakeholder Management and Influencing', 'Workplace Ethics and Professionalism' ),
		'customer'     => array( 'Customer Service Excellence', 'Managing Difficult Customers and Complaints', 'Sales and Negotiation Skills', 'Building Customer-Centric Teams', 'Business Development and Relationship Management' ),
		'projects'     => array( 'Project Management Fundamentals', 'Process Improvement and Operational Efficiency', 'Risk Management Essentials', 'Managing Change in Organisations', 'Monitoring and Evaluation for Programmes and Projects' ),
	);
}

/**
 * Add whatever the site map needs and the site doesn't have yet.
 */
function jodyn_site_setup() {
	global $wp_rewrite;

	if ( ! get_option( 'permalink_structure' ) ) {
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
	}

	$ids = array();
	foreach ( jodyn_site_pages() as $path => $title ) {
		$page = get_page_by_path( $path );
		if ( $page ) {
			$ids[ $path ] = $page->ID;
			continue;
		}
		$parent       = dirname( $path );
		$ids[ $path ] = wp_insert_post(
			array(
				'post_title'  => $title,
				'post_name'   => basename( $path ),
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_parent' => '.' === $parent ? 0 : (int) ( $ids[ $parent ] ?? 0 ),
			)
		);
	}

	if ( 'page' !== get_option( 'show_on_front' ) || ! get_option( 'page_on_front' ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['home'] );
	}
	if ( ! get_option( 'page_for_posts' ) ) {
		update_option( 'page_for_posts', $ids['insights'] );
	}

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( empty( $locations['primary'] ) || ! wp_get_nav_menu_object( $locations['primary'] ) ) {
		$menu    = wp_get_nav_menu_object( 'Primary' );
		$menu_id = $menu ? $menu->term_id : wp_create_nav_menu( 'Primary' );
		if ( ! is_wp_error( $menu_id ) ) {
			if ( ! wp_get_nav_menu_items( $menu_id ) ) {
				jodyn_build_menu( $menu_id, jodyn_site_map(), $ids );
			}
			$locations['primary'] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}

	if ( post_type_exists( 'jodyn_programme' ) && ! get_posts( array( 'post_type' => 'jodyn_programme', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) ) ) {
		$order = 0;
		foreach ( jodyn_programme_catalogue() as $area => $titles ) {
			foreach ( $titles as $title ) {
				$id = wp_insert_post(
					array(
						'post_type'   => 'jodyn_programme',
						'post_status' => 'publish',
						'post_title'  => $title,
						'menu_order'  => ++$order,
					)
				);
				if ( $id && ! is_wp_error( $id ) ) {
					update_post_meta( $id, '_jodyn_area', $area );
				}
			}
		}
	}

	flush_rewrite_rules();
	update_option( 'jodyn_setup_version', JODYN_SETUP_VERSION );
}
add_action( 'after_switch_theme', 'jodyn_site_setup' );

/**
 * Run the setup after an update that changed it.
 */
function jodyn_maybe_site_setup() {
	if ( (int) get_option( 'jodyn_setup_version' ) < JODYN_SETUP_VERSION && current_user_can( 'manage_options' ) ) {
		jodyn_site_setup();
	}
}
add_action( 'admin_init', 'jodyn_maybe_site_setup' );

/**
 * Add site map items to a menu: pages as page items (they follow slug changes and get the
 * current-page highlight), parents without a page as "#" links.
 *
 * @param int   $menu_id Menu term ID.
 * @param array $items   Part of jodyn_site_map().
 * @param array $ids     Page path => ID.
 * @param int   $parent  Parent menu item ID.
 */
function jodyn_build_menu( $menu_id, $items, $ids, $parent = 0 ) {
	foreach ( $items as $item ) {
		$args = array(
			'menu-item-title'       => $item['title'],
			'menu-item-description' => $item['desc'] ?? '',
			'menu-item-parent-id'   => $parent,
			'menu-item-status'      => 'publish',
		);
		if ( ! empty( $item['page'] ) && ! empty( $ids[ $item['page'] ] ) ) {
			$args['menu-item-type']      = 'post_type';
			$args['menu-item-object']    = 'page';
			$args['menu-item-object-id'] = $ids[ $item['page'] ];
		} else {
			$args['menu-item-type'] = 'custom';
			$args['menu-item-url']  = '#';
		}
		$item_id = wp_update_nav_menu_item( $menu_id, 0, $args );
		if ( ! empty( $item['children'] ) && $item_id && ! is_wp_error( $item_id ) ) {
			jodyn_build_menu( $menu_id, $item['children'], $ids, $item_id );
		}
	}
}

/**
 * The home hero film, fetched once into the Media Library if no video is set yet. It isn't
 * shipped in the theme zip (8.6 MB would break many hosts' upload limits); the files live in
 * the theme's GitHub repo, pinned to the commit below. Runs on an admin page load, not for
 * visitors; a failed download is retried after an hour. Removing the video in
 * Customize → Home hero afterwards is respected: it is never fetched again.
 */
const JODYN_HERO_FILM_BASE = 'https://raw.githubusercontent.com/Fagbayibo/jodyn-consulting-wp-theme/674e65545f2ad9094803d27b4e601c984d9d02cf/media/hero/';

function jodyn_maybe_import_hero_film() {
	if ( get_theme_mod( 'jodyn_hero_video' ) || get_option( 'jodyn_hero_film_imported' ) || get_transient( 'jodyn_hero_film_retry' ) || ! current_user_can( 'upload_files' ) ) {
		return;
	}
	set_transient( 'jodyn_hero_film_retry', 1, HOUR_IN_SECONDS );

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$ids = array();
	foreach ( array( 'jodyn_hero_video' => 'jodyn-hero-film-1080.mp4', 'jodyn_hero_video_mobile' => 'jodyn-hero-film-720.mp4' ) as $mod => $file ) {
		$tmp = download_url( JODYN_HERO_FILM_BASE . $file, 120 );
		if ( is_wp_error( $tmp ) ) {
			return;
		}
		$id = media_handle_sideload(
			array(
				'name'     => $file,
				'tmp_name' => $tmp,
			),
			0,
			__( 'JoDyn home hero film', 'jodyn' )
		);
		if ( is_wp_error( $id ) ) {
			wp_delete_file( $tmp );
			return;
		}
		$ids[ $mod ] = (int) $id;
	}

	foreach ( $ids as $mod => $id ) {
		set_theme_mod( $mod, $id );
	}
	update_option( 'jodyn_hero_film_imported', 1, false );
	delete_transient( 'jodyn_hero_film_retry' );
}
add_action( 'admin_init', 'jodyn_maybe_import_hero_film', 20 );
