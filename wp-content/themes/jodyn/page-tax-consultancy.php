<?php
/**
 * Tax Consultancy service page (slug: tax-consultancy, under Services: /services/tax-consultancy/).
 *
 * 1. Hero: template-parts/tax-consultancy/hero.php (full-bleed photo under the see-through header).
 * 2. When to talk to a tax adviser: template-parts/tax-consultancy/signs.php.
 * 3. Our tax services (#services): template-parts/tax-consultancy/services.php (photo panels,
 *    one open at a time: assets/js/tax-consultancy.js).
 * 4. How we work with you: template-parts/tax-consultancy/how.php.
 * 5. Why JoDyn for tax: template-parts/tax-consultancy/why.php.
 * 6. Tax, audit and advisory together: template-parts/tax-consultancy/fixes.php.
 * The page's FAQs live on the dedicated FAQs page, not here.
 * Copy: content/pages/tax-consultancy.md (approved).
 *
 * @package JoDyn
 */

// The hero photo runs under the header, so the header starts see-through.
add_filter( 'jodyn_header_is_overlay', '__return_true' );

// The footer's lime card carries this page's own closing call to action (tax-consultancy.md, footer card).
add_filter(
	'jodyn_footer_cta',
	static function ( $cta ) {
		$cta['title']   = 'Get Your Tax in <em>Order</em>';
		$cta['text']    = 'Tell us where you stand and what is worrying you. We will recommend the right support and the next step.';
		$cta['buttons'] = array( array( 'Talk to a Tax Adviser', '/contact-us/?enquiry=tax' ) );
		return $cta;
	}
);

get_header();
?>
<main id="main" class="jd-main jd-main--flush">

	<?php get_template_part( 'template-parts/tax-consultancy/hero' ); ?>

	<?php get_template_part( 'template-parts/tax-consultancy/signs' ); ?>

	<?php get_template_part( 'template-parts/tax-consultancy/services' ); ?>

	<?php get_template_part( 'template-parts/tax-consultancy/how' ); ?>

	<?php get_template_part( 'template-parts/tax-consultancy/why' ); ?>

	<?php get_template_part( 'template-parts/tax-consultancy/fixes' ); ?>

</main>
<?php
get_footer();
