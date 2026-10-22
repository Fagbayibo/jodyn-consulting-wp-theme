<?php
/**
 * Front page.
 *
 * 1. Hero: full-screen background film (set in Customize → Home hero), scrim,
 *    headline, and a pause control.
 * 2. Who we are: template-parts/home/who-we-are.php.
 * 3. How we help: template-parts/home/how-we-help.php.
 * 5. Upcoming training programmes: template-parts/home/upcoming-programmes.php (from Programmes).
 * 6. How we work: template-parts/home/how-we-work.php.
 * 7. Why JoDyn: template-parts/home/why-jodyn.php.
 * 8. Who we serve: template-parts/home/who-we-serve.php.
 * 9. Outcomes: template-parts/home/outcomes.php.
 * 10. Insights: template-parts/home/insights.php (three latest posts).
 * 12-13. Closing call to action and footer: footer.php (shared by every page).
 *
 * @package JoDyn
 */

get_header();

$jodyn_video_url = static function ( $setting ) {
	$id = (int) get_theme_mod( $setting, 0 );
	return $id ? (string) wp_get_attachment_url( $id ) : '';
};
$jodyn_video        = $jodyn_video_url( 'jodyn_hero_video' );
$jodyn_video_mobile = $jodyn_video_url( 'jodyn_hero_video_mobile' );
$jodyn_poster       = get_theme_mod( 'jodyn_hero_poster', '' );
if ( ! $jodyn_poster ) {
	$jodyn_poster = get_template_directory_uri() . '/assets/img/hero-poster.jpg';
}
?>
<main id="main" class="jd-main">

	<section class="jd-hero<?php echo $jodyn_video ? ' has-video' : ''; ?>" data-jd-hero>
		<div class="jd-hero__media" aria-hidden="true">
			<img class="jd-hero__poster" src="<?php echo esc_url( $jodyn_poster ); ?>" alt="" fetchpriority="high" decoding="async">
			<?php if ( $jodyn_video ) : ?>
				<?php
				/*
				 * No src in the markup: hero.js picks the desktop or mobile file and starts it
				 * after load, and skips it entirely for Data Saver and reduced-motion visitors.
				 */
				?>
				<video class="jd-hero__video" muted loop playsinline preload="none"
					data-src="<?php echo esc_url( $jodyn_video ); ?>"
					<?php if ( $jodyn_video_mobile ) : ?>data-src-mobile="<?php echo esc_url( $jodyn_video_mobile ); ?>"<?php endif; ?>></video>
			<?php endif; ?>
		</div>

		<div class="jd-hero__inner">
			<p class="jd-eyebrow jd-eyebrow--inverse"><?php bloginfo( 'name' ); ?></p>
			<h1 class="jd-hero__title"><span class="jd-hero__line">Advisory that turns</span> <span class="jd-hero__line">strategy into <mark class="jd-hl">measurable</mark></span> <span class="jd-hero__line">growth</span></h1>
			<div class="jd-hero__aside">
				<p class="jd-hero__lead">We work alongside leadership teams to design strategy, build capability and deliver change that lasts.</p>
				<a class="jd-btn jd-btn--accent jd-btn--lg jd-btn--arrow" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">
					Explore our services
					<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</a>
			</div>
		</div>

		<button class="jd-hero__pause" type="button" aria-pressed="false" data-jd-hero-pause>
			<span class="screen-reader-text" data-label-pause="<?php esc_attr_e( 'Pause background video', 'jodyn' ); ?>" data-label-play="<?php esc_attr_e( 'Play background video', 'jodyn' ); ?>"><?php esc_html_e( 'Pause background video', 'jodyn' ); ?></span>
			<svg class="jd-hero__pause-icon" viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><rect x="6" y="5" width="4" height="14" rx="1" fill="currentColor"/><rect x="14" y="5" width="4" height="14" rx="1" fill="currentColor"/></svg>
			<svg class="jd-hero__play-icon" viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M8 5v14l11-7z" fill="currentColor"/></svg>
		</button>
	</section>

	<?php get_template_part( 'template-parts/home/who-we-are' ); ?>

	<?php get_template_part( 'template-parts/home/how-we-help' ); ?>

	<?php get_template_part( 'template-parts/home/upcoming-programmes' ); ?>

	<?php get_template_part( 'template-parts/home/how-we-work' ); ?>

	<?php get_template_part( 'template-parts/home/why-jodyn' ); ?>

	<?php get_template_part( 'template-parts/home/who-we-serve' ); ?>

	<?php get_template_part( 'template-parts/home/outcomes' ); ?>

	<?php get_template_part( 'template-parts/home/insights' ); ?>

</main>
<?php
get_footer();
