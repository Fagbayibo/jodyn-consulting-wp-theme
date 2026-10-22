<?php
/**
 * Home · 5. Upcoming training programmes.
 *
 * White section with brand particles. The soonest programme is the large forest feature,
 * the next two are compact cards, and a lime bar holds the calendar download and link.
 * Programmes come from the admin (Programmes); with none upcoming the section is skipped.
 * Copy: content/pages/home.md, section 5 (approved). Behaviour: assets/js/calendar-download.js.
 *
 * @package JoDyn
 */

$jodyn_programmes = array_map( 'jodyn_programme_data', jodyn_get_upcoming_programmes( 3 ) );
if ( ! $jodyn_programmes ) {
	return;
}
$jodyn_feature = array_shift( $jodyn_programmes );

$jodyn_meta = static function ( $p, $with_date ) {
	$items = array();
	if ( $with_date && $p['date_full'] ) {
		$items[] = array( 'calendar', $p['date_full'] );
	}
	$items[] = array( 'pin', $p['format'] );
	if ( $p['fee'] ) {
		$items[] = array( 'tag', $p['fee'] );
	}
	$out = '<ul class="jd-prog__meta">';
	foreach ( $items as $item ) {
		$out .= '<li>' . jodyn_icon( $item[0] ) . esc_html( $item[1] ) . '</li>';
	}
	return $out . '</ul>';
};

$jodyn_date = static function ( $p ) {
	if ( ! $p['day'] ) {
		return '';
	}
	return sprintf(
		'<time class="jd-prog__date" datetime="%1$s"><b>%2$s</b><span>%3$s</span></time>',
		esc_attr( $p['datetime'] ),
		esc_html( $p['day'] ),
		esc_html( $p['month'] )
	);
};

$jodyn_register = static function ( $p, $variant ) {
	return sprintf(
		'<a class="jd-btn %1$s jd-btn--arrow" href="%2$s">%3$s<span class="screen-reader-text">: %4$s</span><span class="jd-btn__disc">%5$s</span></a>',
		esc_attr( $variant ),
		esc_url( $p['url'] ),
		esc_html__( 'Register Now', 'jodyn' ),
		esc_html( $p['title'] ),
		jodyn_arrow_swap()
	);
};
?>
<section class="jd-prog" id="upcoming-programmes" aria-labelledby="jd-prog-title"
	data-jd-particles="light" data-jd-keep-out=".jd-prog__head > *, .jd-prog__grid, .jd-prog__bar">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">

		<div class="jd-prog__head">
			<div>
				<p class="jd-eyebrow" data-jd-rise>Training calendar</p>
				<h2 class="jd-prog__title" id="jd-prog-title" data-jd-lines>Upcoming Training Programmes</h2>
			</div>
			<p class="jd-prog__text" data-jd-rise="0.1">Invest in your growth and the growth of your team. Explore our upcoming programmes, choose the format that suits you and secure your seat. Spaces are limited to keep every session interactive.</p>
		</div>

		<div class="jd-prog__grid<?php echo $jodyn_programmes ? '' : ' is-single'; ?>">
			<article class="jd-prog__feature" data-jd-reveal>
				<?php
				if ( $jodyn_feature['image'] ) {
					echo wp_get_attachment_image(
						$jodyn_feature['image'],
						'full',
						false,
						array(
							'class'            => 'jd-prog__feature-img',
							'alt'              => '',
							'sizes'            => '(min-width: 1024px) 58vw, 100vw',
							'data-jd-parallax' => '8',
						)
					);
				}
				?>
				<div class="jd-prog__feature-body">
					<span class="jd-prog__next"><?php esc_html_e( 'Next programme', 'jodyn' ); ?></span>
					<?php echo $jodyn_date( $jodyn_feature ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<h3><?php echo esc_html( $jodyn_feature['title'] ); ?></h3>
					<?php if ( $jodyn_feature['summary'] ) : ?>
						<p><?php echo esc_html( $jodyn_feature['summary'] ); ?></p>
					<?php endif; ?>
					<?php echo $jodyn_meta( $jodyn_feature, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo $jodyn_register( $jodyn_feature, 'jd-btn--accent' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</article>

			<?php if ( $jodyn_programmes ) : ?>
				<div class="jd-prog__side">
					<?php foreach ( $jodyn_programmes as $i => $p ) : ?>
						<article class="jd-prog__item" data-jd-rise="<?php echo esc_attr( 0.1 + $i * 0.1 ); ?>">
							<div class="jd-prog__thumb">
								<?php
								if ( $p['image'] ) {
									echo wp_get_attachment_image(
										$p['image'],
										'large',
										false,
										array(
											'alt'   => '',
											'sizes' => '(min-width: 1024px) 32vw, (min-width: 640px) 40vw, 100vw', // Cropped to fill the card's height, so wider than the column.
										)
									);
								}
								echo $jodyn_date( $p ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</div>
							<div class="jd-prog__item-body">
								<h3><?php echo esc_html( $p['title'] ); ?></h3>
								<?php if ( $p['summary'] ) : ?>
									<p><?php echo esc_html( $p['summary'] ); ?></p>
								<?php endif; ?>
								<?php echo $jodyn_meta( $p, false ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php echo $jodyn_register( $p, 'jd-btn--secondary' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="jd-prog__bar" data-jd-rise>
			<button class="jd-btn jd-btn--arrow" type="button" aria-haspopup="dialog" aria-controls="jd-download" data-jd-download-open>
				Download Training Calendar (PDF)
				<span class="jd-btn__disc"><?php echo jodyn_icon( 'download' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</button>
			<a class="jd-prog__link" href="<?php echo esc_url( home_url( '/training/calendar/' ) ); ?>">
				<span>View Full Training Calendar</span><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		</div>

	</div>

	<dialog class="jd-dl" id="jd-download" aria-labelledby="jd-dl-title" data-jd-download>
		<form class="jd-dl__form" method="post" novalidate>
			<button class="jd-dl__close" type="button" data-jd-download-close>
				<span class="screen-reader-text"><?php esc_html_e( 'Close', 'jodyn' ); ?></span>
				<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
			</button>
			<span class="jd-dl__icon" aria-hidden="true"><?php echo jodyn_icon( 'download' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<h2 class="jd-dl__title" id="jd-dl-title">Download Training Calendar (PDF)</h2>
			<label class="jd-dl__field">
				<span><?php esc_html_e( 'Full name', 'jodyn' ); ?></span>
				<input type="text" name="name" autocomplete="name" maxlength="120" required>
			</label>
			<label class="jd-dl__field">
				<span><?php esc_html_e( 'Email address', 'jodyn' ); ?></span>
				<input type="email" name="email" autocomplete="email" required>
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
</section>
