<?php
/**
 * Contact · Form, Office details and Follow us (copy sections 3, 4 and 5).
 *
 * Forest with particles, the colour change after the white hero. The form on the left on dark
 * fields (lime focus); on the right, behind a thin line, "Visit or Call Us" (address, phone,
 * WhatsApp, email and office hours from Customize → Contact & social, placeholders until filled in;
 * Chat on WhatsApp shows once the WhatsApp number is set) and "Stay Connected" (only the networks
 * filled in, hidden when there are none).
 * - ?enquiry=<key> and the hero's Send a message links select the enquiry type (assets/js/contact.js).
 * - Sent to /wp-json/jodyn/v1/contact (inc/enquiries.php); the thank-you message replaces the form.
 * Copy: content/pages/contact.md, sections 3 to 5 (approved).
 *
 * @package JoDyn
 */

$jodyn_c     = jodyn_contact();
$jodyn_reply = jodyn_response_time();
$jodyn_pick  = isset( $_GET['enquiry'] ) ? sanitize_key( $_GET['enquiry'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- only preselects a dropdown option.
$jodyn_types = jodyn_enquiry_types();
$jodyn_pick  = isset( $jodyn_types[ $jodyn_pick ] ) ? $jodyn_pick : 'general';
$jodyn_rows  = array(
	array( 'pin', __( 'Office address', 'jodyn' ), $jodyn_c['address'] ? nl2br( esc_html( $jodyn_c['address'] ) ) : esc_html( '[Street address, City, State, Nigeria]' ) ),
	array( 'phone', __( 'Phone', 'jodyn' ), $jodyn_c['tel'] ? '<a href="tel:' . esc_attr( $jodyn_c['tel'] ) . '">' . esc_html( $jodyn_c['phone'] ) . '</a>' : esc_html( '[phone number]' ) ),
	array( 'whatsapp', __( 'WhatsApp', 'jodyn' ), $jodyn_c['wa'] ? '<a href="https://wa.me/' . esc_attr( $jodyn_c['wa'] ) . '" target="_blank" rel="noopener">' . esc_html( $jodyn_c['whatsapp'] ) . '</a>' : esc_html( '[WhatsApp number]' ) ),
	array( 'mail', __( 'Email', 'jodyn' ), $jodyn_c['email'] ? '<a href="mailto:' . esc_attr( $jodyn_c['email'] ) . '">' . esc_html( $jodyn_c['email'] ) . '</a>' : esc_html( '[info@jodynconsulting.com]' ) ),
	array( 'clock', __( 'Office hours', 'jodyn' ), esc_html( $jodyn_c['hours'] ? $jodyn_c['hours'] : 'Monday to Friday, [9:00 am to 5:00 pm] (WAT)' ) ),
);
$jodyn_social = jodyn_social_links();
?>
<section class="jd-ct-form" id="contact-form" aria-labelledby="jd-ct-form-title" data-jd-particles="forest" data-jd-keep-out=".jd-ct-form__main > *, .jd-ct-form__side">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container jd-ct-form__grid">
		<div class="jd-ct-form__main">
			<div class="jd-ct-form__head">
				<p class="jd-eyebrow jd-eyebrow--inverse" data-jd-rise>Send us a message</p>
				<h2 class="jd-ct-form__title" id="jd-ct-form-title" data-jd-lines>How Can We <em>Help?</em></h2>
				<p class="jd-ct-form__text" data-jd-rise="0.1">Fill in the form below, and we will get back to you within <?php echo esc_html( $jodyn_reply ); ?>.</p>
			</div>

			<form class="jd-ct-form__form" method="post" novalidate data-jd-ct-form>
				<div class="jd-ct-form__fields">
					<label class="jd-ct-form__field"><span>First name <i aria-hidden="true">*</i></span><input type="text" name="first_name" autocomplete="given-name" maxlength="80" required></label>
					<label class="jd-ct-form__field"><span>Last name <i aria-hidden="true">*</i></span><input type="text" name="last_name" autocomplete="family-name" maxlength="80" required></label>
					<label class="jd-ct-form__field"><span>Email address <i aria-hidden="true">*</i></span><input type="email" name="email" autocomplete="email" required></label>
					<label class="jd-ct-form__field"><span>Phone number</span><input type="tel" name="phone" autocomplete="tel" maxlength="40"></label>
					<label class="jd-ct-form__field"><span>Organisation</span><input type="text" name="organisation" autocomplete="organization" maxlength="160"></label>
					<label class="jd-ct-form__field"><span>Job title</span><input type="text" name="job_title" autocomplete="organization-title" maxlength="160"></label>
					<label class="jd-ct-form__field is-full">
						<span>Enquiry type <i aria-hidden="true">*</i></span>
						<span class="jd-ct-form__select">
							<select name="enquiry" required>
								<?php foreach ( $jodyn_types as $jodyn_key => $jodyn_type ) : ?>
									<option value="<?php echo esc_attr( $jodyn_key ); ?>" <?php selected( $jodyn_pick, $jodyn_key ); ?>><?php echo esc_html( $jodyn_type[0] ); ?></option>
								<?php endforeach; ?>
							</select>
							<?php echo jodyn_icon( 'chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
					</label>
					<label class="jd-ct-form__field is-full"><span>Message <i aria-hidden="true">*</i></span><textarea name="message" rows="5" maxlength="5000" required></textarea></label>
				</div>
				<label class="jd-ct-form__check">
					<input type="checkbox" name="privacy" required>
					<span class="jd-ct-form__box" aria-hidden="true"><?php echo jodyn_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span>I have read the <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" target="_blank">Privacy Policy</a> and agree to JoDyn contacting me about my enquiry. <i aria-hidden="true">*</i></span>
				</label>
				<?php // Honeypot: hidden from people and assistive tech; bots fill it in. ?>
				<label class="jd-ct-form__trap" aria-hidden="true">Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
				<div class="jd-ct-form__foot">
					<button class="jd-btn jd-btn--accent jd-btn--arrow" type="submit">Send Message<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></button>
					<p class="jd-ct-form__status" role="status" aria-live="polite"></p>
				</div>
			</form>

			<div class="jd-ct-form__done" data-jd-ct-done hidden tabindex="-1">
				<span class="jd-ct-form__done-ico" aria-hidden="true"><?php echo jodyn_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<h3>Thank you for reaching out!</h3>
				<p>Your message has been received. A member of our team will get back to you within <?php echo esc_html( $jodyn_reply ); ?>. For urgent enquiries, call or WhatsApp us on <?php echo esc_html( $jodyn_c['phone'] ? $jodyn_c['phone'] : '[phone number]' ); ?>.</p>
			</div>
		</div>

		<div class="jd-ct-form__side" data-jd-rise="0.2">
			<div class="jd-ct-form__office">
				<h2 class="jd-ct-form__h">Visit or Call Us</h2>
				<ul class="jd-ct-form__rows">
					<?php foreach ( $jodyn_rows as $jodyn_row ) : ?>
						<li>
							<span class="jd-ct-form__oi" aria-hidden="true"><?php echo jodyn_icon( $jodyn_row[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span><b><?php echo esc_html( $jodyn_row[1] ); ?></b><span><?php echo $jodyn_row[2]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></span></span>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php if ( $jodyn_c['wa'] ) : ?>
					<a class="jd-btn jd-btn--accent jd-btn--arrow" href="<?php echo esc_url( 'https://wa.me/' . $jodyn_c['wa'] ); ?>" target="_blank" rel="noopener">Chat on WhatsApp<span class="jd-btn__disc"><?php echo jodyn_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></a>
				<?php endif; ?>
			</div>

			<?php if ( $jodyn_social ) : ?>
				<div class="jd-ct-form__follow">
					<h3>Stay Connected</h3>
					<p>Follow JoDyn for insights, programme updates and news.</p>
					<ul class="jd-ct-form__social">
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
	</div>
</section>
