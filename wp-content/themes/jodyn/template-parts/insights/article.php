<?php
/**
 * Insights article · Photo header, side column, the article and the author box.
 *
 * The article banner (jodyn_article_banner_id(); not the featured image, which is for the cards)
 * fills the top under the see-through header (forest when there is none), with the breadcrumb,
 * category, title, author, date and reading time over a forest fade. Below, a left column that
 * stays in view: On this page (the article's H2s, the one being read
 * highlighted; hidden under two headings) and the share buttons (LinkedIn, WhatsApp, X, email,
 * copy link). Then the article (the related programme box and Key takeaways are Group block
 * styles the writer adds: inc/insights.php) and the author box from the author's profile.
 * Copy: content/pages/insights.md, article template (approved).
 *
 * @package JoDyn
 */

list( $jodyn_html, $jodyn_heads ) = jodyn_article_content();
$jodyn_cat    = jodyn_post_category( get_post() );
$jodyn_author = (int) get_the_author_meta( 'ID' );
$jodyn_name   = get_the_author();
$jodyn_job    = get_the_author_meta( 'jodyn_job_title' );
$jodyn_bio    = get_the_author_meta( 'description' );
preg_match_all( '/(?<!\p{L})\p{L}/u', $jodyn_name, $jodyn_m ); // first letter of each word: initials for the avatar
$jodyn_init   = mb_strtoupper( implode( '', array_slice( $jodyn_m[0], 0, 2 ) ) );
$jodyn_share  = jodyn_share_links();
$jodyn_photo  = jodyn_article_banner_id();
$jodyn_icons  = array(
	'linkedin' => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9h4v12H3zM9 9h3.8v1.7h.05c.53-1 1.83-2.05 3.77-2.05C20.6 8.65 21 11.2 21 14.4V21h-4v-5.8c0-1.4-.03-3.2-1.95-3.2-1.95 0-2.25 1.5-2.25 3.1V21H9z"/></svg>',
	'whatsapp' => jodyn_icon( 'whatsapp' ),
	'x'        => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.75 3h3.07l-6.7 7.66L22 21h-6.17l-4.83-6.32L5.47 21H2.4l7.17-8.2L2 3h6.33l4.37 5.78zM16.67 19.2h1.7L7.4 4.7H5.57z"/></svg>',
	'email'    => jodyn_icon( 'mail' ),
);
$jodyn_chev   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>';
?>
<article <?php post_class( 'jd-art' ); ?> aria-labelledby="jd-art-title">
	<header class="jd-art__hero<?php echo $jodyn_photo ? '' : ' is-plain'; ?>">
		<?php if ( $jodyn_photo ) : ?>
			<figure class="jd-art__photo">
				<?php
				echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$jodyn_photo,
					'full',
					false,
					array(
						'sizes'         => '100vw',
						'fetchpriority' => 'high',
						'loading'       => 'eager',
						'data-jd-art-img' => '',
					)
				);
				?>
			</figure>
		<?php endif; ?>
		<div class="jd-container jd-art__head">
			<nav class="jd-art__crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'jodyn' ); ?>">
				<ol>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'jodyn' ); ?></a></li>
					<li><?php echo $jodyn_chev; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><a href="<?php echo esc_url( jodyn_blog_url() ); ?>"><?php esc_html_e( 'Insights', 'jodyn' ); ?></a></li>
					<?php if ( $jodyn_cat ) : ?>
						<li><?php echo $jodyn_chev; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><a href="<?php echo esc_url( get_category_link( $jodyn_cat ) ); ?>"><?php echo esc_html( $jodyn_cat->name ); ?></a></li>
					<?php endif; ?>
				</ol>
			</nav>
			<?php if ( $jodyn_cat ) : ?>
				<a class="jd-art__cat" href="<?php echo esc_url( get_category_link( $jodyn_cat ) ); ?>" data-jd-rise><?php echo esc_html( $jodyn_cat->name ); ?></a>
			<?php endif; ?>
			<h1 class="jd-art__title" id="jd-art-title" data-jd-lines><?php the_title(); ?></h1>
			<div class="jd-art__meta" data-jd-rise="0.15">
				<?php if ( $jodyn_name ) : ?>
					<span class="jd-art__avatar" aria-hidden="true"><?php echo esc_html( $jodyn_init ); ?></span>
				<?php endif; ?>
				<span>
					<?php if ( $jodyn_name ) : ?>
						<b><?php echo esc_html( $jodyn_name ); ?></b>
					<?php endif; ?>
					<small>
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time>
						<span aria-hidden="true">·</span>
						<?php echo jodyn_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php echo esc_html( jodyn_reading_label( get_post() ) ); ?>
					</small>
				</span>
			</div>
		</div>
	</header>

	<div class="jd-container jd-art__grid">
		<aside class="jd-art__side">
			<?php if ( count( $jodyn_heads ) >= 2 ) : ?>
				<nav class="jd-art__toc" aria-label="<?php esc_attr_e( 'On this page', 'jodyn' ); ?>">
					<p class="jd-art__label"><?php esc_html_e( 'On this page', 'jodyn' ); ?></p>
					<ol>
						<?php foreach ( $jodyn_heads as $jodyn_head ) : ?>
							<li><a href="#<?php echo esc_attr( $jodyn_head[0] ); ?>"><?php echo esc_html( $jodyn_head[1] ); ?></a></li>
						<?php endforeach; ?>
					</ol>
				</nav>
			<?php endif; ?>
			<div class="jd-art__share">
				<p class="jd-art__label"><?php esc_html_e( 'Share this article', 'jodyn' ); ?></p>
				<ul>
					<?php foreach ( $jodyn_share as $jodyn_key => $jodyn_link ) : ?>
						<li>
							<a href="<?php echo esc_url( $jodyn_link[1] ); ?>"<?php echo 'email' === $jodyn_key ? '' : ' target="_blank" rel="noopener"'; ?> title="<?php echo esc_attr( $jodyn_link[0] ); ?>">
								<?php echo $jodyn_icons[ $jodyn_key ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php /* translators: %s: LinkedIn, WhatsApp, X or email. */ ?>
								<span class="screen-reader-text"><?php echo esc_html( sprintf( __( 'Share by %s', 'jodyn' ), $jodyn_link[0] ) ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
					<li>
						<button type="button" title="<?php esc_attr_e( 'Copy link', 'jodyn' ); ?>" data-jd-copy="<?php echo esc_url( get_permalink() ); ?>">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/></svg>
							<span class="screen-reader-text"><?php esc_html_e( 'Copy link', 'jodyn' ); ?></span>
						</button>
					</li>
				</ul>
				<p class="jd-art__copied" role="status"></p>
			</div>
		</aside>

		<div class="jd-art__main">
			<div class="jd-art__prose">
				<?php echo $jodyn_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output. ?>
			</div>

			<?php if ( $jodyn_name ) : ?>
			<aside class="jd-art__author" aria-label="<?php esc_attr_e( 'About the author', 'jodyn' ); ?>">
				<span class="jd-art__avatar is-lg" aria-hidden="true"><?php echo esc_html( $jodyn_init ); ?></span>
				<div>
					<p class="jd-art__label"><?php esc_html_e( 'Written by', 'jodyn' ); ?></p>
					<p class="jd-art__aname"><a href="<?php echo esc_url( get_author_posts_url( $jodyn_author ) ); ?>"><?php echo esc_html( $jodyn_name ); ?></a></p>
					<?php if ( $jodyn_job ) : ?>
						<p class="jd-art__role"><?php echo esc_html( $jodyn_job ); ?></p>
					<?php endif; ?>
					<?php if ( $jodyn_bio ) : ?>
						<p class="jd-art__bio"><?php echo esc_html( $jodyn_bio ); ?></p>
					<?php endif; ?>
				</div>
			</aside>
			<?php endif; ?>
		</div>
	</div>
</article>
