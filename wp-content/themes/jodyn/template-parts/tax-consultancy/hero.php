<?php
/**
 * Tax Consultancy · 1. Hero.
 *
 * A full-bleed photo under the see-through header with a forest fade (it settles in and drifts
 * on scroll: assets/js/tax-consultancy.js). Copy and the two buttons on the left; the four tax
 * services run along the bottom as a numbered row.
 * Copy: content/pages/tax-consultancy.md, section 1 (approved).
 *
 * @package JoDyn
 */

$jodyn_tax_services = array( 'Tax Compliance & Filing', 'Tax Planning & Advisory', 'Tax Health Checks', 'Support with Tax Authorities' );
?>
<section class="jd-tx-hero" aria-labelledby="jd-tx-title">
	<div class="jd-tx-hero__media" aria-hidden="true">
		<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/tax-consultancy/hero-desk.webp' ); ?>" alt="" width="1800" height="1200" decoding="async" fetchpriority="high" data-jd-tx-img>
	</div>

	<div class="jd-container jd-tx-hero__inner">
		<div class="jd-tx-hero__copy">
			<p class="jd-eyebrow jd-eyebrow--inverse" data-jd-rise>Tax consultancy</p>
			<h1 class="jd-tx-hero__title" id="jd-tx-title" data-jd-lines>Stay Compliant, Avoid Costly <em>Surprises</em></h1>
			<p class="jd-tx-hero__text" data-jd-rise="0.15">Tax rules are complex and the cost of getting them wrong is high. We help you meet every obligation on time, manage your tax position within the law, and handle reviews and enquiries with confidence.</p>
			<div class="jd-tx-hero__btns" data-jd-rise="0.25">
				<a class="jd-btn jd-btn--lg jd-btn--accent jd-btn--arrow" href="<?php echo esc_url( home_url( '/contact-us/?enquiry=tax' ) ); ?>">
					Talk to a Tax Adviser
					<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</a>
				<a class="jd-btn jd-btn--lg jd-btn--inverse jd-tx-hero__jump" href="#services">
					See How We Help
					<?php echo jodyn_icon( 'arrow-down' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</div>
		</div>

		<ol class="jd-tx-hero__areas" aria-label="<?php esc_attr_e( 'Our tax services', 'jodyn' ); ?>" data-jd-stagger>
			<?php foreach ( $jodyn_tax_services as $jodyn_i => $jodyn_service ) : ?>
				<li><span><?php echo esc_html( sprintf( '%02d', $jodyn_i + 1 ) ); ?></span><?php echo esc_html( $jodyn_service ); ?></li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
