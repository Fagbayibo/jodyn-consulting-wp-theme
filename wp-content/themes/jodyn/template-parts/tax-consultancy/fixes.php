<?php
/**
 * Tax Consultancy · 6. Tax, audit and advisory together.
 *
 * White. The heading, first paragraph and Explore Systems Audit on the left; on the right, on a
 * soft green panel, the approved example as a chain: a tax health check finds poor record
 * keeping, a systems audit reviews the finance processes, training equips the team that runs
 * them. The links slide in one after another (assets/js/tax-consultancy.js).
 * Copy: content/pages/tax-consultancy.md, section 6 (approved).
 *
 * @package JoDyn
 */

$jodyn_svg   = static function ( $d ) {
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
};
$jodyn_chain = array(
	array( 'Tax health check', 'Finds poor record keeping', '<path d="M7 3h7l5 5v13H7z"/><path d="M14 3v5h5"/><path d="M10 14l2 2 4-4"/>' ),
	array( 'Systems audit', 'A review of your finance processes', '<circle cx="11" cy="11" r="6.5"/><path d="M20.5 20.5l-4.9-4.9"/><path d="M8.5 11l1.8 1.8 3.4-3.6"/>' ),
	array( 'Training', 'Training for the team that runs them', '<path d="M3 8l9-4 9 4-9 4z"/><path d="M7 10v5c0 1.5 2.2 3 5 3s5-1.5 5-3v-5"/>' ),
);
?>
<section class="jd-tx-fix" aria-labelledby="jd-tx-fix-title" data-jd-particles="light" data-jd-keep-out=".jd-tx-fix__copy > *, .jd-tx-fix__chain">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container jd-tx-fix__grid">
		<div class="jd-tx-fix__copy">
			<p class="jd-eyebrow" data-jd-rise>The JoDyn advantage</p>
			<h2 class="jd-tx-fix__title" id="jd-tx-fix-title" data-jd-lines>Tax That Fits the <em>Bigger Picture</em></h2>
			<p class="jd-tx-fix__text" data-jd-rise="0.1">Tax issues often point to wider problems: weak records, unclear processes or decisions made without the full picture. Because JoDyn also offers systems audit and advisory, we can help fix the cause, not just the return.</p>
			<a class="jd-btn jd-btn--arrow" href="<?php echo esc_url( home_url( '/services/systems-audit/' ) ); ?>" data-jd-rise="0.2">
				Explore Systems Audit
				<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</a>
		</div>

		<div class="jd-tx-fix__chain" data-jd-tx-chain>
			<p class="jd-tx-fix__eg">For example</p>
			<p class="screen-reader-text">A tax health check that finds poor record keeping can be followed by a review of your finance processes and training for the team that runs them.</p>
			<ol aria-hidden="true">
				<?php foreach ( $jodyn_chain as $jodyn_link ) : ?>
					<li class="jd-tx-fix__link" data-jd-tx-chain-step>
						<span class="jd-tx-fix__ico"><?php echo $jodyn_svg( $jodyn_link[2] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup above. ?></span>
						<div>
							<p class="jd-tx-fix__k"><?php echo esc_html( $jodyn_link[0] ); ?></p>
							<p class="jd-tx-fix__v"><?php echo esc_html( $jodyn_link[1] ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>
