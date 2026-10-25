<?php
/**
 * Insights · Hero and featured article (copy sections 1 and 2).
 *
 * White with light particles, under the solid header: the heading and intro on the left; the
 * featured article's photo on the right with its card (lime top edge) hanging off the photo's
 * bottom-left corner: Featured and category tags, title, excerpt, date and reading time, and
 * Read Article. The featured article is the pinned post, or the newest (inc/insights.php).
 * Only on the first page of /insights/; without any posts the heading stands alone. On a category
 * page: "[Category] Insights", the category's description (or the intro) and no featured card.
 * Copy: content/pages/insights.md, sections 1 and 2 (approved).
 *
 * @package JoDyn
 */

$jodyn_feat = ( is_paged() || is_category() ) ? null : jodyn_featured_post();
// A category page gets its own heading ("Tax Insights") and, when the category has one, its description.
$jodyn_title = is_category() ? esc_html( single_cat_title( '', false ) ) . ' <em>' . esc_html__( 'Insights', 'jodyn' ) . '</em>' : 'Insights for Leaders and <em>Teams</em>';
$jodyn_text  = is_category() ? wp_strip_all_tags( category_description() ) : '';
$jodyn_text  = $jodyn_text ? $jodyn_text : 'Practical ideas, guides and perspectives on strategy, systems, tax, leadership and people, written to help you and your organisation stay ahead.';
$jodyn_cat  = $jodyn_feat ? jodyn_post_category( $jodyn_feat ) : null;
?>
<section class="jd-ins-top<?php echo $jodyn_feat ? '' : ' is-solo'; ?>" aria-labelledby="jd-ins-title" data-jd-particles="light" data-jd-keep-out=".jd-ins-top__copy > *, .jd-ins-top__feat">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>
	<div class="jd-container jd-ins-top__grid">
		<div class="jd-ins-top__copy">
			<p class="jd-eyebrow" data-jd-rise>Insights</p>
			<h1 class="jd-ins-top__title" id="jd-ins-title" data-jd-lines><?php echo wp_kses( $jodyn_title, array( 'em' => array() ) ); ?></h1>
			<p class="jd-ins-top__text" data-jd-rise="0.15"><?php echo esc_html( $jodyn_text ); ?></p>
		</div>

		<?php if ( $jodyn_feat ) : ?>
			<article class="jd-ins-top__feat" data-jd-reveal>
				<a class="jd-ins-top__img" href="<?php echo esc_url( get_permalink( $jodyn_feat ) ); ?>" tabindex="-1" aria-hidden="true">
					<?php
					if ( has_post_thumbnail( $jodyn_feat ) ) {
						echo get_the_post_thumbnail(
							$jodyn_feat,
							'large',
							array(
								'alt'           => '',
								'sizes'         => '(min-width: 1024px) 56vw, 100vw',
								'fetchpriority' => 'high',
								'loading'       => 'eager',
							)
						);
					}
					?>
				</a>
				<div class="jd-ins-top__card">
					<div class="jd-ins-top__tags">
						<span class="jd-ins-top__flag"><?php esc_html_e( 'Featured', 'jodyn' ); ?></span>
						<?php if ( $jodyn_cat ) : ?>
							<a class="jd-ins-top__cat" href="<?php echo esc_url( get_category_link( $jodyn_cat ) ); ?>"><?php echo esc_html( $jodyn_cat->name ); ?></a>
						<?php endif; ?>
					</div>
					<h2 class="jd-ins-top__ftitle"><a href="<?php echo esc_url( get_permalink( $jodyn_feat ) ); ?>"><?php echo esc_html( get_the_title( $jodyn_feat ) ); ?></a></h2>
					<p class="jd-ins-top__exc"><?php echo esc_html( jodyn_card_excerpt( $jodyn_feat ) ); ?></p>
					<p class="jd-ins-top__date">
						<time datetime="<?php echo esc_attr( get_the_date( 'c', $jodyn_feat ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y', $jodyn_feat ) ); ?></time>
						<span aria-hidden="true">·</span>
						<?php echo jodyn_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php echo esc_html( jodyn_reading_label( $jodyn_feat ) ); ?>
					</p>
					<a class="jd-btn jd-btn--arrow" href="<?php echo esc_url( get_permalink( $jodyn_feat ) ); ?>">
						<?php esc_html_e( 'Read Article', 'jodyn' ); ?>
						<span class="screen-reader-text">: <?php echo esc_html( get_the_title( $jodyn_feat ) ); ?></span>
						<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</a>
				</div>
			</article>
		<?php endif; ?>
	</div>
</section>
