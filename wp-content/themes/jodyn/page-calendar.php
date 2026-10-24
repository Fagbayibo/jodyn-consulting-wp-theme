<?php
/**
 * Training calendar page (slug: calendar, under Training: /training/calendar/).
 *
 * In page order:
 * Hero (photo under the header, ribbon of upcoming dates): template-parts/calendar/hero.php.
 * Filters and programme list (month tabs, rows, page numbers): template-parts/calendar/list.php.
 * Download the calendar (PDF preview and the download form): template-parts/calendar/download.php.
 * Training a Team? (group discount, in-house delivery): template-parts/calendar/team.php.
 * Copy: content/pages/training-calendar.md (approved).
 *
 * @package JoDyn
 */

// The hero photo runs up under the header, as on the home page.
add_filter( 'jodyn_header_is_overlay', '__return_true' );

// The footer's lime card carries this page's own closing call to action (training-calendar.md, footer card).
add_filter(
	'jodyn_footer_cta',
	static function ( $cta ) {
		$cta['title']   = 'Can\'t Find What You <em>Need?</em>';
		$cta['text']    = 'Our team can recommend the right programme for your goals or design one specifically for your organisation.';
		$cta['buttons'] = array(
			array( 'Talk to Our Training Team', '/contact-us/?enquiry=training' ),
		);
		return $cta;
	}
);

get_header();
?>
<main id="main" class="jd-main jd-main--flush">

	<?php get_template_part( 'template-parts/calendar/hero' ); ?>

	<?php get_template_part( 'template-parts/calendar/list' ); ?>

	<?php get_template_part( 'template-parts/calendar/download' ); ?>

	<?php get_template_part( 'template-parts/calendar/team' ); ?>

</main>
<?php
get_footer();
