<?php
/**
 * Training · The learning journey (copy section 5, the last section on the page).
 *
 * Forest. The heading stays in view on the left; on the right Before, During and After
 * Training sit on a vertical line that fills with lime as you scroll, each number lighting up
 * as the line reaches it (assets/js/training.js). What participants receive sits under After.
 * At rest (no JS, reduced motion) the line is full and every number is lit. The footer's lime
 * card lifts over the bottom of this section.
 * Copy: content/pages/training.md, section 5 (approved).
 *
 * @package JoDyn
 */

$jodyn_stages  = array(
	array( 'Before Training', "We clarify learning objectives with you, understand participants' roles and challenges, and, where needed, conduct a short pre-training assessment. This ensures the programme is relevant from the first minute." ),
	array( 'During Training', 'Participants learn through a balance of short expert input, real case studies, group discussions, role plays and practical exercises. Every session ends with participants applying the learning to their own work.' ),
	array( 'After Training', 'Each participant leaves with a personal action plan. We gather feedback, share a programme report with your organisation and remain available to support the application of learning back at work.' ),
);
$jodyn_receive = array(
	array( 'award', 'Certificate of Participation', 'Issued to every participant who completes the programme.' ),
	array( 'doc', 'Course Materials', 'Workbooks, slides and resources to refer back to after the programme.' ),
	array( 'tool', 'Practical Tools', 'Templates, checklists and frameworks to use at work.' ),
	array( 'pen', 'Personal Action Plan', 'A clear plan for applying the learning in your role.' ),
	array( 'users', 'Post-Training Support', 'Access to the facilitator for follow-up questions after the programme.' ),
	array( 'trend', 'Programme Report (for organisations)', 'A summary of attendance, feedback and recommendations for in-house programmes.' ),
);
?>
<section class="jd-tr-lj" aria-labelledby="jd-tr-lj-title" data-jd-particles="forest" data-jd-keep-out=".jd-tr-lj__head > *, .jd-tr-lj__right">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container jd-tr-lj__grid">
		<div class="jd-tr-lj__left">
			<div class="jd-tr-lj__head">
				<p class="jd-eyebrow jd-eyebrow--inverse" data-jd-rise>Our approach</p>
				<h2 class="jd-tr-lj__title" id="jd-tr-lj-title" data-jd-lines>A Learning Journey, Not Just a Training <em>Day</em></h2>
				<p class="jd-tr-lj__intro" data-jd-rise="0.1">Real learning happens before, during and after the training session. That is why every JoDyn programme follows a structured learning journey.</p>
			</div>
		</div>

		<div class="jd-tr-lj__right">
			<div class="jd-tr-lj__track" aria-hidden="true"><i data-jd-tr-lj-fill></i></div>
			<ol class="jd-tr-lj__stages" data-jd-tr-lj>
				<?php foreach ( $jodyn_stages as $jodyn_i => $jodyn_stage ) : ?>
					<li class="jd-tr-lj__stage" data-jd-tr-lj-stage>
						<span class="jd-tr-lj__node" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $jodyn_i + 1 ) ); ?></span>
						<div class="jd-tr-lj__body">
							<h3 class="jd-tr-lj__name"><?php echo esc_html( $jodyn_stage[0] ); ?></h3>
							<p class="jd-tr-lj__text"><?php echo esc_html( $jodyn_stage[1] ); ?></p>
							<?php if ( 2 === $jodyn_i ) : ?>
								<div class="jd-tr-lj__rec">
									<h4 class="jd-tr-lj__label">What you receive</h4>
									<ul class="jd-tr-lj__list" data-jd-stagger>
										<?php foreach ( $jodyn_receive as $jodyn_item ) : ?>
											<li>
												<span class="jd-tr-lj__ic"><?php echo jodyn_icon( $jodyn_item[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
												<div>
													<h5><?php echo esc_html( $jodyn_item[1] ); ?></h5>
													<p><?php echo esc_html( $jodyn_item[2] ); ?></p>
												</div>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>
