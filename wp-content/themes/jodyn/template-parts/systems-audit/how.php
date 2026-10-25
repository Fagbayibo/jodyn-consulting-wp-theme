<?php
/**
 * Systems Audit · 4. How a systems audit works.
 *
 * Soft green. Heading and intro, then the four steps as white cards joined by lime dots: big
 * number, icon, step, text and a forest "You receive" tile. Two columns on tablets, one on phones.
 * Copy: content/pages/systems-audit.md, section 4 (approved).
 *
 * @package JoDyn
 */

$jodyn_svg   = static function ( $d ) {
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
};
$jodyn_steps = array(
	array( 'Scope', 'We agree what will be reviewed, the key risks and questions, the timeline and who we will speak to.', 'An agreed audit scope and plan', '<circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3.5"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/>' ),
	array( 'Review & Test', 'We interview your team, walk through your processes and test your controls and systems against the agreed scope.', 'Regular updates and early warning of anything urgent', '<circle cx="11" cy="11" r="6.5"/><path d="M20.5 20.5l-4.9-4.9"/><path d="M8.5 11l1.8 1.8 3.4-3.6"/>' ),
	array( 'Report', 'We present our findings, rated by risk, with clear and practical recommendations.', 'An audit report with a prioritised action plan', '<path d="M7 3h7l5 5v13H7z"/><path d="M14 3v5h5"/><path d="M10 13h6M10 17h4"/>' ),
	array( 'Follow Up', 'We check progress on the agreed actions and help you close the gaps.', 'A follow-up review confirming what has been fixed and what remains', '<path d="M20 12a8 8 0 1 1-2.3-5.6"/><path d="M20 4v5h-5"/><path d="M9 12l2 2 4-4"/>' ),
);
$jodyn_doc   = $jodyn_svg( '<path d="M7 3h7l5 5v13H7z"/><path d="M14 3v5h5"/>' );
?>
<section class="jd-sa-how" aria-labelledby="jd-sa-how-title" data-jd-particles="light" data-jd-keep-out=".jd-sa-how__head > *, .jd-sa-how__grid">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-sa-how__head">
			<div>
				<p class="jd-eyebrow" data-jd-rise>Our approach</p>
				<h2 class="jd-sa-how__title" id="jd-sa-how-title" data-jd-lines>How a Systems Audit <em>Works</em></h2>
			</div>
			<p class="jd-sa-how__intro" data-jd-rise="0.1">Every audit follows a clear, structured process with as little disruption to your team as possible. You will always know what we are reviewing and why.</p>
		</div>

		<ol class="jd-sa-how__grid" data-jd-stagger>
			<?php foreach ( $jodyn_steps as $jodyn_i => $jodyn_step ) : ?>
				<li class="jd-sa-how__card">
					<div class="jd-sa-how__top">
						<span class="jd-sa-how__n"><?php echo esc_html( sprintf( '%02d', $jodyn_i + 1 ) ); ?></span>
						<span class="jd-sa-how__ico"><?php echo $jodyn_svg( $jodyn_step[3] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup above. ?></span>
					</div>
					<h3><?php echo esc_html( $jodyn_step[0] ); ?></h3>
					<p class="jd-sa-how__text"><?php echo esc_html( $jodyn_step[1] ); ?></p>
					<div class="jd-sa-how__recv">
						<span class="jd-sa-how__doc"><?php echo $jodyn_doc; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<div>
							<p class="jd-sa-how__label">You receive</p>
							<p><?php echo esc_html( $jodyn_step[2] ); ?></p>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
