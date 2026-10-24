<?php
/**
 * Training calendar · Hero (copy section 1).
 *
 * A planning photo fills the section behind a forest fade, with the header floating over it.
 * Along the bottom, a lime ribbon scrolls the upcoming open programmes past (date, title,
 * format; each links to its programme). It pauses on hover and focus, and stands still for
 * reduced-motion visitors, who can scroll it sideways instead. With nothing scheduled the
 * ribbon is left out. The button jumps to the download form (section 4).
 * Copy: content/pages/training-calendar.md, section 1 (approved).
 *
 * @package JoDyn
 */

$jodyn_runs = array_map( 'jodyn_programme_data', jodyn_get_upcoming_programmes( 8 ) );
$jodyn_img  = get_template_directory_uri() . '/assets/img/calendar/';

// Repeat a short list so one pass of the ribbon is always wider than the screen.
$jodyn_ribbon = $jodyn_runs;
while ( $jodyn_ribbon && count( $jodyn_ribbon ) < 6 ) {
	$jodyn_ribbon = array_merge( $jodyn_ribbon, $jodyn_runs );
}

// Only the first copy of each programme is read out and reachable by keyboard; repeats are visual.
$jodyn_track = static function ( $runs, $readable ) {
	$out = '';
	foreach ( $runs as $i => $run ) {
		$hidden = $i >= $readable;
		$out   .= sprintf(
			'<li%7$s><a href="%1$s"%2$s><time datetime="%3$s">%4$s</time><span class="jd-cal-hero__name">%5$s</span><span class="jd-cal-hero__fmt">%6$s</span></a></li>',
			esc_url( get_permalink( $run['id'] ) ),
			$hidden ? ' tabindex="-1"' : '',
			esc_attr( $run['datetime'] ),
			esc_html( $run['day'] . ' ' . $run['month'] ),
			esc_html( $run['title'] ),
			esc_html( $run['format'] ),
			$hidden ? ' aria-hidden="true"' : ''
		);
	}
	return $out;
};
?>
<section class="jd-cal-hero" aria-labelledby="jd-cal-hero-title" data-jd-cal-hero>
	<div class="jd-cal-hero__media" aria-hidden="true">
		<img src="<?php echo esc_url( $jodyn_img . 'hero-planning.webp' ); ?>" srcset="<?php echo esc_url( $jodyn_img . 'hero-planning-960.webp' ); ?> 960w, <?php echo esc_url( $jodyn_img . 'hero-planning.webp' ); ?> 1920w" sizes="100vw" alt="" width="1920" height="1280" fetchpriority="high" data-jd-cal-hero-img>
	</div>

	<div class="jd-container jd-cal-hero__inner">
		<div class="jd-cal-hero__copy">
			<p class="jd-eyebrow jd-eyebrow--inverse" data-jd-rise>Training calendar</p>
			<h1 class="jd-cal-hero__title" id="jd-cal-hero-title" data-jd-lines>Upcoming Training <em>Programmes</em></h1>
			<p class="jd-cal-hero__text" data-jd-rise="0.15">Explore our upcoming open programmes in leadership, management, people and professional skills. Choose a date and format that suits you, and secure your seat today.</p>
			<div class="jd-cal-hero__btns" data-jd-rise="0.25">
				<a class="jd-btn jd-btn--accent jd-btn--lg jd-cal-hero__dl" href="#download">
					<?php echo jodyn_icon( 'download' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					Download Training Calendar (PDF)
				</a>
			</div>
		</div>
	</div>

	<?php if ( $jodyn_ribbon ) : ?>
		<div class="jd-cal-hero__ribbon" style="--jd-cal-dur: <?php echo (int) ( count( $jodyn_ribbon ) * 7 ); ?>s" data-jd-cal-ribbon>
			<h2 class="screen-reader-text"><?php esc_html_e( 'Next open programmes', 'jodyn' ); ?></h2>
			<ul class="jd-cal-hero__track">
				<?php echo $jodyn_track( $jodyn_ribbon, count( $jodyn_runs ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>
			</ul>
			<ul class="jd-cal-hero__track" aria-hidden="true">
				<?php echo $jodyn_track( $jodyn_ribbon, 0 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>
			</ul>
		</div>
	<?php endif; ?>
</section>
