<?php
/**
 * Privacy Policy (slug: privacy-policy, /privacy-policy/).
 *
 * The shared legal layout: template-parts/legal/page.php. No footer card on this page (footer.php).
 * Copy: content/pages/legal.md (approved).
 *
 * @package JoDyn
 */

get_header();
?>
<main id="main" class="jd-main jd-main--flush">

	<?php get_template_part( 'template-parts/legal/page', null, array( 'slug' => 'privacy-policy' ) ); ?>

</main>
<?php
get_footer();
