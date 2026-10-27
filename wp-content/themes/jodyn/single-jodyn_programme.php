<?php
/**
 * A training programme's own page (/programme/[name]/).
 *
 * One template for every programme, filled from its fields (Programmes → Edit):
 * template-parts/programme/page.php. Footer card and menu: inc/programme-page.php.
 * Copy: content/pages/programme.md (approved).
 *
 * @package JoDyn
 */

get_header();
?>
<main id="main" class="jd-main jd-main--flush">

	<?php
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/programme/page', null, array( 'p' => jodyn_programme_page( get_post() ) ) );
	}
	?>

</main>
<?php
get_footer();
