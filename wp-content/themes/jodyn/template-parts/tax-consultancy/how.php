<?php
/**
 * Tax Consultancy · 4. How we work with you.
 *
 * Soft green. Heading and intro, then the four steps as white cards joined by lime dots: big
 * number, icon, step, text and a forest "You receive" tile. Two columns on tablets, one on phones.
 * Copy: content/pages/tax-consultancy.md, section 4 (approved).
 *
 * @package JoDyn
 */

$jodyn_svg   = static function ( $d ) {
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
};
$jodyn_steps = array(
	array( 'Understand', 'We learn about your organisation, your taxes and your current position, and agree what you need.', 'A clear summary of your obligations and our scope', '<circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3.5"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/>' ),
	array( 'Review', 'We review your records, returns and payments and flag anything that needs attention.', 'A list of issues and opportunities, in plain language', '<circle cx="11" cy="11" r="6.5"/><path d="M20.5 20.5l-4.9-4.9"/><path d="M8.5 11l1.8 1.8 3.4-3.6"/>' ),
	array( 'Act', 'We prepare and file returns, put plans in place or respond to the tax authority on your behalf.', 'Completed filings, plans or responses, with copies for your records', '<path d="M7 3h7l5 5v13H7z"/><path d="M14 3v5h5"/><path d="M10 13h6M10 17h4"/>' ),
	array( 'Stay Ahead', 'We keep track of deadlines and changes in the rules and tell you what they mean for you.', 'Reminders, updates and a contact who knows your business', '<path d="M20 12a8 8 0 1 1-2.3-5.6"/><path d="M20 4v5h-5"/><path d="M9 12l2 2 4-4"/>' ),
);
$jodyn_doc   = $jodyn_svg( '<path d="M7 3h7l5 5v13H7z"/><path d="M14 3v5h5"/>' );
?>
<section class="jd-tx-how" aria-labelledby="jd-tx-how-title" data-jd-particles="light" data-jd-keep-out=".jd-tx-how__head > *, .jd-tx-how__grid">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-tx-how__head">
			<div>
				<p class="jd-eyebrow" data-jd-rise>Our approach</p>
				<h2 class="jd-tx-how__title" id="jd-tx-how-title" data-jd-lines>How We Work <em>With You</em></h2>
			</div>
			<p class="jd-tx-how__intro" data-jd-rise="0.1">Whether you need one return filed or ongoing support, our process is clear and you will always know what we need from you and when.</p>
		</div>

		<ol class="jd-tx-how__grid" data-jd-stagger>
			<?php foreach ( $jodyn_steps as $jodyn_i => $jodyn_step ) : ?>
				<li class="jd-tx-how__card">
					<div class="jd-tx-how__top">
						<span class="jd-tx-how__n"><?php echo esc_html( sprintf( '%02d', $jodyn_i + 1 ) ); ?></span>
						<span class="jd-tx-how__ico"><?php echo $jodyn_svg( $jodyn_step[3] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup above. ?></span>
					</div>
					<h3><?php echo esc_html( $jodyn_step[0] ); ?></h3>
					<p class="jd-tx-how__text"><?php echo esc_html( $jodyn_step[1] ); ?></p>
					<div class="jd-tx-how__recv">
						<span class="jd-tx-how__doc"><?php echo $jodyn_doc; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<div>
							<p class="jd-tx-how__label">You receive</p>
							<p><?php echo esc_html( $jodyn_step[2] ); ?></p>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
