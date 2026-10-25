<?php
/**
 * Systems Audit · 2. Signs you need an audit.
 *
 * Soft green, centred heading and intro. A portrait in an arch in the middle with three warning
 * signs on each side, each on a white card with a forest icon; the closing line below. On
 * tablets the photo goes on top and the signs follow in two columns, then one on phones.
 * Copy: content/pages/systems-audit.md, section 2 (approved).
 *
 * @package JoDyn
 */

$jodyn_svg   = static function ( $d ) {
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
};
$jodyn_signs = array(
	array( 'Numbers you cannot fully trust', 'Reports do not match, reconciliations take too long and nobody is sure which figure is right.', '<path d="M4 19V5M4 19h16"/><path d="M8 15l3-4 3 2 4-6"/>' ),
	array( 'Growth without controls', 'The business has grown, but approvals, checks and segregation of duties have not kept up.', '<rect x="4" y="13" width="5" height="7" rx="1"/><rect x="10" y="9" width="5" height="11" rx="1"/><rect x="16" y="4" width="4" height="16" rx="1"/>' ),
	array( 'Work that depends on a few people', 'Key tasks live in someone’s head or inbox, and everything stops when they are away.', '<circle cx="12" cy="8" r="3.5"/><path d="M5 20c1.2-3.6 4-5.5 7-5.5s5.8 1.9 7 5.5"/>' ),
	array( 'Systems that no longer fit', 'Software and spreadsheets were added over time, and now they overlap, break or leave data exposed.', '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/><path d="M9 9l2 2-2 2M13 13h2"/>' ),
	array( 'A loss, fraud or near miss', 'Something has already slipped through, and you need to know how and whether it could happen again.', '<path d="M12 3l9.5 17h-19z"/><path d="M12 10v4M12 17h.01"/>' ),
	array( 'A regulator, investor or lender is asking questions', 'You need evidence that your controls and governance are sound.', '<circle cx="11" cy="11" r="6.5"/><path d="M20.5 20.5l-4.9-4.9"/><path d="M8.5 11l1.8 1.8 3.4-3.6"/>' ),
);
?>
<section class="jd-sa-signs" aria-labelledby="jd-sa-signs-title" data-jd-particles="light" data-jd-keep-out=".jd-sa-signs__head > *, .jd-sa-signs__map">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-sa-signs__head">
			<p class="jd-eyebrow" data-jd-rise>Warning signs</p>
			<h2 class="jd-sa-signs__title" id="jd-sa-signs-title" data-jd-lines>When It Is Time for a <em>Systems Audit</em></h2>
			<p class="jd-sa-signs__intro" data-jd-rise="0.1">Most problems in systems and controls stay hidden until something goes wrong. If any of these sound familiar, an independent review will tell you where you stand.</p>
		</div>

		<div class="jd-sa-signs__map">
			<figure class="jd-sa-signs__photo" data-jd-reveal>
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/systems-audit/signs-review.webp' ); ?>" alt="" width="1000" height="1500" loading="lazy" decoding="async" data-jd-parallax>
			</figure>
			<ul class="jd-sa-signs__list" data-jd-stagger>
				<?php foreach ( $jodyn_signs as $jodyn_i => $jodyn_sign ) : ?>
					<li class="jd-sa-signs__sign is-<?php echo esc_attr( $jodyn_i + 1 ); ?>">
						<span class="jd-sa-signs__ico"><?php echo $jodyn_svg( $jodyn_sign[2] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup above. ?></span>
						<div>
							<h3><?php echo esc_html( $jodyn_sign[0] ); ?></h3>
							<p><?php echo esc_html( $jodyn_sign[1] ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<p class="jd-sa-signs__close" data-jd-rise>Finding these issues early costs far less than dealing with the consequences later.</p>
	</div>
</section>
