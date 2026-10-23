<?php
/**
 * Training · Upcoming open programmes (copy section 6, shown third on the page).
 *
 * Forest, straight after "Why train" and before the full programmes list. The next three
 * scheduled runs (Programmes in the admin) as tickets: a lime tear-off stub with the date,
 * then the area, title, format, fee and Register Now. The calendar link and the PDF download
 * (template-parts/calendar-download.php) sit below. With nothing scheduled it is skipped.
 * Copy: content/pages/training.md, section 6 (approved).
 *
 * @package JoDyn
 */

$jodyn_upcoming = array_map( 'jodyn_programme_data', jodyn_get_upcoming_programmes( 3 ) );
if ( ! $jodyn_upcoming ) {
	return;
}
?>
<section class="jd-tr-up" id="upcoming" aria-labelledby="jd-tr-up-title" data-jd-particles="forest" data-jd-keep-out=".jd-tr-up__head > *, .jd-tr-up__grid, .jd-tr-up__btns">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-tr-up__head">
			<div>
				<p class="jd-eyebrow jd-eyebrow--inverse" data-jd-rise>Training calendar</p>
				<h2 class="jd-tr-up__title" id="jd-tr-up-title" data-jd-lines>Upcoming Open <em>Programmes</em></h2>
			</div>
			<p class="jd-tr-up__text" data-jd-rise="0.1">Secure your seat on one of our upcoming programmes. Seats are limited to keep every session interactive, so early registration is encouraged.</p>
		</div>

		<ul class="jd-tr-up__grid" data-jd-stagger>
			<?php foreach ( $jodyn_upcoming as $jodyn_p ) : ?>
				<li class="jd-tr-up__ticket">
					<div class="jd-tr-up__stub">
						<span class="jd-tr-up__kind"><?php esc_html_e( 'Open programme', 'jodyn' ); ?></span>
						<time datetime="<?php echo esc_attr( $jodyn_p['datetime'] ); ?>"><b><?php echo esc_html( $jodyn_p['day'] ); ?></b><?php echo esc_html( $jodyn_p['month'] . ' ' . substr( $jodyn_p['datetime'], 0, 4 ) ); ?></time>
					</div>
					<div class="jd-tr-up__body">
						<p class="jd-tr-up__area"><?php echo esc_html( $jodyn_p['area_label'] ); ?></p>
						<h3 class="jd-tr-up__name"><a href="<?php echo esc_url( get_permalink( $jodyn_p['id'] ) ); ?>"><?php echo esc_html( $jodyn_p['title'] ); ?></a></h3>
						<ul class="jd-tr-up__meta">
							<li><?php echo jodyn_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $jodyn_p['format'] ); ?></li>
							<?php if ( $jodyn_p['fee'] ) : ?>
								<li><?php echo jodyn_icon( 'tag' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $jodyn_p['fee'] ); ?></li>
							<?php endif; ?>
						</ul>
						<a class="jd-btn jd-btn--arrow" href="<?php echo esc_url( $jodyn_p['url'] ); ?>">
							Register Now<span class="screen-reader-text">: <?php echo esc_html( $jodyn_p['title'] ); ?></span>
							<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</a>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="jd-tr-up__btns" data-jd-rise>
			<a class="jd-btn jd-btn--accent jd-btn--arrow" href="<?php echo esc_url( home_url( '/training/calendar/' ) ); ?>">
				View Full Training Calendar
				<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</a>
			<button class="jd-btn jd-btn--inverse jd-tr-up__dl" type="button" aria-haspopup="dialog" aria-controls="jd-download" data-jd-download-open>
				<?php echo jodyn_icon( 'download' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				Download Training Calendar (PDF)
			</button>
		</div>
	</div>

	<?php get_template_part( 'template-parts/calendar-download' ); ?>
</section>
