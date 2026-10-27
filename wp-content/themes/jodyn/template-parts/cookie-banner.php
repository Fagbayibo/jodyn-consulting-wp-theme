<?php
/**
 * Cookie banner (option D): a lime card in the bottom-right corner with a forest cookie that
 * spins in. Accept and Essential only are the same size, so declining is as easy as accepting.
 * Shown by assets/js/consent.js until the visitor chooses; output by inc/consent.php.
 * Copy: content/pages/legal.md, Part 4 (approved). The "Cookies" label is new (noted there).
 *
 * @package JoDyn
 */

$jodyn_cookie = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 3a9 9 0 1 0 9 9 4 4 0 0 1-4-4 4 4 0 0 1-5-5z"/><circle cx="8.5" cy="10.5" r="1"/><circle cx="14" cy="15" r="1"/><circle cx="9" cy="16" r="1"/></svg>';
?>
<div class="jd-cookie" role="region" aria-labelledby="jd-cookie-label" data-jd-consent data-ga="<?php echo esc_attr( jodyn_ga4_id() ); ?>" hidden>
	<span class="jd-cookie__icon"><?php echo $jodyn_cookie; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup. ?></span>
	<p class="jd-cookie__label" id="jd-cookie-label"><?php esc_html_e( 'Cookies', 'jodyn' ); ?></p>
	<p class="jd-cookie__text">
		<?php esc_html_e( 'We use essential cookies to make this site work. With your permission, we would also like to use analytics cookies to understand how the site is used, so we can improve it.', 'jodyn' ); ?>
		<a href="<?php echo esc_url( jodyn_legal_url( 'cookie-policy' ) ); ?>"><?php esc_html_e( 'Cookie Policy', 'jodyn' ); ?></a>
	</p>
	<div class="jd-cookie__btns">
		<button type="button" class="jd-cookie__btn jd-cookie__btn--yes" data-jd-consent-choice="all"><?php esc_html_e( 'Accept', 'jodyn' ); ?></button>
		<button type="button" class="jd-cookie__btn jd-cookie__btn--no" data-jd-consent-choice="essential"><?php esc_html_e( 'Essential only', 'jodyn' ); ?></button>
	</div>
</div>
