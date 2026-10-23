<?php
/**
 * About · 3. Mission and vision.
 *
 * White. A photo with the Mission (soft green) and Vision (lime) cards overlapping its
 * right edge, then the Promise as a forest pull-quote with its question in lime.
 * Copy: content/pages/about.md, section 3 (approved).
 *
 * @package JoDyn
 */

$jodyn_mv_cards = array(
	array(
		'mod'   => 'tint',
		'label' => 'Our mission',
		'icon'  => '<circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="4.5"/><circle cx="12" cy="12" r="1" fill="currentColor"/>',
		'text'  => 'To help organisations and professionals unlock their full potential through sound strategic advice, independent assurance, practical learning and a genuine commitment to lasting results.',
	),
	array(
		'mod'   => 'lime',
		'label' => 'Our vision',
		'icon'  => '<path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12z"/><circle cx="12" cy="12" r="3"/>',
		'text'  => 'To be a trusted partner for organisations across Africa seeking to build strong leaders, capable teams and sustainable growth.',
	),
);
?>
<section class="jd-mv" aria-labelledby="jd-mv-title" data-jd-particles="light" data-jd-keep-out=".jd-mv__head > *, .jd-mv__grid, .jd-mv__promise">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-mv__head">
			<p class="jd-eyebrow" data-jd-rise>What drives us</p>
			<h2 class="jd-mv__title" id="jd-mv-title" data-jd-lines>Our Mission and <em>Vision</em></h2>
		</div>

		<div class="jd-mv__grid">
			<figure class="jd-mv__photo" data-jd-reveal>
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/about/mission-speaker.webp' ); ?>" alt="" width="1400" height="933" loading="lazy" decoding="async" data-jd-parallax>
			</figure>
			<div class="jd-mv__cards" data-jd-stagger>
				<?php foreach ( $jodyn_mv_cards as $jodyn_card ) : ?>
					<article class="jd-mv__card jd-mv__card--<?php echo esc_attr( $jodyn_card['mod'] ); ?>">
						<h3 class="jd-mv__label">
							<span class="jd-mv__ic" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><?php echo $jodyn_card['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG above. ?></svg></span>
							<?php echo esc_html( $jodyn_card['label'] ); ?>
						</h3>
						<p><?php echo esc_html( $jodyn_card['text'] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>

		<blockquote class="jd-mv__promise" data-jd-rise>
			<span class="jd-mv__quote" aria-hidden="true">&ldquo;</span>
			<div>
				<h3 class="jd-mv__label">Our promise</h3>
				<p>Every engagement we take on is guided by one question: <strong>will this make a real, measurable difference for our client?</strong> If the answer is no, we rethink our approach until it is.</p>
			</div>
		</blockquote>
	</div>
</section>
