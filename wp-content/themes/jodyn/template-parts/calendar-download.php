<?php
/**
 * Training calendar download dialog: asks for a name and email (organisation, job title and
 * the updates box are optional), then the PDF downloads. Same fields as the form on the
 * calendar page (template-parts/calendar/download.php).
 *
 * Opened by a button with data-jd-download-open and aria-controls="jd-download". Styles in
 * assets/css/calendar-download.css, behaviour in assets/js/calendar-download.js, both loaded by
 * jodyn_enqueue_calendar_download(). The request is saved by inc/calendar-download.php.
 *
 * @package JoDyn
 */

?>
<dialog class="jd-dl" id="jd-download" aria-labelledby="jd-dl-title" data-jd-download>
	<form class="jd-dl__form" method="post" novalidate data-jd-download-form>
		<button class="jd-dl__close" type="button" data-jd-download-close>
			<span class="screen-reader-text"><?php esc_html_e( 'Close', 'jodyn' ); ?></span>
			<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
		</button>
		<span class="jd-dl__icon" aria-hidden="true"><?php echo jodyn_icon( 'download' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<h2 class="jd-dl__title" id="jd-dl-title">Download Training Calendar (PDF)</h2>
		<label class="jd-dl__field">
			<span><?php esc_html_e( 'Full name', 'jodyn' ); ?> <i aria-hidden="true">*</i></span>
			<input type="text" name="name" autocomplete="name" maxlength="120" required>
		</label>
		<label class="jd-dl__field">
			<span><?php esc_html_e( 'Email address', 'jodyn' ); ?> <i aria-hidden="true">*</i></span>
			<input type="email" name="email" autocomplete="email" required>
		</label>
		<div class="jd-dl__pair">
			<label class="jd-dl__field">
				<span><?php esc_html_e( 'Organisation', 'jodyn' ); ?></span>
				<input type="text" name="organisation" autocomplete="organization" maxlength="160">
			</label>
			<label class="jd-dl__field">
				<span><?php esc_html_e( 'Job title', 'jodyn' ); ?></span>
				<input type="text" name="job_title" autocomplete="organization-title" maxlength="160">
			</label>
		</div>
		<label class="jd-dl__check">
			<input type="checkbox" name="updates" value="1">
			<span class="jd-dl__box" aria-hidden="true"><?php echo jodyn_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<span><?php esc_html_e( 'Send me updates on new programmes and insights', 'jodyn' ); ?> <small><?php esc_html_e( '(optional)', 'jodyn' ); ?></small></span>
		</label>
		<?php // Honeypot: hidden from people and assistive tech; bots fill it in. ?>
		<label class="jd-dl__trap" aria-hidden="true">Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
		<p class="jd-dl__status" role="status" aria-live="polite"></p>
		<button class="jd-btn jd-btn--accent jd-btn--arrow jd-dl__submit" type="submit">
			<?php esc_html_e( 'Download (PDF)', 'jodyn' ); ?>
			<span class="jd-btn__disc"><?php echo jodyn_icon( 'download' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		</button>
	</form>
</dialog>
