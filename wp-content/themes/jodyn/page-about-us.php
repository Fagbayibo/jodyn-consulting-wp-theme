<?php
/**
 * About us page (slug: about-us).
 *
 * 1. Hero: template-parts/about/hero.php.
 * 2. Our story: template-parts/about/story.php.
 * Sections 3-7 (mission, values, what we do, approach, experts) follow as they are built.
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

</main>
<?php
get_footer();
