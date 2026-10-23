<?php
/**
 * About · 2. Our story.
 *
 * Soft green. Heading beside the opening paragraph; then the five gaps as cards that
 * flip from ✕ to ✓ as they scroll into view (assets/js/about.js), beside a photo and the
 * forest "We exist to close that gap." card. At rest (no JS, reduced motion) every gap shows ✓.
 * Copy: content/pages/about.md, section 2 (approved).
 *
 * @package JoDyn
 */

$jodyn_gaps = array(
	'Strategies are written but not executed.',
	'Controls and processes fall behind the business they are meant to protect.',
	'Tax becomes a source of risk instead of something managed with confidence.',
	'Talented employees are promoted into leadership without being prepared to lead.',
	'Training happens, but the learning never makes its way back to the desk.',
);
?>
<section class="jd-story" aria-labelledby="jd-story-title" data-jd-particles="light" data-jd-keep-out=".jd-story__top > *, .jd-story__gaps > *, .jd-story__close > *">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-story__top">
			<div>
				<p class="jd-eyebrow" data-jd-rise>Our story</p>
				<h2 class="jd-story__title" id="jd-story-title" data-jd-lines>Built on a Simple <em>Conviction</em></h2>
			</div>
			<p class="jd-story__lead" data-jd-rise="0.1">JoDyn Advisory and Consulting was founded on a simple conviction: organisations do not grow by strategy alone. They grow when the right direction is matched by sound systems and by people with the skills, confidence and support to deliver it.</p>
		</div>

		<div class="jd-story__grid">
			<div class="jd-story__gaps">
				<p class="jd-story__gaps-intro" data-jd-rise>Across many organisations, the gap is rarely a lack of ideas.</p>
				<ol class="jd-story__list" data-jd-story-gaps>
					<?php foreach ( $jodyn_gaps as $jodyn_gap ) : ?>
						<li>
							<span class="jd-story__ic" aria-hidden="true">
								<span class="jd-story__ic-x"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M8 8l8 8M16 8l-8 8"/></svg></span>
								<span class="jd-story__ic-ok"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg></span>
							</span>
							<?php echo esc_html( $jodyn_gap ); ?>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>

			<div class="jd-story__close">
				<figure class="jd-story__photo" data-jd-reveal>
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/about/story-team.webp' ); ?>" alt="" width="1400" height="933" loading="lazy" decoding="async" data-jd-parallax>
				</figure>
				<div class="jd-story__card" data-jd-rise="0.1">
					<p class="jd-story__card-lead">We exist to close that gap.</p>
					<p>JoDyn brings advisory, systems audit, tax consultancy and training together under one roof, so the strategies we help organisations shape are supported by the systems, compliance and leadership needed to carry them through. We help leaders think clearly, organisations run on systems they can trust and teams build capability that lasts.</p>
				</div>
				<p class="jd-story__foot" data-jd-rise>We work with corporate organisations, public institutions, growing businesses, non-profits and individual professionals, and our training is delivered in person, online or in a hybrid format, always designed around each client's goals.</p>
			</div>
		</div>
	</div>
</section>
