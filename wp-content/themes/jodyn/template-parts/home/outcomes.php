<?php
/**
 * Home · 9. Outcomes.
 *
 * Soft green section. The five outcomes are blocks rising like steps (the stepped mark in the
 * logo); they grow up from the baseline on scroll (assets/js/outcomes.js). Below desktop they
 * become an offset stack. Copy: content/pages/home.md, section 9 (approved).
 *
 * @package JoDyn
 */

$jodyn_outcomes = array(
	array( 'Clearer Strategic Direction', 'Leaders aligned on priorities, with practical plans that turn goals into action.' ),
	array( 'Stronger Systems and Controls', 'Processes, controls and tax practices that reduce risk and stand up to scrutiny.' ),
	array( 'Higher Productivity', 'Streamlined processes and skilled teams that deliver more with less friction.' ),
	array( 'Stronger Leadership', 'Managers and executives equipped to inspire, guide and develop their teams.' ),
	array( 'Engaged, Capable People', 'Employees who keep learning, grow in their roles and contribute more.' ),
);
?>
<section class="jd-outcomes" id="outcomes" aria-labelledby="jd-outcomes-title" data-jd-outcomes
	data-jd-particles="light" data-jd-keep-out=".jd-outcomes__head > *, .jd-outcomes__stairs">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">

		<div class="jd-outcomes__head">
			<div>
				<p class="jd-eyebrow" data-jd-rise>The JoDyn difference</p>
				<h2 class="jd-outcomes__title" id="jd-outcomes-title" data-jd-lines>What Our Work Helps You <em>Achieve</em></h2>
			</div>
			<div class="jd-outcomes__head-right">
				<p class="jd-outcomes__text" data-jd-rise="0.1">Behind every engagement is a simple goal: to leave your organisation stronger than we found it. Our work is designed to help you achieve:</p>
				<div data-jd-rise="0.2">
					<a class="jd-btn jd-btn--arrow" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
						Book a Free Consultation
						<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</a>
				</div>
			</div>
		</div>

		<ol class="jd-outcomes__stairs">
			<?php foreach ( $jodyn_outcomes as $i => $outcome ) : ?>
				<li class="jd-outcomes__step" style="--step: <?php echo (int) $i; ?>">
					<div class="jd-outcomes__block">
						<span class="jd-outcomes__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<h3><?php echo esc_html( $outcome[0] ); ?></h3>
						<p><?php echo esc_html( $outcome[1] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>

	</div>
</section>
