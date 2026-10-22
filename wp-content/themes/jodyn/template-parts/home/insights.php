<?php
/**
 * Home · 10. Insights.
 *
 * White section: a forest panel (heading, text, button) beside the three latest posts as
 * horizontal cards (photo, category, date, title). Skipped when there are no posts yet.
 * Copy: content/pages/home.md, section 10 (approved).
 *
 * @package JoDyn
 */

$jodyn_posts = get_posts(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'numberposts'         => 3,
		'ignore_sticky_posts' => true,
		'suppress_filters'    => false,
	)
);
if ( ! $jodyn_posts ) {
	return;
}

// "Read Our Insights" goes to the blog page when one is set in Settings → Reading.
$jodyn_blog = (int) get_option( 'page_for_posts' );
$jodyn_blog = $jodyn_blog ? get_permalink( $jodyn_blog ) : home_url( '/insights/' );
?>
<section class="jd-insights" id="insights" aria-labelledby="jd-insights-title"
	data-jd-particles="light" data-jd-keep-out=".jd-insights__wrap">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-insights__wrap">

			<div class="jd-insights__panel" data-jd-reveal>
				<p class="jd-eyebrow jd-eyebrow--inverse">Insights</p>
				<h2 class="jd-insights__title" id="jd-insights-title">Insights for Leaders and <em>Teams</em></h2>
				<p class="jd-insights__text">Practical ideas, guides and perspectives on strategy, systems, tax, leadership and organisational growth, written to help you and your team stay ahead.</p>
				<a class="jd-btn jd-btn--accent jd-btn--arrow" href="<?php echo esc_url( $jodyn_blog ); ?>">
					Read Our Insights
					<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</a>
				<span class="jd-insights__mark" aria-hidden="true"><i></i><i></i><i></i></span>
			</div>

			<ul class="jd-insights__list">
				<?php foreach ( $jodyn_posts as $i => $post_item ) : ?>
					<?php
					$jodyn_cats = get_the_category( $post_item->ID );
					$jodyn_cat  = $jodyn_cats ? $jodyn_cats[0]->name : '';
					?>
					<li class="jd-insights__card" data-jd-rise="<?php echo esc_attr( 0.1 + $i * 0.1 ); ?>">
						<a href="<?php echo esc_url( get_permalink( $post_item ) ); ?>">
							<div class="jd-insights__thumb">
								<?php
								if ( has_post_thumbnail( $post_item ) ) {
									echo get_the_post_thumbnail(
										$post_item,
										'medium_large',
										array(
											'alt'   => '',
											'sizes' => '(min-width: 560px) 200px, 100vw',
										)
									);
								}
								?>
							</div>
							<div class="jd-insights__body">
								<div class="jd-insights__meta">
									<?php if ( $jodyn_cat && 'Uncategorized' !== $jodyn_cat ) : ?>
										<span class="jd-insights__cat"><?php echo esc_html( $jodyn_cat ); ?></span>
									<?php endif; ?>
									<time datetime="<?php echo esc_attr( get_the_date( 'c', $post_item ) ); ?>"><?php echo esc_html( get_the_date( 'j M Y', $post_item ) ); ?></time>
								</div>
								<h3><?php echo esc_html( get_the_title( $post_item ) ); ?></h3>
								<span class="jd-insights__read" aria-hidden="true"><?php esc_html_e( 'Read article', 'jodyn' ); ?> <?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							</div>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

		</div>
	</div>
</section>
