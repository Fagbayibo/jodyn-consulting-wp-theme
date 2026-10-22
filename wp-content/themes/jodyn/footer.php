<?php
/**
 * Site footer.
 *
 * Opens with the closing call to action as a lime card that lifts over the edge of the
 * last section, then the forest footer: brand, quick links, contact, social, legal.
 * Copy: content/pages/home.md, sections 12 and 13 (approved).
 * Contact details and social links come from Customizer → Contact & social; empty ones are hidden.
 * Quick links come from the "Footer navigation" menu, with the approved list as the fallback.
 *
 * @package JoDyn
 */

$jodyn_contact = jodyn_contact();
$jodyn_social  = jodyn_social_links();
$jodyn_privacy = get_privacy_policy_url();
// The call to action points at the contact page, so it isn't repeated there.
$jodyn_has_cta = ! is_page( 'contact-us' );
?>
<footer class="jd-footer<?php echo $jodyn_has_cta ? ' has-cta' : ''; ?>" data-jd-particles="forest" data-jd-keep-out=".jd-footer__cta, .jd-footer__cols > *, .jd-footer__bottom">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<?php if ( $jodyn_has_cta ) : ?>
			<section class="jd-footer__cta" aria-labelledby="jd-footer-cta-title" data-jd-reveal>
				<div class="jd-footer__cta-copy">
					<h2 class="jd-footer__cta-title" id="jd-footer-cta-title" data-jd-lines>How Can We Help You <em>Grow?</em></h2>
					<p class="jd-footer__cta-text">Whether you need strategic advice, an independent review of your systems, support with your taxes or a training programme for your team, our advisors are ready to listen. Tell us about your goals, and we will recommend the right next step.</p>
				</div>
				<div class="jd-footer__cta-act">
					<div class="jd-footer__btns">
						<a class="jd-btn jd-btn--arrow" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
							Talk to an Advisor
							<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</a>
						<a class="jd-btn jd-btn--secondary jd-btn--arrow" href="<?php echo esc_url( home_url( '/training/calendar/' ) ); ?>">
							View Training Calendar
							<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</a>
					</div>
					<?php if ( $jodyn_contact['tel'] || $jodyn_contact['email'] ) : ?>
						<p class="jd-footer__line">
							Prefer to talk now?
							<?php if ( $jodyn_contact['tel'] ) : ?>
								Call <a href="tel:<?php echo esc_attr( $jodyn_contact['tel'] ); ?>"><?php echo esc_html( $jodyn_contact['phone'] ); ?></a><?php echo $jodyn_contact['email'] ? ' or' : ''; ?>
							<?php endif; ?>
							<?php if ( $jodyn_contact['email'] ) : ?>
								<?php echo $jodyn_contact['tel'] ? 'email' : 'Email'; ?> <a href="mailto:<?php echo esc_attr( antispambot( $jodyn_contact['email'] ) ); ?>"><?php echo esc_html( antispambot( $jodyn_contact['email'] ) ); ?></a>
							<?php endif; ?>
						</p>
					<?php endif; ?>
				</div>
				<span class="jd-footer__mark" aria-hidden="true"><i></i><i></i><i></i></span>
			</section>
		<?php endif; ?>

		<div class="jd-footer__cols">
			<div class="jd-footer__brand" data-jd-rise>
				<a class="jd-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo-white.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" width="687" height="483" loading="lazy">
				</a>
				<p>JoDyn Advisory and Consulting helps organisations across Nigeria grow through expert advisory, systems audit, tax consultancy and practical training in strategy, leadership and workforce development.</p>
			</div>

			<nav class="jd-footer__col" aria-labelledby="jd-footer-links" data-jd-rise="0.1">
				<h2 class="jd-footer__h" id="jd-footer-links"><?php esc_html_e( 'Quick links', 'jodyn' ); ?></h2>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'depth'          => 1,
						'menu_class'     => 'jd-footer__links',
						'fallback_cb'    => 'jodyn_footer_nav_fallback',
					)
				);
				?>
			</nav>

			<?php if ( $jodyn_contact['address'] || $jodyn_contact['phone'] || $jodyn_contact['email'] ) : ?>
				<div class="jd-footer__col" data-jd-rise="0.2">
					<h2 class="jd-footer__h"><?php esc_html_e( 'Contact', 'jodyn' ); ?></h2>
					<ul class="jd-footer__contact">
						<?php if ( $jodyn_contact['address'] ) : ?>
							<li><?php echo jodyn_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><address><?php echo nl2br( esc_html( $jodyn_contact['address'] ) ); ?></address></li>
						<?php endif; ?>
						<?php if ( $jodyn_contact['phone'] ) : ?>
							<li><?php echo jodyn_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php if ( $jodyn_contact['tel'] ) : ?>
									<a href="tel:<?php echo esc_attr( $jodyn_contact['tel'] ); ?>"><?php echo esc_html( $jodyn_contact['phone'] ); ?></a>
								<?php else : ?>
									<span><?php echo esc_html( $jodyn_contact['phone'] ); ?></span>
								<?php endif; ?>
							</li>
						<?php endif; ?>
						<?php if ( $jodyn_contact['email'] ) : ?>
							<li><?php echo jodyn_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><a href="mailto:<?php echo esc_attr( antispambot( $jodyn_contact['email'] ) ); ?>"><?php echo esc_html( antispambot( $jodyn_contact['email'] ) ); ?></a></li>
						<?php endif; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if ( $jodyn_social ) : ?>
				<div class="jd-footer__col" data-jd-rise="0.3">
					<h2 class="jd-footer__h"><?php esc_html_e( 'Follow us', 'jodyn' ); ?></h2>
					<ul class="jd-footer__social">
						<?php foreach ( $jodyn_social as $jodyn_network ) : ?>
							<li>
								<a href="<?php echo esc_url( $jodyn_network['url'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $jodyn_network['label'] ); ?>">
									<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><?php echo $jodyn_network['path']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup from jodyn_social_networks(). ?></svg>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>

		<div class="jd-footer__bottom">
			<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> JoDyn Advisory and Consulting. All rights reserved.</p>
			<ul>
				<li><a href="<?php echo esc_url( $jodyn_privacy ? $jodyn_privacy : home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a></li>
				<li><a href="<?php echo esc_url( home_url( '/cookie-policy/' ) ); ?>">Cookie Policy</a></li>
			</ul>
			<a class="jd-footer__top" href="#top" aria-label="<?php esc_attr_e( 'Back to top', 'jodyn' ); ?>"><?php echo jodyn_icon( 'arrow-up' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
