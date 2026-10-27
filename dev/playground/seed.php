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
// Fourteen carry a sample upcoming run (dates always in the future) for the home page and calendar.
// title => summary, start (days from today), length in days, format, venue, fee, seats, photo.
// Dates are relative so the calendar always has upcoming runs; set on every seed.
$runs = array(
	'Leadership Essentials for New Managers'          => array( 'Make a confident transition from team member to team leader. Learn how to lead former peers, delegate effectively, give feedback and motivate your team to deliver results.', 23, 2, 'in-person', 'Lagos', '₦250,000', 'open', 'leadership-essentials.jpg' ),
	'Problem Solving and Decision Making'             => array( '', 31, 1, 'online', 'Live via Zoom', '₦95,000', 'few', '' ),
	'Strategic Thinking and Planning'                 => array( 'Move from ideas to results. Learn practical tools for setting priorities, making sound decisions and turning strategy into action across your team.', 37, 2, 'online', 'Live via Zoom', '₦180,000', 'open', 'strategic-thinking.jpg' ),
	'Customer Service Excellence'                     => array( '', 40, 2, 'in-person', 'Ibadan', '₦150,000', 'full', '' ),
	'Effective Communication and Presentation Skills' => array( 'Communicate with clarity and influence. Build the skills to present ideas, manage difficult conversations and strengthen relationships with stakeholders.', 48, 1, 'hybrid', 'Lagos', '₦220,000', 'few', 'effective-communication.jpg' ),
	'Performance Management That Works'               => array( '', 88, 2, 'in-person', 'Abuja', '₦230,000', 'open', '' ),
	'Time Management and Personal Productivity'       => array( '', 95, 1, 'online', 'Live via Zoom', '₦85,000', 'open', '' ),
	'Project Management Fundamentals'                 => array( '', 109, 2, 'hybrid', 'Lagos', '₦240,000', 'open', '' ),
	'Emotional Intelligence for Leaders'              => array( '', 116, 2, 'in-person', 'Ibadan', '₦210,000', 'open', '' ),
	'HR for Non-HR Managers'                          => array( '', 130, 1, 'online', 'Live via Zoom', '₦120,000', 'open', '' ),
	'Sales and Negotiation Skills'                    => array( '', 137, 3, 'in-person', 'Lagos', '₦260,000', 'open', '' ),
	'Risk Management Essentials'                      => array( '', 151, 2, 'online', 'Live via Zoom', '₦160,000', 'open', '' ),
	'Coaching and Mentoring Skills for Managers'      => array( '', 165, 2, 'hybrid', 'Abuja', '₦230,000', 'open', '' ),
	'Business Writing and Report Writing'             => array( '', 179, 1, 'online', 'Live via Zoom', '₦90,000', 'open', '' ),
);
foreach ( $runs as $title => $run ) {
	$found = get_posts( array( 'post_type' => 'jodyn_programme', 'title' => $title, 'fields' => 'ids', 'numberposts' => 1 ) );
	if ( ! $found ) {
		continue;
	}
	$id    = $found[0];
	$start = strtotime( '+' . $run[1] . ' days' );
	if ( $run[0] ) {
		// Only an empty description gets the summary, so the example programme page keeps its overview.
		wp_update_post( '' === trim( get_post_field( 'post_content', $id ) ) ? array( 'ID' => $id, 'post_excerpt' => $run[0], 'post_content' => '<p>' . $run[0] . '</p>' ) : array( 'ID' => $id, 'post_excerpt' => $run[0] ) );
	}
	update_post_meta( $id, '_jodyn_start', gmdate( 'Y-m-d', $start ) );
	update_post_meta( $id, '_jodyn_end', $run[2] > 1 ? gmdate( 'Y-m-d', $start + ( $run[2] - 1 ) * DAY_IN_SECONDS ) : '' );
	update_post_meta( $id, '_jodyn_format', $run[3] );
	update_post_meta( $id, '_jodyn_venue', $run[4] );
	update_post_meta( $id, '_jodyn_fee', $run[5] );
	update_post_meta( $id, '_jodyn_seats', $run[6] );
	if ( $run[7] && ! has_post_thumbnail( $id ) ) {
		$photo = $import( '/wordpress/dev-media/programmes/' . $run[7] );
		if ( $photo ) {
			set_post_thumbnail( $id, $photo );
		}
	}
}
$pdf = $import( '/wordpress/dev-media/programmes/jodyn-training-calendar-sample.pdf' );
set_theme_mod( 'jodyn_calendar_pdf', $pdf );
echo "Seeded programmes, calendar PDF {$pdf}\n";

