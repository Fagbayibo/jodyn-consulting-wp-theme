<?php
/**
 * Training programmes page (slug: programmes, under Training: /training/programmes/).
 *
 * In page order (the next dates come before the full list):
 * Hero: template-parts/training/hero.php.
 * Why train with JoDyn: template-parts/training/why.php.
 * Upcoming open programmes (tickets, PDF download): template-parts/training/upcoming.php.
 * Explore our programmes (every programme, filterable): template-parts/training/programmes.php.
 * Training options (tabs): template-parts/training/options.php.
 * The learning journey (vertical timeline): template-parts/training/journey.php.
 * The page's FAQs live on the dedicated FAQs page, and the in-house
 * request form is the shared Contact form (?enquiry=training).
 * Copy: content/pages/training.md (approved).
 *
 * @package JoDyn
 */

// The footer's lime card carries this page's own closing call to action (training.md, footer card).
add_filter(
	'jodyn_footer_cta',
	static function ( $cta ) {
		$cta['title']   = 'Invest in Your People. Strengthen Your <em>Organisation.</em>';
		$cta['text']    = 'Whether you want to attend an upcoming programme or build a training plan for your whole team, we are ready to help you take the next step.';
		$cta['buttons'] = array(
			array( 'View Training Calendar', '/training/calendar/' ),
			array( 'Talk to Our Training Team', '/contact-us/?enquiry=training' ),
		);
		return $cta;
	}
);

get_header();
?>
<main id="main" class="jd-main jd-main--flush">

	<?php get_template_part( 'template-parts/training/hero' ); ?>

	<?php get_template_part( 'template-parts/training/why' ); ?>

	<?php get_template_part( 'template-parts/training/upcoming' ); ?>

	<?php get_template_part( 'template-parts/training/programmes' ); ?>

	<?php get_template_part( 'template-parts/training/options' ); ?>

	<?php get_template_part( 'template-parts/training/journey' ); ?>

</main>
<?php
get_footer();
