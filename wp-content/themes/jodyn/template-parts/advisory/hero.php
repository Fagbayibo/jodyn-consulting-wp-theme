<?php
/**
 * Advisory · 1. Hero.
 *
 * White, solid header. Copy and the two buttons on the left; on the right a portrait beside a
 * forest index of the five advisory services, each linking to its block in section 3.
 * Copy: content/pages/advisory.md, section 1 (approved).
 *
 * @package JoDyn
 */

// Anchors match the service blocks in section 3 (template-parts/advisory/services.php).
$jodyn_advisory_index = array(
	'advisory-strategy'       => 'Strategy & Business Advisory',
	'advisory-transformation' => 'Organisational Transformation',
	'advisory-people'         => 'People & Culture Advisory',
	'advisory-performance'    => 'Performance Improvement',
	'advisory-learning'       => 'Learning & Development Advisory',
);
?>
<section class="jd-adv-hero" aria-labelledby="jd-adv-title" data-jd-particles="light" data-jd-keep-out=".jd-adv-hero__copy > *, .jd-adv-hero__side">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container jd-adv-hero__grid">
		<div class="jd-adv-hero__copy">
			<p class="jd-eyebrow" data-jd-rise>Advisory services</p>
			<h1 class="jd-adv-hero__title" id="jd-adv-title" data-jd-lines>Expert Advisory That Turns Strategy into <em>Results</em></h1>
			<p class="jd-adv-hero__text" data-jd-rise="0.15">We help leaders make better decisions, strengthen their organisations and deliver on their goals. From strategy and transformation to people, culture and performance, JoDyn provides practical advice backed by hands-on support.</p>
			<div class="jd-adv-hero__btns" data-jd-rise="0.25">
				<a class="jd-btn jd-btn--lg jd-btn--arrow" href="<?php echo esc_url( home_url( '/contact-us/?enquiry=advisory' ) ); ?>">
					Book a Consultation
					<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</a>
				<a class="jd-btn jd-btn--lg jd-btn--secondary jd-adv-hero__jump" href="#services">
					Explore Our Services
					<?php echo jodyn_icon( 'arrow-down' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</div>
		</div>

		<div class="jd-adv-hero__side">
			<figure class="jd-adv-hero__photo" data-jd-reveal>
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/advisory/hero-leader.webp' ); ?>" alt="" width="1000" height="1498" decoding="async" fetchpriority="high" data-jd-parallax>
			</figure>
			<nav class="jd-adv-hero__index" aria-label="<?php esc_attr_e( 'Advisory services', 'jodyn' ); ?>" data-jd-rise="0.3">
				<p class="jd-adv-hero__index-h">Our advisory services</p>
				<ol>
					<?php $i = 0; foreach ( $jodyn_advisory_index as $jodyn_anchor => $jodyn_name ) : ?>
						<li>
							<a href="#<?php echo esc_attr( $jodyn_anchor ); ?>">
								<span class="jd-adv-hero__n"><?php echo esc_html( sprintf( '%02d', ++$i ) ); ?></span>
								<span><?php echo esc_html( $jodyn_name ); ?></span>
								<?php echo jodyn_icon( 'arrow-down' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ol>
			</nav>
		</div>
	</div>
</section>
