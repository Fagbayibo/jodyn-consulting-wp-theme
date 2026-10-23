<?php
/**
 * Training · 4. Training options.
 *
 * White. The four ways to train are tabs down the left; the chosen one fills a soft green
 * panel with a photo, its text, four ticked points and its button (tabs in
 * assets/js/training.js; arrow keys move between them). "Explore Online Programmes" opens the
 * programmes list in section 3 with Online selected.
 * Copy: content/pages/training.md, section 4 (approved).
 *
 * @package JoDyn
 */

$jodyn_img = get_template_directory_uri() . '/assets/img/training/';
// Key, name, icon, photo, object-position, text, points, button label, button path.
$jodyn_options = array(
	array(
		'open',
		'Open Programmes',
		'calendar',
		'opt-open.webp',
		'50% 40%',
		'Scheduled public programmes that professionals from different organisations can attend. A cost-effective way to build specific skills while learning alongside peers from other industries.',
		array( 'Fixed dates published on our training calendar', 'Ideal for individuals and small teams', 'Networking with professionals from other organisations', 'Available in person and online' ),
		'View Training Calendar',
		'/training/calendar/',
	),
	array(
		'inhouse',
		'Corporate In-House Training',
		'building',
		'opt-inhouse.webp',
		'50% 35%',
		'Programmes delivered exclusively for your organisation, at your premises, a venue of your choice or online. Content is adapted to your industry, culture and challenges, and scheduled around your operations.',
		array( 'Tailored content and case studies from your industry', 'Delivered on dates that suit your team', 'Cost-effective for groups', 'Confidential discussion of internal challenges' ),
		'Request In-House Training',
		'/contact-us/?enquiry=training',
	),
	array(
		'online',
		'Online Facilitation',
		'monitor',
		'opt-online.webp',
		'35% 40%',
		'Live, instructor-led virtual training that your team can join from anywhere. Our online sessions are designed to be just as engaging as in-person training, not a lecture on a screen.',
		array( 'Live facilitation on Zoom, Google Meet or Microsoft Teams', 'Interactive tools: polls, breakout rooms and group activities', 'Ideal for teams across multiple locations', 'Shorter sessions spread over days to reduce screen fatigue' ),
		'Explore Online Programmes',
		'/training/programmes/?format=online#programmes',
	),
	array(
		'bespoke',
		'Bespoke Programmes',
		'pen',
		'opt-bespoke.webp',
		'30% 40%',
		"Fully customised programmes designed from the ground up to address your organisation's specific goals and capability gaps. These can range from a single focused workshop to a structured development journey over several months.",
		array( 'Begins with a training needs analysis', 'Custom content, case studies and assessments', 'Can combine in-person, online and coaching', 'Includes evaluation and impact reporting' ),
		'Design a Custom Programme',
		'/contact-us/?enquiry=custom-training',
	),
);
?>
<section class="jd-tr-op" aria-labelledby="jd-tr-op-title" data-jd-particles="light" data-jd-keep-out=".jd-tr-op__head > *, .jd-tr-op__wrap">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-tr-op__head">
			<div>
				<p class="jd-eyebrow" data-jd-rise>How you can train with us</p>
				<h2 class="jd-tr-op__title" id="jd-tr-op-title" data-jd-lines>Training Options to Suit Every <em>Need</em></h2>
			</div>
			<p class="jd-tr-op__intro" data-jd-rise="0.1">Every organisation and professional has different needs. Choose the training option that works best for you.</p>
		</div>

		<div class="jd-tr-op__wrap" data-jd-tr-tabs data-jd-rise="0.15">
			<div class="jd-tr-op__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Training options', 'jodyn' ); ?>" aria-orientation="vertical">
				<?php foreach ( $jodyn_options as $jodyn_i => $jodyn_opt ) : ?>
					<button class="jd-tr-op__tab" type="button" role="tab" id="jd-tr-op-tab-<?php echo esc_attr( $jodyn_opt[0] ); ?>" aria-controls="jd-tr-op-panel-<?php echo esc_attr( $jodyn_opt[0] ); ?>" aria-selected="<?php echo 0 === $jodyn_i ? 'true' : 'false'; ?>" tabindex="<?php echo 0 === $jodyn_i ? '0' : '-1'; ?>">
						<span class="jd-tr-op__ic"><?php echo jodyn_icon( $jodyn_opt[2] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span><?php echo esc_html( $jodyn_opt[1] ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="jd-tr-op__panels">
				<?php foreach ( $jodyn_options as $jodyn_i => $jodyn_opt ) : ?>
					<div class="jd-tr-op__panel" role="tabpanel" id="jd-tr-op-panel-<?php echo esc_attr( $jodyn_opt[0] ); ?>" aria-labelledby="jd-tr-op-tab-<?php echo esc_attr( $jodyn_opt[0] ); ?>"<?php echo 0 === $jodyn_i ? '' : ' hidden'; ?>>
						<figure class="jd-tr-op__ph">
							<img src="<?php echo esc_url( $jodyn_img . $jodyn_opt[3] ); ?>" alt="" width="1400" height="933" loading="lazy" decoding="async" style="object-position: <?php echo esc_attr( $jodyn_opt[4] ); ?>">
						</figure>
						<div class="jd-tr-op__body">
							<h3 class="jd-tr-op__name"><?php echo esc_html( $jodyn_opt[1] ); ?></h3>
							<p class="jd-tr-op__text"><?php echo esc_html( $jodyn_opt[5] ); ?></p>
							<ul class="jd-tr-op__ticks">
								<?php foreach ( $jodyn_opt[6] as $jodyn_point ) : ?>
									<li><span class="jd-tr-op__tick"><?php echo jodyn_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php echo esc_html( $jodyn_point ); ?></li>
								<?php endforeach; ?>
							</ul>
							<a class="jd-btn jd-btn--arrow" href="<?php echo esc_url( home_url( $jodyn_opt[8] ) ); ?>"<?php echo 'online' === $jodyn_opt[0] ? ' data-jd-tr-format="online"' : ''; ?>>
								<?php echo esc_html( $jodyn_opt[7] ); ?>
								<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
