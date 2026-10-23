<?php
/**
 * About us page (slug: about-us).
 *
 * 1. Hero: template-parts/about/hero.php.
 * 2. Our story: template-parts/about/story.php.
 * 3. Mission and vision: template-parts/about/mission.php.
 * 4. Values: template-parts/about/values.php.
 * 5. What we do: template-parts/about/what-we-do.php.
 * 6. Our approach: template-parts/about/approach.php.
 * 7. Facilitators and advisors: template-parts/about/experts.php.
 * Leadership is deferred until the client sends team photos and bios.
 * Copy: content/pages/about.md (approved).
 *
 * @package JoDyn
 */

// The footer's lime card carries this page's own closing call to action (about.md, footer card).
add_filter(
	'jodyn_footer_cta',
	static function ( $cta ) {
		$cta['title'] = 'Let\'s Build Something Lasting <em>Together</em>';
		$cta['text']  = 'Whether you are planning a new strategy, reviewing your systems, getting your tax position in order or developing your leaders, we would love to hear about your goals. Speak with our team and discover how JoDyn can support your organisation\'s growth.';
		return $cta;
	}
);

get_header();
?>
<main id="main" class="jd-main jd-main--flush">

	<?php get_template_part( 'template-parts/about/hero' ); ?>

	<?php get_template_part( 'template-parts/about/story' ); ?>

	<?php get_template_part( 'template-parts/about/mission' ); ?>

	<?php get_template_part( 'template-parts/about/values' ); ?>

	<?php get_template_part( 'template-parts/about/what-we-do' ); ?>

	<?php get_template_part( 'template-parts/about/approach' ); ?>

	<?php get_template_part( 'template-parts/about/experts' ); ?>

</main>
<?php
get_footer();
