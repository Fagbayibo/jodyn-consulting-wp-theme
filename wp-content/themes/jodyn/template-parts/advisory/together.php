<?php
/**
 * Advisory · 6. Advisory and training together.
 *
 * White. Heading beside the opening paragraph; then an advisory photo and a training photo
 * side by side, joined by a lime "+" that spins in (assets/js/advisory.js); the examples
 * paragraph and the button below.
 * Copy: content/pages/advisory.md, section 6 (approved).
 *
 * @package JoDyn
 */

$jodyn_img = get_template_directory_uri() . '/assets/img/advisory/';
?>
<section class="jd-adv-tg" aria-labelledby="jd-adv-tg-title" data-jd-particles="light" data-jd-keep-out=".jd-adv-tg__head > *, .jd-adv-tg__photos, .jd-adv-tg__foot > *">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-adv-tg__head">
			<div>
				<p class="jd-eyebrow" data-jd-rise>The JoDyn advantage</p>
				<h2 class="jd-adv-tg__title" id="jd-adv-tg-title" data-jd-lines>Advice That Your People Are Ready to <em>Deliver</em></h2>
			</div>
			<p class="jd-adv-tg__lead" data-jd-rise="0.1">Many advisory projects end with a report. Ours are designed to end with results. Because JoDyn offers both advisory and training under one roof, we can help you design the right solution and then equip your people with the skills to carry it out.</p>
		</div>

		<div class="jd-adv-tg__photos" data-jd-adv-photos>
			<figure class="jd-adv-tg__ph" data-jd-reveal>
				<img src="<?php echo esc_url( $jodyn_img . 'together-advisory.webp' ); ?>" alt="" width="1200" height="801" loading="lazy" decoding="async" style="object-position: 40% 35%" data-jd-parallax>
				<figcaption>Advisory</figcaption>
			</figure>
			<span class="jd-adv-tg__plus" aria-hidden="true" data-jd-adv-plus>+</span>
			<figure class="jd-adv-tg__ph" data-jd-reveal="0.15">
				<img src="<?php echo esc_url( $jodyn_img . 'together-training.webp' ); ?>" alt="" width="800" height="1200" loading="lazy" decoding="async" style="object-position: 50% 25%" data-jd-parallax>
				<figcaption>Training</figcaption>
			</figure>
		</div>

		<div class="jd-adv-tg__foot">
			<p data-jd-rise>For example, a new performance management system can be followed by training for managers on setting goals and giving feedback. A new strategy can be followed by leadership workshops that help teams translate it into daily priorities. Advice and capability move forward together.</p>
			<div data-jd-rise="0.1">
				<a class="jd-btn jd-btn--lg jd-btn--arrow" href="<?php echo esc_url( home_url( '/training/programmes/' ) ); ?>">
					Explore Our Training Programmes
					<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</a>
			</div>
		</div>
	</div>
</section>
