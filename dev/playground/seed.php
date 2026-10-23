<?php
/**
 * DEV ONLY: seeds a local WordPress Playground with pages and the Primary menu.
 * Not part of the theme; never upload this to the live site.
 */
require_once '/wordpress/wp-load.php';

update_option( 'blogname', 'JoDyn Advisory & Consulting' );
update_option( 'blogdescription', 'Advisory that turns strategy into measurable growth' );
update_option( 'permalink_structure', '/%postname%/' );

$page_ids = array();
foreach ( array( 'Home', 'About us', 'Services', 'Training calendar', 'Insights', 'Careers', 'Contact us' ) as $title ) {
	$existing = get_page_by_path( sanitize_title( $title ) );
	$page_ids[ $title ] = $existing ? $existing->ID : wp_insert_post(
		array(
			'post_title'   => $title,
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_content' => '<p>Placeholder content for ' . esc_html( $title ) . '.</p>',
		)
	);
}
// Service pages live under Services, e.g. /services/advisory/.
foreach ( array( 'Advisory' ) as $title ) {
	if ( ! get_page_by_path( 'services/' . sanitize_title( $title ) ) ) {
		wp_insert_post(
			array(
				'post_title'   => $title,
				'post_name'    => sanitize_title( $title ),
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_parent'  => $page_ids['Services'],
				'post_content' => '<p>Placeholder content for ' . esc_html( $title ) . '.</p>',
			)
		);
	}
}
update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $page_ids['Home'] );

$menu = wp_get_nav_menu_object( 'Primary' );
if ( $menu ) {
	wp_delete_nav_menu( $menu->term_id );
}
$menu_id = wp_create_nav_menu( 'Primary' );

$add_page = function ( $title, $parent = 0 ) use ( $menu_id, $page_ids ) {
	return wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-title'     => $title,
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $page_ids[ $title ],
			'menu-item-type'      => 'post_type',
			'menu-item-parent-id' => $parent,
			'menu-item-status'    => 'publish',
		)
	);
};

$add_page( 'About us' );
$services = $add_page( 'Services' );
foreach (
	array(
		'Strategy & advisory'   => 'Direction, priorities and the plan to get there',
		'People & organisation' => 'Structures, talent and culture that perform',
		'Innovation consulting' => 'New products, models and ways of working',
		'Project management'    => 'Delivery that lands on time and on budget',
		'Research & insights'   => 'Evidence for the decisions that matter',
		'Training & capability' => 'Programmes from the 2026 training calendar',
	) as $title => $description
) {
	wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-title'       => $title,
			'menu-item-url'         => home_url( '/services/#' . sanitize_title( $title ) ),
			'menu-item-type'        => 'custom',
			'menu-item-description' => $description,
			'menu-item-parent-id'   => $services,
			'menu-item-status'      => 'publish',
		)
	);
}
$add_page( 'Training calendar' );
$add_page( 'Insights' );
$add_page( 'Careers' );

set_theme_mod( 'nav_menu_locations', array( 'primary' => $menu_id ) );

