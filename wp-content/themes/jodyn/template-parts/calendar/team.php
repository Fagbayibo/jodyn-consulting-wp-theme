<?php
/**
 * Training calendar · Training a Team? (copy section 5): group and in-house training.
 *
 * White, centred: heading and text, then two halves split by a line, the group discount
 * (a big forest badge with the percentage) and in-house delivery (a lime building icon), then
 * Request In-House Training (to the Contact form with Training selected). The footer's lime
 * card overlaps the bottom.
 * The discount and the minimum group size are set in Customize → Training calendar; until the
 * client fills them in they show as [X] and [three].
 * Copy: content/pages/training-calendar.md, section 5 (approved).
 *
 * @package JoDyn
 */

$jodyn_discount = trim( (string) get_theme_mod( 'jodyn_group_discount', '' ) );
$jodyn_discount = '' === $jodyn_discount ? '[X]' : rtrim( $jodyn_discount, '%' );
$jodyn_group    = trim( (string) get_theme_mod( 'jodyn_group_size', '' ) );
$jodyn_group    = '' === $jodyn_group ? '[three]' : $jodyn_group;
?>
<section class="jd-cal-team" aria-labelledby="jd-cal-team-title" data-jd-particles="light" data-jd-keep-out=".jd-cal-team__copy > *, .jd-cal-team__split, .jd-cal-team__act">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-cal-team__copy">
			<h2 class="jd-cal-team__title" id="jd-cal-team-title" data-jd-lines>Training a <em>Team?</em></h2>
			<p class="jd-cal-team__text" data-jd-rise="0.1">Save with group registration, or bring any programme in-house. We can deliver it exclusively for your organisation, adapted to your industry and scheduled around your operations.</p>
		</div>

		<ul class="jd-cal-team__split" data-jd-stagger>
			<li class="jd-cal-team__half">
				<span class="jd-cal-team__big" aria-hidden="true"><?php echo esc_html( $jodyn_discount ); ?>%</span>
				<h3>Group discount</h3>
				<p><?php echo esc_html( $jodyn_discount ); ?>% off when <?php echo esc_html( $jodyn_group ); ?> or more participants from the same organisation register for the same programme.</p>
			</li>
			<li class="jd-cal-team__half">
				<span class="jd-cal-team__big jd-cal-team__big--icon" aria-hidden="true"><?php echo jodyn_icon( 'building' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<h3>In-house delivery</h3>
				<p>Any programme, at your premises, a venue of your choice or online.</p>
			</li>
		</ul>

		<div class="jd-cal-team__act" data-jd-rise="0.2">
			<a class="jd-btn jd-btn--arrow" href="<?php echo esc_url( home_url( '/contact-us/?enquiry=training' ) ); ?>">
				Request In-House Training
				<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</a>
		</div>
	</div>
</section>
