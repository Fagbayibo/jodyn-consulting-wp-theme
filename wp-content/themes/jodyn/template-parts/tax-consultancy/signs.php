<?php
/**
 * Tax Consultancy · 2. When to talk to a tax adviser.
 *
 * White. Heading beside the intro, then the six signs as a 3 × 2 grid of soft green cards, each
 * with a forest icon and its number; a card turns lime on hover. The closing line below.
 * Two columns on tablets, one on phones.
 * Copy: content/pages/tax-consultancy.md, section 2 (approved).
 *
 * @package JoDyn
 */

$jodyn_svg   = static function ( $d ) {
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
};
$jodyn_signs = array(
	array( 'Deadlines that creep up', 'Returns are filed late or in a rush, and penalties keep appearing.', '<circle cx="12" cy="13" r="8"/><path d="M12 9v4l2.5 2.5M9 2h6"/>' ),
	array( 'Unsure what you owe', 'You are not certain which taxes apply to you, or whether you are paying too much or too little.', '<circle cx="12" cy="12" r="9"/><path d="M9.5 9.5a2.5 2.5 0 1 1 3.5 2.3c-.7.3-1 .9-1 1.6M12 17h.01"/>' ),
	array( 'Growth that changes the picture', 'New staff, new locations, new products or new investors bring new tax obligations.', '<path d="M4 19V5M4 19h16"/><path d="M7 15l4-4 3 3 5-6"/><path d="M15 8h4v4"/>' ),
	array( 'A letter from the tax authority', 'An assessment, audit notice or enquiry has arrived and you need to respond well.', '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>' ),
	array( 'Changing rules', 'Tax laws and rates change, and you need to know what that means for your organisation.', '<path d="M20 12a8 8 0 1 1-2.3-5.6"/><path d="M20 4v5h-5"/>' ),
	array( 'Big decisions ahead', 'A restructure, acquisition, new contract or investment has tax consequences you want to understand first.', '<path d="M12 21v-9M12 12l-6-6M12 12l6-6"/><path d="M6 10V6h4M18 10V6h-4"/>' ),
);
?>
<section class="jd-tx-signs" aria-labelledby="jd-tx-signs-title" data-jd-particles="light" data-jd-keep-out=".jd-tx-signs__head > *, .jd-tx-signs__grid">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-tx-signs__head">
			<div>
				<p class="jd-eyebrow" data-jd-rise>Sound familiar?</p>
				<h2 class="jd-tx-signs__title" id="jd-tx-signs-title" data-jd-lines>When to Talk to a <em>Tax Adviser</em></h2>
			</div>
			<p class="jd-tx-signs__intro" data-jd-rise="0.1">Tax problems rarely announce themselves. They build up quietly until a deadline, a penalty or a letter from the tax authority brings them to light. If any of these sound familiar, it is time for expert support.</p>
		</div>

		<ol class="jd-tx-signs__grid" data-jd-stagger>
			<?php foreach ( $jodyn_signs as $jodyn_i => $jodyn_sign ) : ?>
				<li class="jd-tx-signs__card">
					<span class="jd-tx-signs__ico"><?php echo $jodyn_svg( $jodyn_sign[2] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup above. ?></span>
					<span class="jd-tx-signs__n" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $jodyn_i + 1 ) ); ?></span>
					<h3><?php echo esc_html( $jodyn_sign[0] ); ?></h3>
					<p><?php echo esc_html( $jodyn_sign[1] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>

		<p class="jd-tx-signs__close" data-jd-rise>Getting advice early costs far less than fixing problems after the fact.</p>
	</div>
</section>
