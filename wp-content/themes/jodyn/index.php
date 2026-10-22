<?php
/**
 * Fallback template.
 *
 * @package JoDyn
 */

get_header();
?>
<main id="main" class="jd-main">
	<div class="jd-container">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'jd-entry' ); ?>>
					<h1 class="jd-entry__title"><?php the_title(); ?></h1>
					<div class="jd-entry__content"><?php the_content(); ?></div>
				</article>
				<?php
			endwhile;
		else :
			?>
			<p><?php esc_html_e( 'Nothing here yet.', 'jodyn' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
