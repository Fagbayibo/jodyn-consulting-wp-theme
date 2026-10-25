<?php
/**
 * Insights article · Related Articles: three photo cards (template-parts/insights/card.php),
 * the newest from the same category, topped up with the newest overall. Soft green.
 *
 * @package JoDyn
 */

$jodyn_related = jodyn_related_posts( get_the_ID() );
if ( ! $jodyn_related ) {
	return;
}
?>
<section class="jd-ins-grid jd-art-rel" aria-labelledby="jd-art-rel-title">
	<div class="jd-container">
		<h2 class="jd-ins-grid__title jd-art-rel__title" id="jd-art-rel-title" data-jd-lines>Related <em>Articles</em></h2>
		<ul class="jd-ins-grid__list" data-jd-stagger>
			<?php
			global $post;
			foreach ( $jodyn_related as $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- restored below.
				setup_postdata( $post );
				get_template_part( 'template-parts/insights/card' );
			endforeach;
			wp_reset_postdata();
			?>
		</ul>
	</div>
</section>
