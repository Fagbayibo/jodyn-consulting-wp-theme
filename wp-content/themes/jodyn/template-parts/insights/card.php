<?php
/**
 * Insights · One article card (the Latest Insights grid and Related Articles): a tall photo card
 * with the text over a forest fade: lime category tag, title, short excerpt, date and reading
 * time, Read More. The whole card is the link (the title's ::after). Styles: insights.css.
 * Uses the current post in the loop.
 *
 * @package JoDyn
 */

$jodyn_cat = jodyn_post_category( get_post() );
?>
<li class="jd-ins-card">
	<article>
		<div class="jd-ins-card__img" aria-hidden="true">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail(
					'medium_large',
					array(
						'alt'   => '',
						'sizes' => '(min-width: 1024px) 33vw, (min-width: 601px) 50vw, 100vw',
					)
				);
			}
			?>
		</div>
		<div class="jd-ins-card__body">
			<?php if ( $jodyn_cat ) : ?>
				<span class="jd-ins-card__cat"><?php echo esc_html( $jodyn_cat->name ); ?></span>
			<?php endif; ?>
			<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<p class="jd-ins-card__exc"><?php echo esc_html( jodyn_card_excerpt( get_post(), 18 ) ); ?></p>
			<p class="jd-ins-card__date">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time>
				<span aria-hidden="true">·</span>
				<?php echo jodyn_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo esc_html( jodyn_reading_label( get_post() ) ); ?>
			</p>
			<span class="jd-ins-card__more" aria-hidden="true"><?php esc_html_e( 'Read More', 'jodyn' ); ?> <?php echo jodyn_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		</div>
	</article>
</li>
