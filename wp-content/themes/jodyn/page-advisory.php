<?php
/**
 * Advisory service page (slug: advisory, under Services: /services/advisory/).
 *
 * 1. Hero: template-parts/advisory/hero.php.
 * 2. Challenges we solve: template-parts/advisory/challenges.php.
 * 3. Our advisory services: template-parts/advisory/services.php (pinned horizontal row,
 *    assets/js/advisory.js).
 * 4. How we work with you: template-parts/advisory/work.php.
 * 5. Ways to work with us: template-parts/advisory/ways.php.
 * 6. Advisory and training together: template-parts/advisory/together.php.
 * The page's FAQs live on the dedicated FAQs page, not here.
 * Copy: content/pages/advisory.md (approved).
 *
 * @package JoDyn
 */

// The footer's lime card carries this page's own closing call to action (advisory.md, footer card).
add_filter(
	'jodyn_footer_cta',
	static function ( $cta ) {
		$cta['title']   = 'Ready to Take the Next <em>Step?</em>';
		$cta['text']    = 'Tell us about the challenge you are facing or the goal you are working towards. One of our advisors will get back to you to discuss how we can help.';
		$cta['buttons'] = array( array( 'Book a Consultation', '/contact-us/?enquiry=advisory' ) );
		return $cta;
	}
);

get_header();
?>
<main id="main" class="jd-main jd-main--flush">

	<?php get_template_part( 'template-parts/advisory/hero' ); ?>

	<?php get_template_part( 'template-parts/advisory/challenges' ); ?>

	<?php get_template_part( 'template-parts/advisory/services' ); ?>

	<?php get_template_part( 'template-parts/advisory/work' ); ?>

	<?php get_template_part( 'template-parts/advisory/ways' ); ?>

	<?php get_template_part( 'template-parts/advisory/together' ); ?>

</main>
<?php
get_footer();