// Insights: the six launch articles and three later ideas as sample posts, with sample intros (the
// leadership one is the client's own, from the article outline) until the real articles are written.
// Categories by slug: site setup creates them (inc/insights.php).
wp_delete_post( 1, true ); // "Hello world!"
$articles = array(
	array( 'Why Leadership Training Is a Business Investment, Not a Cost', 'leadership', '-2 days', 'leadership-training.jpg', 'Many organisations cut training budgets first when times are hard. Yet the cost of poor leadership, through missed targets, disengaged staff and high turnover, is often far greater. This article explains why leadership training should be treated as an investment and how to measure its return.' ),
	array( '5 Signs Your Business Needs a Systems Audit', 'systems-audit', '-9 days', 'systems-audit-signs.jpg', 'A systems audit is not only for organisations in trouble. Delayed reports, repeated errors and work that depends on one person are early signs that your processes and controls need an independent review. Here are five to watch for.' ),
	array( 'A Tax Compliance Checklist for Growing Businesses', 'tax', '-16 days', 'tax-compliance-checklist.jpg', 'As a business grows, so do its tax obligations. Missed filings and poor records can lead to penalties that are easy to avoid. This checklist covers the essentials every growing business in Nigeria should have in place.' ),
	array( 'From Strategy to Execution: Why Good Plans Fail and How to Fix Them', 'strategy', '-23 days', 'strategy-execution.jpg', 'Most strategies fail in the doing, not the planning. This article shows how to turn a good plan into clear priorities, named owners and results you can track.' ),
	array( 'Building a Performance Management System That Actually Works', 'performance', '-30 days', 'performance-management.jpg', 'Annual reviews alone rarely improve performance. These are the building blocks of a performance management system your managers will actually use.' ),
	array( 'How to Conduct a Training Needs Analysis in 5 Steps', 'learning-development', '-37 days', 'training-needs-analysis.jpg', 'Training works best when it targets real gaps. A practical five-step method to find them before you spend your budget.' ),
	array( '7 Signs Your Organisation Needs a Capacity Building Programme', 'people-culture', '-44 days', 'capacity-building.jpg', 'When growth outpaces your people\'s skills, it shows. Seven signs it is time to invest in building capability across your teams.' ),
	array( 'In-Person vs Online Training: How to Choose the Right Format', 'learning-development', '-51 days', 'in-person-vs-online.jpg', 'Both formats work, for different goals. How to choose between classroom, online and hybrid training for your team.' ),
	array( 'Our 2027 Training Calendar Is Now Available', 'news-events', '-58 days', 'training-calendar-2027.jpg', 'Dates, formats and fees for next year\'s open programmes are out. Download the calendar and plan your team\'s learning for the year.' ),
);
foreach ( $articles as $a ) {
	$cat  = get_term_by( 'slug', $a[1], 'category' );
	$post = array(
		'post_title'    => $a[0],
		'post_status'   => 'publish',
		'post_author'   => 1,
		'post_excerpt'  => $a[4],
		'post_content'  => '<p>' . $a[4] . '</p>',
		'post_category' => $cat ? array( $cat->term_id ) : array(),
	);
	// The leadership article is a full sample, to show the article layout and the two box styles.
	if ( 'leadership' === $a[1] ) {
		$post['post_content'] = file_get_contents( __DIR__ . '/sample-article.html' );
	}
	$found = get_posts( array( 'post_type' => 'post', 'title' => $a[0], 'fields' => 'ids', 'numberposts' => 1 ) );
	if ( $found ) {
		wp_update_post( $post + array( 'ID' => $found[0] ) );
		continue;
	}
	$id    = wp_insert_post( $post + array( 'post_date' => gmdate( 'Y-m-d H:i:s', strtotime( $a[2] ) ) ) );
	$photo = $import( '/wordpress/dev-media/insights/' . $a[3] );
	if ( $photo ) {
		set_post_thumbnail( $id, $photo );
	}
}
// Sample categories from earlier seeds, once they are empty.
foreach ( array( 'training' ) as $slug ) {
	$old = get_term_by( 'slug', $slug, 'category' );
	if ( $old && ! $old->count ) {
		wp_delete_term( $old->term_id, 'category' );
	}
}
// The sample author's profile, for the article's author box.
wp_update_user( array( 'ID' => 1, 'display_name' => '[Author name]', 'description' => '[One-line bio: area of expertise and years of experience.]' ) );
update_user_meta( 1, 'jodyn_job_title', '[Job title], JoDyn Advisory and Consulting' );
echo "Seeded insights\n";

// Footer contact details and social links (placeholders until the client sends the real ones).
set_theme_mod( 'jodyn_contact_address', '[Office address]' );
set_theme_mod( 'jodyn_contact_phone', '[Phone number]' );
set_theme_mod( 'jodyn_contact_email', 'info@jodynconsulting.com' );
foreach ( array( 'linkedin' => 'https://www.linkedin.com/', 'instagram' => 'https://www.instagram.com/', 'facebook' => 'https://www.facebook.com/', 'x' => 'https://x.com/', 'youtube' => 'https://www.youtube.com/' ) as $network => $url ) {
	set_theme_mod( "jodyn_social_$network", $url );
}
