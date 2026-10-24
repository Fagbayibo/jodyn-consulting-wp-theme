<?php
/**
 * Registration · Form and programme summary (copy section 3), and the on-screen confirmation
 * that replaces them after sending (section 4).
 *
 * White. The form is three short steps with a progress bar: Programme (programme details,
 * registration type) · Your details (participant, organisation-only) · Finish (additional
 * information, agreements, Complete Registration). Each step is checked before Continue.
 * The summary card sticks beside the form (below it on phones): title, date, format, venue and
 * fee; for organisations the total and, from the group size in Customize → Training calendar,
 * "Group discount applies".
 * - ?programme=<id> (every Register Now button) selects that programme.
 * - Only runs from today on that are not fully booked are offered; with none, a short message
 *   and links to the calendar and the contact form show instead.
 * - Sent to /wp-json/jodyn/v1/register (inc/registration.php); behaviour in assets/js/register.js.
 * Without JavaScript the form cannot be sent, so the help line below it stays the fallback.
 * Copy: content/pages/registration.md, sections 3 and 4 (approved).
 *
 * @package JoDyn
 */

$jodyn_runs = jodyn_registration_programmes();
$jodyn_team = jodyn_registration_email();
$jodyn_tel  = trim( (string) get_theme_mod( 'jodyn_contact_phone', '' ) );
$jodyn_tel  = $jodyn_tel ? $jodyn_tel : '[phone number]';
$jodyn_pick = isset( $_GET['programme'] ) ? absint( $_GET['programme'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- only preselects a dropdown option.

// The group discount (Customize → Training calendar): the size may be typed as a word ("three")
$jodyn_size  = strtolower( trim( (string) get_theme_mod( 'jodyn_group_size', '' ) ) );
$jodyn_words = array_flip( array( 'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten' ) );
$jodyn_size  = ctype_digit( $jodyn_size ) ? (int) $jodyn_size : ( isset( $jodyn_words[ $jodyn_size ] ) ? $jodyn_words[ $jodyn_size ] : 3 );
$jodyn_off   = rtrim( trim( (string) get_theme_mod( 'jodyn_group_discount', '' ) ), '%' );
$jodyn_off   = '' === $jodyn_off ? '[X]' : $jodyn_off;

$jodyn_data = array();
foreach ( $jodyn_runs as $jodyn_p ) {
	$jodyn_data[ $jodyn_p['id'] ] = array(
		'title'       => $jodyn_p['title'],
		'when'        => $jodyn_p['when'],
		'format'      => $jodyn_p['format_key'],
		'formatLabel' => $jodyn_p['format_name'],
		'venue'       => $jodyn_p['venue'] ? $jodyn_p['venue'] : ( 'online' === $jodyn_p['format_key'] ? __( 'Online', 'jodyn' ) : '—' ),
		'fee'         => $jodyn_p['fee_amount'],
		'feeText'     => $jodyn_p['fee'],
	);
}
$jodyn_pick = isset( $jodyn_data[ $jodyn_pick ] ) ? $jodyn_pick : 0;

$jodyn_help = static function () use ( $jodyn_team, $jodyn_tel ) {
	?>
	<p class="jd-reg-form__help"><?php echo jodyn_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span>Need help? Call or WhatsApp <a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $jodyn_tel ) ); ?>"><?php echo esc_html( $jodyn_tel ); ?></a> or email <?php if ( $jodyn_team ) : ?><a href="mailto:<?php echo esc_attr( $jodyn_team ); ?>"><?php echo esc_html( $jodyn_team ); ?></a><?php else : ?>[training@jodynconsulting.com]<?php endif; ?></span></p>
	<?php
};
?>
<section class="jd-reg-form" id="register" aria-label="<?php esc_attr_e( 'Registration form', 'jodyn' ); ?>" data-jd-reg>
	<div class="jd-container">

		<?php if ( ! $jodyn_runs ) : ?>
			<div class="jd-reg-form__none">
				<h2>No programmes are open for registration right now.</h2>
				<div class="jd-reg-form__none-acts">
					<a class="jd-btn jd-btn--arrow" href="<?php echo esc_url( home_url( '/training/calendar/' ) ); ?>">View Training Calendar<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></a>
					<a class="jd-btn jd-btn--secondary jd-btn--arrow" href="<?php echo esc_url( home_url( '/contact-us/?enquiry=training' ) ); ?>">Request a Programme<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></a>
				</div>
				<?php $jodyn_help(); ?>
			</div>
		<?php else : ?>

		<div class="jd-reg-form__grid" data-jd-reg-main>
			<div class="jd-reg-form__card">
				<form class="jd-reg-form__form" method="post" novalidate data-jd-reg-form>
					<script type="application/json" data-jd-reg-data><?php echo wp_json_encode( $jodyn_data ); ?></script>

					<ol class="jd-reg-form__prog">
						<li data-prog aria-current="step"><span>1</span><?php esc_html_e( 'Programme', 'jodyn' ); ?></li>
						<li data-prog><span>2</span><?php esc_html_e( 'Your details', 'jodyn' ); ?></li>
						<li data-prog><span>3</span><?php esc_html_e( 'Finish', 'jodyn' ); ?></li>
					</ol>

					<div class="jd-reg-form__pane" data-pane>
						<fieldset class="jd-reg-form__group">
							<legend>Programme details</legend>
							<div class="jd-reg-form__fields">
								<label class="jd-reg-form__field is-full">
									<span>Programme <i aria-hidden="true">*</i></span>
									<span class="jd-reg-form__select">
										<select name="programme" required>
											<option value=""><?php esc_html_e( 'Select a programme', 'jodyn' ); ?></option>
											<?php foreach ( $jodyn_runs as $jodyn_p ) : ?>
												<option value="<?php echo esc_attr( $jodyn_p['id'] ); ?>" <?php selected( $jodyn_pick, $jodyn_p['id'] ); ?>><?php echo esc_html( $jodyn_p['title'] . ' · ' . strtok( $jodyn_p['when'], '·' ) ); ?></option>
											<?php endforeach; ?>
										</select>
										<?php echo jodyn_icon( 'chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</span>
								</label>
								<div class="jd-reg-form__field is-full" data-reg-format hidden>
									<span id="jd-reg-fmt">Preferred format <i aria-hidden="true">*</i></span>
									<div class="jd-reg-form__pills" role="radiogroup" aria-labelledby="jd-reg-fmt">
										<label class="jd-reg-form__pill"><input type="radio" name="preferred_format" value="in-person" checked><span>In-person</span></label>
										<label class="jd-reg-form__pill"><input type="radio" name="preferred_format" value="online"><span>Online</span></label>
									</div>
								</div>
							</div>
						</fieldset>

						<fieldset class="jd-reg-form__group">
							<legend>Registration type</legend>
							<div class="jd-reg-form__field is-full">
								<span id="jd-reg-type">I am registering <i aria-hidden="true">*</i></span>
								<div class="jd-reg-form__cards" role="radiogroup" aria-labelledby="jd-reg-type">
									<label class="jd-reg-form__choice"><input type="radio" name="type" value="individual" checked><span>As an individual</span></label>
									<label class="jd-reg-form__choice"><input type="radio" name="type" value="organisation"><span>On behalf of an organisation</span></label>
								</div>
							</div>
						</fieldset>
					</div>

					<div class="jd-reg-form__pane" data-pane hidden>
						<fieldset class="jd-reg-form__group">
							<legend>Participant details</legend>
							<div class="jd-reg-form__fields">
								<label class="jd-reg-form__field"><span>Full name <i aria-hidden="true">*</i></span><input type="text" name="name" autocomplete="name" maxlength="120" required></label>
								<label class="jd-reg-form__field"><span>Email address <i aria-hidden="true">*</i></span><input type="email" name="email" autocomplete="email" required></label>
								<label class="jd-reg-form__field"><span>Phone number (WhatsApp preferred) <i aria-hidden="true">*</i></span><input type="tel" name="phone" autocomplete="tel" maxlength="40" required></label>
								<label class="jd-reg-form__field"><span>Job title</span><input type="text" name="job_title" autocomplete="organization-title" maxlength="160"></label>
								<label class="jd-reg-form__field is-full"><span>Organisation<i aria-hidden="true" data-reg-org-star hidden> *</i></span><input type="text" name="organisation" autocomplete="organization" maxlength="160"><small>Required if registering for an organisation</small></label>
							</div>
						</fieldset>

						<fieldset class="jd-reg-form__group" data-reg-org hidden>
							<legend>Organisation registrations only</legend>
							<div class="jd-reg-form__fields">
								<label class="jd-reg-form__field"><span>Number of participants <i aria-hidden="true">*</i></span><input type="number" name="participants" min="2" max="30" value="2" inputmode="numeric" required data-reg-count></label>
							</div>
							<div class="jd-reg-form__sub">
								<p class="jd-reg-form__subhead">Additional participants</p>
								<ol class="jd-reg-form__people" data-reg-people data-label="<?php /* translators: %d: participant number. */ esc_attr_e( 'Participant %d', 'jodyn' ); ?>" data-name="<?php esc_attr_e( 'Name', 'jodyn' ); ?>" data-email="<?php esc_attr_e( 'Email', 'jodyn' ); ?>" data-job="<?php esc_attr_e( 'Job title', 'jodyn' ); ?>"></ol>
							</div>
							<div class="jd-reg-form__sub">
								<p class="jd-reg-form__subhead">Contact person for invoicing</p>
								<div class="jd-reg-form__fields is-3">
									<label class="jd-reg-form__field"><span>Name</span><input type="text" name="invoice_name" autocomplete="name" maxlength="160"></label>
									<label class="jd-reg-form__field"><span>Email</span><input type="email" name="invoice_email" autocomplete="email"></label>
									<label class="jd-reg-form__field"><span>Phone</span><input type="tel" name="invoice_phone" autocomplete="tel" maxlength="40"></label>
								</div>
							</div>
						</fieldset>
					</div>

					<div class="jd-reg-form__pane" data-pane hidden>
						<fieldset class="jd-reg-form__group">
							<legend>Additional information</legend>
							<div class="jd-reg-form__fields">
								<label class="jd-reg-form__field is-full">
									<span>How did you hear about us?</span>
									<span class="jd-reg-form__select">
										<select name="source">
											<option value=""><?php esc_html_e( 'Choose one', 'jodyn' ); ?></option>
											<?php foreach ( jodyn_registration_sources() as $jodyn_key => $jodyn_label ) : ?>
												<option value="<?php echo esc_attr( $jodyn_key ); ?>"><?php echo esc_html( $jodyn_label ); ?></option>
											<?php endforeach; ?>
										</select>
										<?php echo jodyn_icon( 'chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</span>
								</label>
								<label class="jd-reg-form__field is-full"><span>Any special requirements?</span><textarea name="requirements" rows="3" maxlength="1000" placeholder="For example, accessibility or dietary needs"></textarea></label>
							</div>
						</fieldset>

						<fieldset class="jd-reg-form__group">
							<legend>Agreements</legend>
							<div class="jd-reg-form__checks">
								<label class="jd-reg-form__check">
									<input type="checkbox" name="terms" required>
									<span class="jd-reg-form__box" aria-hidden="true"><?php echo jodyn_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
									<span>I agree to the <a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>" target="_blank">Terms of Use</a> and the <a href="<?php echo esc_url( home_url( '/terms/#cancellations' ) ); ?>" target="_blank">cancellation and refund policy</a>, and I have read the <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" target="_blank">Privacy Policy</a>. <i aria-hidden="true">*</i></span>
								</label>
								<label class="jd-reg-form__check">
									<input type="checkbox" name="updates">
									<span class="jd-reg-form__box" aria-hidden="true"><?php echo jodyn_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
									<span>Send me updates on new programmes and insights.</span>
								</label>
							</div>
						</fieldset>
						<?php // Honeypot: hidden from people and assistive tech; bots fill it in. ?>
						<label class="jd-dl__trap" aria-hidden="true">Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
					</div>

					<div class="jd-reg-form__nav">
						<button type="button" class="jd-reg-form__back" data-go="-1" hidden><?php esc_html_e( 'Back', 'jodyn' ); ?></button>
						<button type="button" class="jd-btn jd-btn--arrow" data-go="1"><?php esc_html_e( 'Continue', 'jodyn' ); ?><span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></button>
						<button type="submit" class="jd-btn jd-btn--arrow" data-reg-submit hidden>Complete Registration<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></button>
					</div>
					<p class="jd-reg-form__status" role="status" aria-live="polite"></p>
					<?php $jodyn_help(); ?>
				</form>
			</div>

			<div class="jd-reg-form__side">
				<aside class="jd-reg-form__sum" aria-label="<?php esc_attr_e( 'Your programme', 'jodyn' ); ?>">
					<p class="jd-reg-form__sum-empty" data-sum-empty><?php esc_html_e( 'Choose a programme to see its details here.', 'jodyn' ); ?></p>
					<div data-sum-body hidden>
						<h2 class="jd-reg-form__sum-title" data-sum-title></h2>
						<ul class="jd-reg-form__sum-list">
							<li><?php echo jodyn_icon( 'calendar' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><b><?php esc_html_e( 'Date', 'jodyn' ); ?></b><span data-sum-when></span></span></li>
							<li><?php echo jodyn_icon( 'monitor' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><b><?php esc_html_e( 'Format', 'jodyn' ); ?></b><span data-sum-format></span></span></li>
							<li><?php echo jodyn_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><b><?php esc_html_e( 'Venue', 'jodyn' ); ?></b><span data-sum-venue></span></span></li>
							<li><?php echo jodyn_icon( 'tag' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><b><?php esc_html_e( 'Fee', 'jodyn' ); ?></b><span><span data-sum-fee></span> per participant</span></span></li>
						</ul>
						<p class="jd-reg-form__sum-total" data-sum-total hidden><span>Total for <b data-sum-n>2</b> participants</span><strong data-sum-sum></strong></p>
						<p class="jd-reg-form__sum-deal" data-sum-deal data-size="<?php echo (int) $jodyn_size; ?>" hidden><?php echo jodyn_icon( 'users' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>Group discount applies: <?php echo esc_html( $jodyn_off ); ?>% off</p>
					</div>
				</aside>
			</div>
		</div>

		<div class="jd-reg-form__done" data-jd-reg-done hidden tabindex="-1">
			<span class="jd-reg-form__done-ico" aria-hidden="true"><?php echo jodyn_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<h2>Thank you for registering!</h2>
			<p>Your registration for <b data-done-title></b> has been received. We have sent a confirmation email to <b data-done-email></b> with your payment details and next steps.</p>
			<p class="jd-reg-form__done-small">Can't find the email? Please check your spam or promotions folder, or contact us at <?php if ( $jodyn_team ) : ?><a href="mailto:<?php echo esc_attr( $jodyn_team ); ?>"><?php echo esc_html( $jodyn_team ); ?></a><?php else : ?>[training@jodynconsulting.com]<?php endif; ?>.</p>
			<a class="jd-btn jd-btn--arrow" href="<?php echo esc_url( home_url( '/training/calendar/' ) ); ?>">Back to Training Calendar<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></a>
		</div>

		<?php endif; ?>
	</div>
</section>
