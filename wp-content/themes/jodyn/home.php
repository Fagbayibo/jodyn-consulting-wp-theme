<?php
/**
 * Insights (the posts page, /insights/: Settings → Reading → Posts page), and the category
 * pages (/category/tax/ and so on, through category.php).
 *
 * In page order:
 * Hero and featured article (magazine split; on a category page, the category's heading):
 * template-parts/insights/top.php.
 * Category tabs and Latest Insights (photo cards, numbered pages): template-parts/insights/grid.php.
 * The footer's lime card carries the page's closing call to action (inc/insights.php).
 * Articles per page: Settings → Reading → Blog pages show at most (9, set on install).
 * Copy: content/pages/insights.md (approved).
 *
 * @package JoDyn
 */

get_header();
?>
<main id="main" class="jd-main jd-main--flush">

	<?php get_template_part( 'template-parts/insights/top' ); ?>

	<?php get_template_part( 'template-parts/insights/grid' ); ?>

</main>
<?php
get_footer();
