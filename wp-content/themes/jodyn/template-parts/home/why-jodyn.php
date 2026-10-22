<?php
/**
 * Home · 7. Why JoDyn.
 *
 * White section with brand particles. The logo sits in a forest circle at the centre with three
 * reasons on each side; on wide screens assets/js/why-jodyn.js draws connector lines from each
 * reason to the circle. Copy: content/pages/home.md, section 7 (approved).
 *
 * @package JoDyn
 */

$jodyn_reasons = array(
	array( 'sliders', 'Tailored Solutions', "No off-the-shelf templates. Every engagement is designed around your organisation's goals, culture and challenges." ),
	array( 'tool', 'Practical, Applied Expertise', 'Our recommendations, reviews and training are grounded in real workplace scenarios, so you get outcomes you can act on immediately.' ),
	array( 'award', 'Expert Advisors and Facilitators', 'Our team brings depth of knowledge, professional experience and a passion for helping people and organisations grow.' ),
	array( 'shield', 'Independent and Confidential', 'Our audit and tax work is objective and handled with strict confidentiality, so you get an honest picture you can trust.' ),
	array( 'target', 'Focus on Results', 'We measure success by the impact on your people, performance and bottom line, not by the number of sessions or reports delivered.' ),
	array( 'users', 'A True Partnership', 'We work alongside you, not just for you, and remain available for support and follow-up after every engagement.' ),
);

$jodyn_side = static function ( $reasons ) {
	foreach ( $reasons as $reason ) {
		printf(
			'<li class="jd-why__item"><span class="jd-why__icon">%1$s</span><div><h3>%2$s</h3><p>%3$s</p></div></li>',
			jodyn_icon( $reason[0] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_html( $reason[1] ),
			esc_html( $reason[2] )
		);
	}
};
?>
<section class="jd-why" id="why-jodyn" aria-labelledby="jd-why-title" data-jd-why
	data-jd-particles="light" data-jd-keep-out=".jd-why__head > *, .jd-why__side, .jd-why__core, .jd-why__cta">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">

		<div class="jd-why__head">
			<p class="jd-eyebrow" data-jd-rise>Why JoDyn</p>
			<h2 class="jd-why__title" id="jd-why-title" data-jd-lines>Why Organisations Choose <em>JoDyn</em></h2>
			<p class="jd-why__text" data-jd-rise="0.1">Choosing the right advisory partner is an important decision. Here is what you can expect when you work with us.</p>
		</div>

		<div class="jd-why__hub">
			<svg class="jd-why__lines" aria-hidden="true" focusable="false"></svg>
			<ul class="jd-why__side jd-why__side--start"><?php $jodyn_side( array_slice( $jodyn_reasons, 0, 3 ) ); ?></ul>
			<div class="jd-why__core" aria-hidden="true">
				<span class="jd-why__ring"></span>
				<span class="jd-why__ring"></span>
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo-white.svg' ); ?>" alt="" width="200" height="139" loading="lazy" decoding="async">
			</div>
			<ul class="jd-why__side jd-why__side--end"><?php $jodyn_side( array_slice( $jodyn_reasons, 3 ) ); ?></ul>
		</div>

		<div class="jd-why__cta" data-jd-rise>
			<a class="jd-btn jd-btn--arrow" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
				Talk to an Advisor
				<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</a>
		</div>

	</div>
</section>
