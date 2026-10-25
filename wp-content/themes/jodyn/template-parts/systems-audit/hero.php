<?php
/**
 * Systems Audit · 1. Hero.
 *
 * White, solid header. Copy and the two buttons on the left; on the right a photo with a forest
 * card of sample findings, each rated by risk (the report idea from section 5). The findings
 * are illustrative, and the card says so.
 * Copy: content/pages/systems-audit.md, section 1 (approved).
 *
 * @package JoDyn
 */

// Illustrative findings: label => risk (high, medium, low).
$jodyn_findings = array(
	'Approval limits not enforced' => 'high',
	'No offsite data backup'       => 'high',
	'Duplicate supplier records'   => 'medium',
	'Policy last updated 2019'     => 'low',
);
$jodyn_risks    = array(
	'high'   => __( 'High', 'jodyn' ),
	'medium' => __( 'Medium', 'jodyn' ),
	'low'    => __( 'Low', 'jodyn' ),
);
?>
<section class="jd-sa-hero" aria-labelledby="jd-sa-title" data-jd-particles="light" data-jd-keep-out=".jd-sa-hero__copy > *, .jd-sa-hero__visual">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container jd-sa-hero__grid">
		<div class="jd-sa-hero__copy">
			<p class="jd-eyebrow" data-jd-rise>Systems audit</p>
			<h1 class="jd-sa-hero__title" id="jd-sa-title" data-jd-lines>See Clearly Where Your Systems Are <em>Exposed</em></h1>
			<p class="jd-sa-hero__text" data-jd-rise="0.15">Your organisation runs on its processes, controls and systems. We review them independently, show you where the risks and inefficiencies are, and give you a clear, practical plan to fix them before they become costly.</p>
			<div class="jd-sa-hero__btns" data-jd-rise="0.25">
				<a class="jd-btn jd-btn--lg jd-btn--arrow" href="<?php echo esc_url( home_url( '/contact-us/?enquiry=audit' ) ); ?>">
					Request an Audit
					<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</a>
				<a class="jd-btn jd-btn--lg jd-btn--secondary jd-sa-hero__jump" href="#services">
					See What We Review
					<?php echo jodyn_icon( 'arrow-down' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</div>
		</div>

		<div class="jd-sa-hero__visual">
			<figure class="jd-sa-hero__photo" data-jd-reveal>
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/systems-audit/hero-review.webp' ); ?>" alt="" width="1600" height="1066" decoding="async" fetchpriority="high" data-jd-parallax>
			</figure>
			<div class="jd-sa-hero__card" data-jd-sa-card>
				<p class="jd-sa-hero__card-h"><span><?php esc_html_e( 'Sample findings', 'jodyn' ); ?></span><span><?php esc_html_e( 'Risk', 'jodyn' ); ?></span></p>
				<ul>
					<?php foreach ( $jodyn_findings as $jodyn_finding => $jodyn_risk ) : ?>
						<li><span><?php echo esc_html( $jodyn_finding ); ?></span><b class="is-<?php echo esc_attr( $jodyn_risk ); ?>"><?php echo esc_html( $jodyn_risks[ $jodyn_risk ] ); ?></b></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</section>
