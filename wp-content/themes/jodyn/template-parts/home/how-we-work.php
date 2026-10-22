<?php
/**
 * Home · 6. How we work.
 *
 * Soft green section. The four steps are tall photo panels: the open one shows its text,
 * the others are slim strips with a number and vertical title. Each panel's button opens it.
 * Copy: content/pages/home.md, section 6 (approved). Behaviour: assets/js/how-we-work.js.
 *
 * @package JoDyn
 */

$jodyn_img = get_template_directory_uri() . '/assets/img/home/';

$jodyn_steps = array(
	array( 'Understand', 'We start by listening. Through conversations, assessments and a close look at your context, we gain a clear picture of your goals, challenges and the outcomes that matter most to you.', 'work-understand.webp', 1400, 935 ),
	array( 'Design', 'We develop a tailored solution, whether advisory, audit, tax or training, or a combination. Every recommendation and programme is shaped around your organisation, not borrowed from a template.', 'work-design.webp', 933, 1400 ),
	array( 'Deliver', 'We implement with excellence, working closely with your leaders and teams at every stage. Our advisors, auditors and facilitators keep the work practical, engaging and focused on results.', 'work-deliver.webp', 1400, 933 ),
	array( 'Measure & Sustain', 'We track outcomes against the goals we set together, gather feedback and recommend next steps, so the progress continues long after the engagement ends.', 'work-measure.webp', 933, 1400 ),
);
?>
<section class="jd-work" id="how-we-work" aria-labelledby="jd-work-title" data-jd-work
	data-jd-particles="light" data-jd-keep-out=".jd-work__head > *, .jd-work__panels">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">

		<div class="jd-work__head">
			<div>
				<p class="jd-eyebrow" data-jd-rise>Our approach</p>
				<h2 class="jd-work__title" id="jd-work-title" data-jd-lines>A Clear, Proven <em>Process</em></h2>
			</div>
			<div class="jd-work__head-right">
				<p class="jd-work__text" data-jd-rise="0.1">Every engagement follows a structured approach designed to deliver real, measurable impact. You always know what is happening, why it matters and what results to expect.</p>
				<div data-jd-rise="0.2">
					<a class="jd-btn jd-btn--arrow" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
						Start a Conversation
						<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</a>
				</div>
			</div>
		</div>

		<ol class="jd-work__panels">
			<?php foreach ( $jodyn_steps as $i => $step ) : ?>
				<li class="jd-work__panel<?php echo 0 === $i ? ' is-active' : ''; ?>">
					<img src="<?php echo esc_url( $jodyn_img . $step[2] ); ?>" alt="" width="<?php echo (int) $step[3]; ?>" height="<?php echo (int) $step[4]; ?>" loading="lazy" decoding="async">
					<button class="jd-work__hit" type="button" aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-controls="jd-work-step-<?php echo (int) $i; ?>">
						<span class="screen-reader-text">
							<?php
							/* translators: %d: step number. */
							printf( esc_html__( 'Step %d:', 'jodyn' ), (int) $i + 1 );
							?>
						</span>
						<span class="jd-work__vtitle"><?php echo esc_html( $step[0] ); ?></span>
					</button>
					<span class="jd-work__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					<div class="jd-work__body" id="jd-work-step-<?php echo (int) $i; ?>">
						<h3><?php echo esc_html( $step[0] ); ?></h3>
						<p><?php echo esc_html( $step[1] ); ?></p>
						<span class="jd-work__progress" aria-hidden="true"><i></i></span>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>

	</div>
</section>
