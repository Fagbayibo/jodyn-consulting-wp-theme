<?php
/**
 * Insights · Categories and Latest Insights (copy sections 3 and 4).
 *
 * Soft green: "Latest Insights" beside the category tabs (pills with counts; each one is the
 * category's own page, so it works without JavaScript and Google can index it), then the
 * articles as tall photo cards with the title over a forest fade: lime category tag, title,
 * short excerpt, date and reading time, Read More. Numbered pages underneath; tabs and page
 * links jump straight back to this section (#latest). The featured article is left out
 * (inc/insights.php). Empty state from the approved copy.
 * Copy: content/pages/insights.md, sections 3 and 4 (approved).
 *
 * @package JoDyn
 */

$jodyn_current = is_category() ? get_queried_object_id() : 0;
$jodyn_tabs    = jodyn_insight_tabs();
$jodyn_all     = (int) wp_count_posts( 'post' )->publish;
$jodyn_chev    = static function ( $dir ) {
	return '<svg class="jd-ins-grid__chev is-' . $dir . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="' . ( 'prev' === $dir ? 'M15 6l-6 6 6 6' : 'M9 6l6 6-6 6' ) . '"/></svg>';
};
?>
<section class="jd-ins-grid" id="latest" aria-labelledby="jd-ins-grid-title">
	<div class="jd-container">
		<div class="jd-ins-grid__head">
			<h2 class="jd-ins-grid__title" id="jd-ins-grid-title" data-jd-lines>Latest <em>Insights</em></h2>
			<?php if ( $jodyn_tabs ) : ?>
				<nav class="jd-ins-grid__tabs" aria-label="<?php esc_attr_e( 'Categories', 'jodyn' ); ?>">
					<ul>
						<li><a href="<?php echo esc_url( jodyn_blog_url() . '#latest' ); ?>"<?php echo $jodyn_current ? '' : ' aria-current="page"'; ?>><?php esc_html_e( 'All', 'jodyn' ); ?><span><?php echo esc_html( $jodyn_all ); ?></span></a></li>
						<?php foreach ( $jodyn_tabs as $jodyn_tab ) : ?>
							<li><a href="<?php echo esc_url( get_category_link( $jodyn_tab ) . '#latest' ); ?>"<?php echo $jodyn_current === $jodyn_tab->term_id ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $jodyn_tab->name ); ?><span><?php echo esc_html( $jodyn_tab->count ); ?></span></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php endif; ?>
		</div>

		<?php if ( have_posts() ) : ?>
			<ul class="jd-ins-grid__list" data-jd-stagger>
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/insights/card' );
				endwhile;
				?>
			</ul>

			<?php
			$jodyn_pages = paginate_links(
				array(
					'type'         => 'array',
					'mid_size'     => 1,
					'add_fragment' => '#latest',
					'prev_text'    => $jodyn_chev( 'prev' ) . '<span class="screen-reader-text">' . esc_html__( 'Previous page', 'jodyn' ) . '</span>',
					'next_text'    => $jodyn_chev( 'next' ) . '<span class="screen-reader-text">' . esc_html__( 'Next page', 'jodyn' ) . '</span>',
				)
			);
			if ( $jodyn_pages ) :
				?>
				<nav class="jd-ins-grid__pager" aria-label="<?php esc_attr_e( 'Pages', 'jodyn' ); ?>">
					<?php echo implode( '', $jodyn_pages ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</nav>
			<?php endif; ?>

		<?php else : ?>
			<p class="jd-ins-grid__empty">New articles are on their way. In the meantime, <a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">talk to an advisor</a> or <a href="<?php echo esc_url( home_url( '/training/programmes/' ) ); ?>">explore our training programmes</a>.</p>
		<?php endif; ?>
	</div>
</section>
