<?php
/**
 * Advisory · 4. How we work with you.
 *
 * White. Each step is a soft green row; its deliverable sits beside it as a slightly tilted
 * paper document with a lime "You receive" tab, which slides out as it scrolls into view
 * (assets/js/advisory.js). At rest (no JS, reduced motion) the documents are in place.
 * Copy: content/pages/advisory.md, section 4 (approved).
 *
 * @package JoDyn
 */

$jodyn_steps = array(
	array( 'Understand', 'search', 'We begin with conversations, interviews and a review of relevant information to understand your goals, challenges and context.', 'A clear summary of the problem and agreed objectives.' ),
	array( 'Design', 'pen', 'We analyse our findings and develop practical recommendations tailored to your organisation.', 'A findings report and a recommended solution or roadmap.' ),
	array( 'Deliver', 'tool', 'We support implementation alongside your team through workshops, tools, coaching and hands-on guidance.', 'The frameworks, templates and support needed to put the solution into action.' ),
	array( 'Measure & Sustain', 'trend', 'We review progress against agreed measures and recommend next steps to keep the momentum going.', 'A progress review and a plan for sustaining results.' ),
);
?>
<section class="jd-adv-work" aria-labelledby="jd-adv-work-title" data-jd-particles="light" data-jd-keep-out=".jd-adv-work__head > *, .jd-adv-work__rows">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-adv-work__head">
			<div>
				<p class="jd-eyebrow" data-jd-rise>Our approach</p>
				<h2 class="jd-adv-work__title" id="jd-adv-work-title" data-jd-lines>How We Work <em>With You</em></h2>
			</div>
			<p class="jd-adv-work__intro" data-jd-rise="0.1">Every advisory engagement follows a clear, structured process. You will always know what we are doing, why it matters and what you will receive at each stage.</p>
		</div>

		<ol class="jd-adv-work__rows">
			<?php foreach ( $jodyn_steps as $i => $jodyn_step ) : ?>
				<li class="jd-adv-work__row">
					<div class="jd-adv-work__copy" data-jd-rise>
						<span class="jd-adv-work__ic" aria-hidden="true"><?php echo jodyn_icon( $jodyn_step[1] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<div>
							<span class="jd-adv-work__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
							<h3><?php echo esc_html( $jodyn_step[0] ); ?></h3>
							<p><?php echo esc_html( $jodyn_step[2] ); ?></p>
						</div>
					</div>
					<div class="jd-adv-work__doc" data-jd-adv-doc>
						<span class="jd-adv-work__tab">You receive</span>
						<span class="jd-adv-work__doc-ic" aria-hidden="true"><?php echo jodyn_icon( 'doc' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<p><?php echo esc_html( $jodyn_step[3] ); ?></p>
						<span class="jd-adv-work__lines" aria-hidden="true"><i></i><i></i><i></i></span>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
