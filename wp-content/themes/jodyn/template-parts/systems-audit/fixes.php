<?php
/**
 * Systems Audit · 6. Audit, advisory and training together.
 *
 * Forest. The heading, first paragraph and Explore Advisory Services on the left; on the right
 * the approved example as a chain: the systems audit finds weak approval processes, advisory
 * brings a new delegation of authority framework, training a short workshop for the managers
 * who use it. The links slide in one after another (assets/js/systems-audit.js).
 * Copy: content/pages/systems-audit.md, section 6 (approved).
 *
 * @package JoDyn
 */

$jodyn_svg   = static function ( $d ) {
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
};
$jodyn_chain = array(
	array( 'Systems audit', 'Finds weak approval processes', '<circle cx="11" cy="11" r="6.5"/><path d="M20.5 20.5l-4.9-4.9"/><path d="M8.5 11l1.8 1.8 3.4-3.6"/>' ),
	array( 'Advisory', 'A new delegation of authority framework', '<path d="M4 7h16M4 12h10M4 17h7"/><circle cx="18" cy="16" r="3"/>' ),
	array( 'Training', 'A short workshop for the managers who use it', '<path d="M3 8l9-4 9 4-9 4z"/><path d="M7 10v5c0 1.5 2.2 3 5 3s5-1.5 5-3v-5"/>' ),
);
?>
<section class="jd-sa-fix" aria-labelledby="jd-sa-fix-title" data-jd-particles="forest" data-jd-keep-out=".jd-sa-fix__copy > *, .jd-sa-fix__chain">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container jd-sa-fix__grid">
		<div class="jd-sa-fix__copy">
			<p class="jd-eyebrow jd-eyebrow--inverse" data-jd-rise>The JoDyn advantage</p>
			<h2 class="jd-sa-fix__title" id="jd-sa-fix-title" data-jd-lines>From Findings to <em>Fixes</em></h2>
			<p class="jd-sa-fix__text" data-jd-rise="0.1">Many audits end with a list of problems. Because JoDyn offers advisory and training as well, we can help you fix what the audit finds: redesigning a process, writing the policies that were missing or training your team on the new controls.</p>
			<a class="jd-btn jd-btn--accent jd-btn--arrow" href="<?php echo esc_url( home_url( '/services/advisory/' ) ); ?>" data-jd-rise="0.2">
				Explore Advisory Services
				<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</a>
		</div>

		<div class="jd-sa-fix__chain" data-jd-sa-chain>
			<p class="jd-sa-fix__eg">For example</p>
			<p class="screen-reader-text">A controls review that finds weak approval processes can be followed by a new delegation of authority framework and a short workshop for the managers who use it.</p>
			<ol aria-hidden="true">
				<?php foreach ( $jodyn_chain as $jodyn_link ) : ?>
					<li class="jd-sa-fix__link" data-jd-sa-link-step>
						<span class="jd-sa-fix__ico"><?php echo $jodyn_svg( $jodyn_link[2] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup above. ?></span>
						<div>
							<p class="jd-sa-fix__k"><?php echo esc_html( $jodyn_link[0] ); ?></p>
							<p class="jd-sa-fix__v"><?php echo esc_html( $jodyn_link[1] ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>
