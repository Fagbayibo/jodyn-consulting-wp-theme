<?php
/**
 * Training calendar · Download the calendar (copy section 4). The hero's button lands here (#download).
 *
 * Soft green, two columns:
 * - Left: a preview of the PDF (JoDyn logo, "Training Calendar" and the next six runs from
 *   Programmes, so it always matches the schedule), fanned over a lime and a forest sheet.
 * - Right: eyebrow, heading, text and the form: full name and email (required), organisation,
 *   job title, the optional updates box and Download Now.
 * The form posts like the pop-up on the Home and Training pages (assets/js/calendar-download.js,
 * inc/calendar-download.php): the request is saved under Calendar downloads, then the PDF downloads.
 * Copy: content/pages/training-calendar.md, section 4 (approved).
 *
 * @package JoDyn
 */

$jodyn_runs = array_map( 'jodyn_programme_data', jodyn_get_upcoming_programmes( 6 ) );
$jodyn_img  = get_template_directory_uri() . '/assets/img';
?>
<section class="jd-cal-dl" id="download" aria-labelledby="jd-cal-dl-title">
	<div class="jd-container jd-cal-dl__grid">
		<div class="jd-cal-dl__visual" aria-hidden="true" data-jd-cal-pdf>
			<div class="jd-cal-dl__sheet jd-cal-dl__sheet--back"></div>
			<div class="jd-cal-dl__sheet jd-cal-dl__sheet--mid"></div>
			<div class="jd-cal-dl__sheet">
				<div class="jd-cal-dl__sheet-top">
					<picture>
						<source type="image/webp" srcset="<?php echo esc_url( $jodyn_img . '/logo-color.webp' ); ?>">
						<img src="<?php echo esc_url( $jodyn_img . '/logo-color.png' ); ?>" alt="" width="480" height="333" loading="lazy">
					</picture>
					<span>PDF</span>
				</div>
				<p class="jd-cal-dl__sheet-title">Training Calendar</p>
				<?php if ( $jodyn_runs ) : ?>
					<ol class="jd-cal-dl__rows">
						<?php foreach ( $jodyn_runs as $jodyn_p ) : ?>
							<li><b><?php echo esc_html( $jodyn_p['day'] . ' ' . $jodyn_p['month'] ); ?></b><span><?php echo esc_html( $jodyn_p['title'] ); ?></span><em><?php echo esc_html( $jodyn_p['format'] ); ?></em></li>
						<?php endforeach; ?>
					</ol>
				<?php endif; ?>
			</div>
		</div>

		<div class="jd-cal-dl__side">
			<div class="jd-cal-dl__copy">
				<p class="jd-eyebrow" data-jd-rise>Plan ahead</p>
				<h2 class="jd-cal-dl__title" id="jd-cal-dl-title" data-jd-lines>Download Our Training <em>Calendar</em></h2>
				<p class="jd-cal-dl__text" data-jd-rise="0.1">Get the full schedule of upcoming programmes, with dates, formats and fees, in one easy-to-share PDF. Ideal for HR and L&amp;D teams planning the year's training.</p>
			</div>

			<form class="jd-cal-dl__form" method="post" novalidate data-jd-download-form data-jd-rise="0.15">
				<div class="jd-cal-dl__fields">
					<label class="jd-cal-dl__field">
						<span><?php esc_html_e( 'Full name', 'jodyn' ); ?> <i aria-hidden="true">*</i></span>
						<input type="text" name="name" autocomplete="name" maxlength="120" required>
					</label>
					<label class="jd-cal-dl__field">
						<span><?php esc_html_e( 'Email address', 'jodyn' ); ?> <i aria-hidden="true">*</i></span>
						<input type="email" name="email" autocomplete="email" required>
					</label>
					<label class="jd-cal-dl__field">
						<span><?php esc_html_e( 'Organisation', 'jodyn' ); ?></span>
						<input type="text" name="organisation" autocomplete="organization" maxlength="160">
					</label>
					<label class="jd-cal-dl__field">
						<span><?php esc_html_e( 'Job title', 'jodyn' ); ?></span>
						<input type="text" name="job_title" autocomplete="organization-title" maxlength="160">
					</label>
				</div>
				<label class="jd-cal-dl__check">
					<input type="checkbox" name="updates" value="1">
					<span class="jd-cal-dl__box" aria-hidden="true"><?php echo jodyn_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span><?php esc_html_e( 'Send me updates on new programmes and insights', 'jodyn' ); ?> <small><?php esc_html_e( '(optional)', 'jodyn' ); ?></small></span>
				</label>
				<?php // Honeypot: hidden from people and assistive tech; bots fill it in. ?>
				<label class="jd-dl__trap" aria-hidden="true">Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
				<div class="jd-cal-dl__foot">
					<button class="jd-btn jd-btn--accent jd-btn--arrow" type="submit">
						Download Now
						<span class="jd-btn__disc"><?php echo jodyn_icon( 'download' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</button>
					<p class="jd-cal-dl__status" role="status" aria-live="polite"></p>
				</div>
			</form>
		</div>
	</div>
</section>