// Hero film: import into the Media Library and set it in the Customizer, as the client will.
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$import = function ( $path ) {
	if ( ! file_exists( $path ) ) {
		return 0;
	}
	$title    = pathinfo( $path, PATHINFO_FILENAME );
	$existing = get_posts(
		array(
			'post_type'   => 'attachment',
			'title'       => $title,
			'numberposts' => 1,
			'fields'      => 'ids',
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}
	$tmp = wp_tempnam( basename( $path ) );
	copy( $path, $tmp );
	$id = media_handle_sideload(
		array(
			'name'     => basename( $path ),
			'tmp_name' => $tmp,
		),
		0
	);
	return is_wp_error( $id ) ? 0 : (int) $id;
};

$film   = $import( '/wordpress/dev-media/hero/jodyn-hero-film-1080.mp4' );
$mobile = $import( '/wordpress/dev-media/hero/jodyn-hero-film-720.mp4' );
$poster = $import( '/wordpress/dev-media/hero/jodyn-hero-poster.jpg' );
set_theme_mod( 'jodyn_hero_video', $film );
set_theme_mod( 'jodyn_hero_video_mobile', $mobile );
if ( $poster ) {
	set_theme_mod( 'jodyn_hero_poster', wp_get_attachment_url( $poster ) );
}
echo "Hero film {$film}, mobile {$mobile}, poster {$poster}\n";
flush_rewrite_rules();

echo "Seeded menu {$menu_id}\n";

// Sample training programmes (dates always in the future) and a placeholder calendar PDF.
$programmes = array(
	array( 'Leadership Essentials for New Managers', 'Make a confident transition into management. Learn how to lead former peers, delegate effectively, give feedback and motivate your team to deliver.', '+23 days', 'in-person', '₦250,000', 'leadership-essentials.jpg' ),
	array( 'Strategic Thinking and Execution', 'Move from ideas to results. Learn practical tools for setting priorities, making sound decisions and turning strategy into action across your team.', '+37 days', 'online', '₦180,000', 'strategic-thinking.jpg' ),
	array( 'Effective Communication and Stakeholder Management', 'Communicate with clarity and influence. Build the skills to present ideas, manage difficult conversations and strengthen relationships with stakeholders.', '+48 days', 'hybrid', '₦220,000', 'effective-communication.jpg' ),
);
foreach ( $programmes as $p ) {
	if ( get_posts( array( 'post_type' => 'jodyn_programme', 'title' => $p[0], 'fields' => 'ids', 'numberposts' => 1 ) ) ) {
		continue;
	}
	$id = wp_insert_post(
		array(
			'post_type'    => 'jodyn_programme',
			'post_status'  => 'publish',
			'post_title'   => $p[0],
			'post_excerpt' => $p[1],
			'post_content' => '<p>' . $p[1] . '</p>',
		)
	);
	update_post_meta( $id, '_jodyn_start', gmdate( 'Y-m-d', strtotime( $p[2] ) ) );
	update_post_meta( $id, '_jodyn_format', $p[3] );
	update_post_meta( $id, '_jodyn_fee', $p[4] );
	$photo = $import( '/wordpress/dev-media/programmes/' . $p[5] );
	if ( $photo ) {
		set_post_thumbnail( $id, $photo );
	}
}
$pdf = $import( '/wordpress/dev-media/programmes/jodyn-training-calendar-sample.pdf' );
set_theme_mod( 'jodyn_calendar_pdf', $pdf );
echo "Seeded programmes, calendar PDF {$pdf}\n";

// Insights: the three launch articles as sample posts (titles only; the client writes the articles).
update_option( 'page_for_posts', $page_ids['Insights'] );
wp_delete_post( 1, true ); // "Hello world!"
$articles = array(
	array( 'Why Leadership Training Is a Business Investment, Not a Cost', 'Training', '-2 days', 'leadership-training.jpg' ),
	array( '5 Signs Your Business Needs a Systems Audit', 'Systems Audit', '-9 days', 'systems-audit-signs.jpg' ),
	array( 'A Tax Compliance Checklist for Growing Businesses', 'Tax', '-16 days', 'tax-compliance-checklist.jpg' ),
);
foreach ( $articles as $a ) {
	if ( get_posts( array( 'post_type' => 'post', 'title' => $a[0], 'fields' => 'ids', 'numberposts' => 1 ) ) ) {
		continue;
	}
	$cat = term_exists( $a[1], 'category' );
	$cat = $cat ? (int) $cat['term_id'] : (int) wp_insert_term( $a[1], 'category' )['term_id'];
	$id  = wp_insert_post(
		array(
			'post_title'    => $a[0],
			'post_status'   => 'publish',
			'post_date'     => gmdate( 'Y-m-d H:i:s', strtotime( $a[2] ) ),
			'post_content'  => '<p>Sample post for local testing. The client will write this article.</p>',
			'post_category' => array( $cat ),
		)
	);
	$photo = $import( '/wordpress/dev-media/insights/' . $a[3] );
	if ( $photo ) {
		set_post_thumbnail( $id, $photo );
	}
}
echo "Seeded insights\n";

// Footer contact details and social links (placeholders until the client sends the real ones).
set_theme_mod( 'jodyn_contact_address', '[Office address]' );
set_theme_mod( 'jodyn_contact_phone', '[Phone number]' );
set_theme_mod( 'jodyn_contact_email', 'info@jodynconsulting.com' );
foreach ( array( 'linkedin' => 'https://www.linkedin.com/', 'instagram' => 'https://www.instagram.com/', 'facebook' => 'https://www.facebook.com/', 'x' => 'https://x.com/', 'youtube' => 'https://www.youtube.com/' ) as $network => $url ) {
	set_theme_mod( "jodyn_social_$network", $url );
}
