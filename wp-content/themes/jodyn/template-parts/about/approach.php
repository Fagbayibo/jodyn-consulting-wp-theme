<?php
/**
 * About · 6. Our approach.
 *
 * Forest. The five beliefs as a row of cards (lime, white, soft green) that arrive fanned out
 * like a hand of cards and straighten as you scroll in (assets/js/about.js, desktop only).
 * At rest (no JS, reduced motion, tablets and phones) the cards sit in a straight row/grid.
 * Copy: content/pages/about.md, section 6 (approved).
 *
 * @package JoDyn
 */

$jodyn_beliefs = array(
	array( 'People Come First', 'users', 'lime', 'Strategies succeed or fail through people. We design every solution with the people who will carry it out in mind.' ),
	array( 'Context Matters', 'pin', 'white', 'No two organisations are the same. We take time to understand your industry, culture and challenges before recommending anything.' ),
	array( 'Advice Must Be Practical', 'tool', 'tint', 'A recommendation is only valuable if it can be implemented. We focus on clear, realistic steps that fit your resources and timelines.' ),
	array( 'Learning Must Lead to Action', 'cap', 'white', 'Training only matters if behaviour changes. Our programmes use real scenarios, practical exercises and action plans so participants apply what they learn.' ),
	array( 'Progress Should Be Measured', 'target', 'lime', 'We agree on success measures at the start and track them through to the end, so you can see the value of your investment.' ),
);
?>
<section class="jd-approach" aria-labelledby="jd-approach-title" data-jd-particles="forest" data-jd-keep-out=".jd-approach__head > *, .jd-approach__fan, .jd-approach__foot">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-approach__head">
			<div>
				<p class="jd-eyebrow jd-eyebrow--inverse" data-jd-rise>Our philosophy</p>
				<h2 class="jd-approach__title" id="jd-approach-title" data-jd-lines>How We Think About <em>Growth</em></h2>
			</div>
			<p class="jd-approach__intro" data-jd-rise="0.1">Behind every engagement is a set of beliefs about what makes our work truly effective, and the same standard applies whether you work with us on a one-day workshop or a long-term transformation.</p>
		</div>

		<ol class="jd-approach__fan" data-jd-approach-fan>
			<?php foreach ( $jodyn_beliefs as $i => $jodyn_belief ) : ?>
				<li class="jd-approach__card jd-approach__card--<?php echo esc_attr( $jodyn_belief[2] ); ?>">
					<span class="jd-approach__ic" aria-hidden="true"><?php echo jodyn_icon( $jodyn_belief[1] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="jd-approach__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					<h3><?php echo esc_html( $jodyn_belief[0] ); ?></h3>
					<p><?php echo esc_html( $jodyn_belief[3] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>

		<div class="jd-approach__foot" data-jd-rise="0.2">
			<a class="jd-btn jd-btn--accent jd-btn--arrow" href="<?php echo esc_url( home_url( '/#how-we-work' ) ); ?>">
				See How We Work
				<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</a>
		</div>
	</div>
</section>
