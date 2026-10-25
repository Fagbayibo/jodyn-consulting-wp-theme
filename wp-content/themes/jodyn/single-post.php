<?php
/**
 * An Insights article (/insights/[article-title]/ … WordPress's single post).
 *
 * In page order:
 * Photo header, On this page and share, the article, author box: template-parts/insights/article.php.
 * Related Articles (three photo cards from the same category): template-parts/insights/related.php.
 * The footer's lime card is the standard end-of-article call to action (inc/insights.php).
 * Copy: content/pages/insights.md, article template (approved).
 *
 * @package JoDyn
 */

get_header();
?>
<main id="main" class="jd-main jd-main--flush">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/insights/article' );
		get_template_part( 'template-parts/insights/related' );
	endwhile;
	?>
</main>
<?php
get_footer();
