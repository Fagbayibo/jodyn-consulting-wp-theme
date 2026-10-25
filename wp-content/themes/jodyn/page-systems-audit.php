<?php
/**
 * Systems Audit service page (slug: systems-audit, under Services: /services/systems-audit/).
 *
 * 1. Hero: template-parts/systems-audit/hero.php.
 * 2. Signs you need an audit: template-parts/systems-audit/signs.php.
 * 3. Our audit services (#services): template-parts/systems-audit/services.php (lime index
 *    that follows the scroll, assets/js/systems-audit.js).
 * 4. How a systems audit works: template-parts/systems-audit/how.php.
 * 5. What you receive: template-parts/systems-audit/receive.php.
 * 6. Audit, advisory and training together: template-parts/systems-audit/fixes.php.
 * The page's FAQs live on the dedicated FAQs page, not here.
 * Copy: content/pages/systems-audit.md (approved).
 *
 * @package JoDyn
 */

// The footer's lime card carries this page's own closing call to action (systems-audit.md, footer card).
add_filter(
	'jodyn_footer_cta',
	static function ( $cta ) {
		$cta['title']   = 'Know Where You <em>Stand</em>';
		$cta['text']    = 'Tell us what is worrying you or what you need to prove to others. We will recommend the right scope for your audit and the next step.';
		$cta['buttons'] = array( array( 'Request an Audit', '/contact-us/?enquiry=audit' ) );
		return $cta;
	}
);

get_header();
?>
<main id="main" class="jd-main jd-main--flush">

	<?php get_template_part( 'template-parts/systems-audit/hero' ); ?>

	<?php get_template_part( 'template-parts/systems-audit/signs' ); ?>

	<?php get_template_part( 'template-parts/systems-audit/services' ); ?>

	<?php get_template_part( 'template-parts/systems-audit/how' ); ?>

	<?php get_template_part( 'template-parts/systems-audit/receive' ); ?>

	<?php get_template_part( 'template-parts/systems-audit/fixes' ); ?>

</main>
<?php
get_footer();
