<?php
/**
 * DEV ONLY: seeds a local WordPress Playground with pages and the Primary menu.
 * Not part of the theme; never upload this to the live site.
 */
require_once '/wordpress/wp-load.php';

update_option( 'blogname', 'JoDyn Advisory & Consulting' );
update_option( 'blogdescription', 'Advisory that turns strategy into measurable growth' );

// Pages, front page, posts page, Primary menu and the programme catalogue come from the theme's
// own site setup (inc/setup.php), exactly as on a real install.
jodyn_site_setup();

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


// Training programmes: the catalogue comes from the theme setup above (training.md, section 3).
// Three carry a sample upcoming run (dates always in the future) for the home page and calendar.
$runs = array(
	'Leadership Essentials for New Managers'          => array( 'Make a confident transition into management. Learn how to lead former peers, delegate effectively, give feedback and motivate your team to deliver.', '+23 days', 'in-person', '₦250,000', 'leadership-essentials.jpg' ),
	'Strategic Thinking and Planning'                 => array( 'Move from ideas to results. Learn practical tools for setting priorities, making sound decisions and turning strategy into action across your team.', '+37 days', 'online', '₦180,000', 'strategic-thinking.jpg' ),
	'Effective Communication and Presentation Skills' => array( 'Communicate with clarity and influence. Build the skills to present ideas, manage difficult conversations and strengthen relationships with stakeholders.', '+48 days', 'hybrid', '₦220,000', 'effective-communication.jpg' ),
);
foreach ( $runs as $title => $run ) {
	$found = get_posts( array( 'post_type' => 'jodyn_programme', 'title' => $title, 'fields' => 'ids', 'numberposts' => 1 ) );
	if ( ! $found || get_post_meta( $found[0], '_jodyn_start', true ) ) {
		continue;
	}
	$id = $found[0];
	wp_update_post( array( 'ID' => $id, 'post_excerpt' => $run[0], 'post_content' => '<p>' . $run[0] . '</p>' ) );
	update_post_meta( $id, '_jodyn_start', gmdate( 'Y-m-d', strtotime( $run[1] ) ) );
	update_post_meta( $id, '_jodyn_format', $run[2] );
	update_post_meta( $id, '_jodyn_fee', $run[3] );
	$photo = $import( '/wordpress/dev-media/programmes/' . $run[4] );
	if ( $photo ) {
		set_post_thumbnail( $id, $photo );
	}
}
$pdf = $import( '/wordpress/dev-media/programmes/jodyn-training-calendar-sample.pdf' );
set_theme_mod( 'jodyn_calendar_pdf', $pdf );
echo "Seeded programmes, calendar PDF {$pdf}\n";

// Insights: the three launch articles as sample posts (titles only; the client writes the articles).
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
