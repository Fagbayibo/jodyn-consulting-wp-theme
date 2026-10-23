<?php
/**
 * Training · 1. Hero.
 *
 * Soft green, solid header. Centred eyebrow, heading, intro and the two buttons; below them
 * three training photos stand up from the bottom edge like a hand of cards. They rise in on
 * load and the outer two open wider as you scroll (assets/js/training.js).
 * Copy: content/pages/training.md, section 1 (approved).
 *
 * @package JoDyn
 */

$jodyn_img = get_template_directory_uri() . '/assets/img/training/';
// File, object-position. The middle card sits in front.
$jodyn_fan = array(
	array( 'hero-team.webp', '50% 40%' ),
	array( 'hero-facilitator.webp', '60% 30%' ),
	array( 'hero-trainer.webp', '70% 30%' ),
);
?>
<section class="jd-tr-hero" aria-labelledby="jd-tr-title" data-jd-particles="light" data-jd-keep-out=".jd-tr-hero__copy > *, .jd-tr-hero__fan">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-tr-hero__copy">
			<p class="jd-eyebrow" data-jd-rise>Training &amp; development</p>
			<h1 class="jd-tr-hero__title" id="jd-tr-title" data-jd-lines>Training Programmes That Build Capable People and Stronger <em>Organisations</em></h1>
			<p class="jd-tr-hero__text" data-jd-rise="0.15">Practical, engaging training in leadership, management, people and professional skills, designed to help your team perform better from the day they return to work. Delivered in person, online or in a hybrid format.</p>
			<div class="jd-tr-hero__btns" data-jd-rise="0.25">
				<a class="jd-btn jd-btn--lg jd-btn--arrow" href="<?php echo esc_url( home_url( '/training/calendar/' ) ); ?>">
					View Training Calendar
					<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</a>
				<a class="jd-btn jd-btn--lg jd-btn--secondary" href="<?php echo esc_url( home_url( '/contact-us/?enquiry=training' ) ); ?>">Request In-House Training</a>
			</div>
		</div>
	</div>

	<div class="jd-tr-hero__fan" aria-hidden="true" data-jd-tr-fan>
		<?php foreach ( $jodyn_fan as $jodyn_i => $jodyn_card ) : ?>
			<figure class="jd-tr-hero__card">
				<img src="<?php echo esc_url( $jodyn_img . $jodyn_card[0] ); ?>" alt="" width="1200" height="800" decoding="async" <?php echo 1 === $jodyn_i ? 'fetchpriority="high"' : 'loading="lazy"'; ?> style="object-position: <?php echo esc_attr( $jodyn_card[1] ); ?>">
			</figure>
		<?php endforeach; ?>
	</div>
</section>
