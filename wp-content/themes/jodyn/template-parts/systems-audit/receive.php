<?php
/**
 * Systems Audit · 5. What you receive.
 *
 * White. A photo of someone reading a report, with a forest chip ("Every finding rated High,
 * Medium or Low"), beside the heading, intro and the five points as a ticked list.
 * Copy: content/pages/systems-audit.md, section 5 (approved).
 *
 * @package JoDyn
 */

$jodyn_points = array(
	array( 'Rated by risk', 'Every finding is rated high, medium or low, so you know what to fix first.' ),
	array( 'Root causes, not only symptoms', 'We explain why each issue happens, so the fix lasts.' ),
	array( 'Practical recommendations', 'Clear actions, owners and timelines that fit your size and resources.' ),
	array( 'A summary for leadership', 'A short overview for the board or management alongside the detailed findings.' ),
	array( 'Independent and confidential', 'An objective view, handled with strict confidentiality.' ),
);
$jodyn_tick   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>';
?>
<section class="jd-sa-recv" aria-labelledby="jd-sa-recv-title" data-jd-particles="light" data-jd-keep-out=".jd-sa-recv__copy > *, .jd-sa-recv__photo">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container jd-sa-recv__grid">
		<figure class="jd-sa-recv__photo" data-jd-reveal>
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/systems-audit/report-reading.webp' ); ?>" alt="" width="1200" height="800" loading="lazy" decoding="async" data-jd-parallax>
			<figcaption class="jd-sa-recv__chip">
				<span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 20h16"/><rect x="5" y="12" width="3" height="6" rx="1"/><rect x="10.5" y="8" width="3" height="10" rx="1"/><rect x="16" y="4" width="3" height="14" rx="1"/></svg></span>
				<?php esc_html_e( 'Every finding rated High, Medium or Low', 'jodyn' ); ?>
			</figcaption>
		</figure>

		<div class="jd-sa-recv__copy">
			<p class="jd-eyebrow" data-jd-rise>The report</p>
			<h2 class="jd-sa-recv__title" id="jd-sa-recv-title" data-jd-lines>Findings You Can <em>Act On</em></h2>
			<p class="jd-sa-recv__intro" data-jd-rise="0.1">An audit is only useful if it leads to action. Our reports are written for decision-makers, not just auditors.</p>
			<ul class="jd-sa-recv__list" data-jd-stagger>
				<?php foreach ( $jodyn_points as $jodyn_point ) : ?>
					<li>
						<span class="jd-sa-recv__tick"><?php echo $jodyn_tick; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<div>
							<h3><?php echo esc_html( $jodyn_point[0] ); ?></h3>
							<p><?php echo esc_html( $jodyn_point[1] ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
