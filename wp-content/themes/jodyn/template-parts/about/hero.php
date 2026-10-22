<?php
/**
 * About · 1. Hero.
 *
 * White, centred heading (lime highlight on the last word), then a strip of four photos
 * and a lime tile that wipe open one after another on load (assets/js/about-hero.js).
 * Copy: content/pages/about.md, section 1 (approved).
 *
 * @package JoDyn
 */

$jodyn_img   = get_template_directory_uri() . '/assets/img/about/';
$jodyn_strip = array(
	array( 'hero-training', 1000, 1500 ),
	array( 'hero-team', 1400, 933 ),
	'tile',
	array( 'hero-meeting', 1400, 933 ),
	array( 'hero-leader', 1200, 800 ),
);
?>
<section class="jd-about-hero" aria-labelledby="jd-about-title" data-jd-particles="light" data-jd-keep-out=".jd-about-hero__copy > *, .jd-about-hero__strip">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-about-hero__copy">
			<p class="jd-eyebrow" data-jd-rise>About us</p>
			<h1 class="jd-about-hero__title" id="jd-about-title" data-jd-lines>Helping Organisations Grow Through Their <em>People</em></h1>
			<p class="jd-about-hero__text" data-jd-rise="0.15">JoDyn Advisory and Consulting is an advisory, audit, tax and training firm dedicated to helping organisations across Nigeria build clear strategies, sound systems, capable leaders and high-performing teams.</p>
			<div data-jd-rise="0.25">
				<a class="jd-btn jd-btn--lg jd-btn--arrow" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
					Talk to an Advisor
					<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</a>
			</div>
		</div>
	</div>

	<div class="jd-about-hero__strip" aria-hidden="true" data-jd-about-strip>
		<?php foreach ( $jodyn_strip as $i => $item ) : ?>
			<?php if ( 'tile' === $item ) : ?>
				<div class="jd-about-hero__tile"><span class="jd-about-hero__mark"><i></i><i></i><i></i></span></div>
			<?php else : ?>
				<figure class="jd-about-hero__ph jd-about-hero__ph--<?php echo esc_attr( $i + 1 ); ?>">
					<img src="<?php echo esc_url( $jodyn_img . $item[0] . '.webp' ); ?>" alt="" width="<?php echo esc_attr( $item[1] ); ?>" height="<?php echo esc_attr( $item[2] ); ?>" decoding="async">
				</figure>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</section>
