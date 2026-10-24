<?php
/**
 * Training calendar · Filters and programme list (copy sections 2 and 3).
 *
 * White. Every scheduled run from today on (Programmes in the admin), soonest first.
 * - Month tabs across the top: All, then this month and the next eleven, each with its count
 *   (months with nothing fade).
 * - Search, category and format, plus Clear filters and the result count.
 * - One programme per row: a big date, then the category, title (linked to its page), dates
 *   and length, format and venue, fee, seats badge, Register Now and View Details.
 *   "Fully booked" turns Register Now off.
 * - 8 per page with page numbers; any filter change goes back to page 1 (assets/js/calendar.js).
 *   Without JavaScript every run shows on one page.
 * - ?month=YYYY-MM, ?area=<key> and ?format=<key> in the address open it already filtered.
 * Copy: content/pages/training-calendar.md, sections 2 and 3 (approved).
 *
 * @package JoDyn
 */

$jodyn_runs   = array_map( 'jodyn_programme_data', jodyn_get_upcoming_programmes( -1 ) );
$jodyn_months = array();
$jodyn_first  = new DateTimeImmutable( current_time( 'Y-m-01' ), new DateTimeZone( 'UTC' ) );
for ( $jodyn_i = 0; $jodyn_i < 12; $jodyn_i++ ) {
	$jodyn_m = $jodyn_first->modify( "+$jodyn_i month" );
	// The first tab and every January carry the year
	$jodyn_months[ $jodyn_m->format( 'Y-m' ) ] = array(
		0 === $jodyn_i || '01' === $jodyn_m->format( 'm' ) ? wp_date( 'M Y', $jodyn_m->getTimestamp(), new DateTimeZone( 'UTC' ) ) : wp_date( 'M', $jodyn_m->getTimestamp(), new DateTimeZone( 'UTC' ) ),
		wp_date( 'F Y', $jodyn_m->getTimestamp(), new DateTimeZone( 'UTC' ) ),
	);
}
$jodyn_count = static function ( $key ) use ( $jodyn_runs ) {
	return count( wp_list_filter( $jodyn_runs, array( 'month_key' => $key ) ) );
};
$jodyn_total = count( $jodyn_runs );
?>
<section class="jd-cal-list" id="programmes" aria-labelledby="jd-cal-list-title" data-jd-cal-list data-per="8" data-jd-particles="light" data-jd-keep-out=".jd-cal-list__head > *, .jd-cal-list__tabs, .jd-cal-list__bar, .jd-cal-list__res">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-cal-list__head">
			<div>
				<p class="jd-eyebrow" data-jd-rise>Open programmes</p>
				<h2 class="jd-cal-list__title" id="jd-cal-list-title" data-jd-lines>Find Your Next <em>Programme</em></h2>
			</div>
			<p class="jd-cal-list__text" data-jd-rise="0.1">All programmes are facilitated by experienced professionals and include course materials, practical tools and a certificate of participation. Seats are limited to keep each session interactive.</p>
		</div>

		<div class="jd-cal-list__tabs" role="group" aria-label="<?php esc_attr_e( 'Month', 'jodyn' ); ?>">
			<button type="button" class="jd-cal-list__tab" data-f="month" data-v="all" aria-pressed="true">
				<?php esc_html_e( 'All', 'jodyn' ); ?><span class="jd-cal-list__n" data-month-count="all"><?php echo (int) $jodyn_total; ?></span>
			</button>
			<?php foreach ( $jodyn_months as $jodyn_key => $jodyn_m ) : ?>
				<?php $jodyn_n = $jodyn_count( $jodyn_key ); ?>
				<button type="button" class="jd-cal-list__tab<?php echo $jodyn_n ? '' : ' is-zero'; ?>" data-f="month" data-v="<?php echo esc_attr( $jodyn_key ); ?>" aria-pressed="false">
					<span aria-hidden="true"><?php echo esc_html( $jodyn_m[0] ); ?></span><span class="screen-reader-text"><?php echo esc_html( $jodyn_m[1] ); ?>,</span><span class="jd-cal-list__n" data-month-count="<?php echo esc_attr( $jodyn_key ); ?>"><?php echo (int) $jodyn_n; ?></span>
				</button>
			<?php endforeach; ?>
		</div>

		<div class="jd-cal-list__bar">
			<label class="jd-cal-list__search">
				<?php echo jodyn_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Search programmes', 'jodyn' ); ?></span>
				<input type="search" placeholder="<?php esc_attr_e( 'Search programmes…', 'jodyn' ); ?>" data-f="q" autocomplete="off">
			</label>
			<label class="jd-cal-list__select">
				<span class="screen-reader-text"><?php esc_html_e( 'Category', 'jodyn' ); ?></span>
				<select data-f="area">
					<option value="all"><?php esc_html_e( 'All categories', 'jodyn' ); ?></option>
					<?php foreach ( jodyn_programme_area_short() as $jodyn_key => $jodyn_label ) : ?>
						<option value="<?php echo esc_attr( $jodyn_key ); ?>"><?php echo esc_html( $jodyn_label ); ?></option>
					<?php endforeach; ?>
				</select>
				<?php echo jodyn_icon( 'chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</label>
			<label class="jd-cal-list__select">
				<span class="screen-reader-text"><?php esc_html_e( 'Format', 'jodyn' ); ?></span>
				<select data-f="format">
					<option value="all"><?php esc_html_e( 'All formats', 'jodyn' ); ?></option>
					<?php foreach ( jodyn_programme_formats() as $jodyn_key => $jodyn_label ) : ?>
						<option value="<?php echo esc_attr( $jodyn_key ); ?>"><?php echo esc_html( $jodyn_label ); ?></option>
					<?php endforeach; ?>
				</select>
				<?php echo jodyn_icon( 'chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</label>
			<button type="button" class="jd-cal-list__clear" data-f-clear disabled><?php echo jodyn_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Clear filters', 'jodyn' ); ?></button>
			<p class="jd-cal-list__status" aria-live="polite"><b data-count><?php echo (int) $jodyn_total; ?></b> <span data-count-label data-one="<?php esc_attr_e( 'programme', 'jodyn' ); ?>" data-many="<?php esc_attr_e( 'programmes', 'jodyn' ); ?>"><?php echo 1 === $jodyn_total ? esc_html__( 'programme', 'jodyn' ) : esc_html__( 'programmes', 'jodyn' ); ?></span></p>
		</div>

		<div class="jd-cal-list__res" data-results>
			<?php if ( $jodyn_runs ) : ?>
				<ol class="jd-cal-list__rows">
					<?php foreach ( $jodyn_runs as $jodyn_p ) : ?>
						<li class="jd-cal-list__row" data-item data-area="<?php echo esc_attr( $jodyn_p['area'] ); ?>" data-format="<?php echo esc_attr( $jodyn_p['format_key'] ); ?>" data-month="<?php echo esc_attr( $jodyn_p['month_key'] ); ?>" data-title="<?php echo esc_attr( strtolower( $jodyn_p['title'] ) ); ?>">
							<div class="jd-cal-list__day" aria-hidden="true">
								<b><?php echo esc_html( (int) $jodyn_p['day'] ); ?></b><span><?php echo esc_html( $jodyn_p['month'] ); ?><br><?php echo esc_html( $jodyn_p['weekday'] ); ?></span>
							</div>
							<div class="jd-cal-list__main">
								<p class="jd-cal-list__tag"><?php echo esc_html( $jodyn_p['area_short'] ); ?></p>
								<h3 class="jd-cal-list__name"><a href="<?php echo esc_url( $jodyn_p['details'] ); ?>"><?php echo esc_html( $jodyn_p['title'] ); ?></a></h3>
								<p class="jd-cal-list__when"><time datetime="<?php echo esc_attr( $jodyn_p['datetime'] ); ?>"><?php echo esc_html( $jodyn_p['when'] ); ?></time></p>
							</div>
							<div class="jd-cal-list__info">
								<span><?php echo jodyn_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $jodyn_p['format'] ); ?></span>
								<?php if ( $jodyn_p['fee'] ) : ?>
									<span><?php echo jodyn_icon( 'tag' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><b><?php echo esc_html( $jodyn_p['fee'] ); ?></b>&nbsp;<?php esc_html_e( 'per participant', 'jodyn' ); ?></span>
								<?php endif; ?>
								<span class="jd-cal-list__badge jd-cal-list__badge--<?php echo esc_attr( $jodyn_p['seats'] ); ?>"><?php echo esc_html( $jodyn_p['seats_label'] ); ?></span>
							</div>
							<div class="jd-cal-list__acts">
								<?php if ( 'full' === $jodyn_p['seats'] ) : ?>
									<span class="jd-btn jd-cal-list__reg is-off" aria-disabled="true">Register Now<span class="screen-reader-text">: <?php echo esc_html( $jodyn_p['title'] ); ?>, <?php echo esc_html( $jodyn_p['seats_label'] ); ?></span></span>
								<?php else : ?>
									<a class="jd-btn jd-btn--arrow jd-cal-list__reg" href="<?php echo esc_url( $jodyn_p['url'] ); ?>">
										Register Now<span class="screen-reader-text">: <?php echo esc_html( $jodyn_p['title'] ); ?></span>
										<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
									</a>
								<?php endif; ?>
								<a class="jd-cal-list__more" href="<?php echo esc_url( $jodyn_p['details'] ); ?>">View Details<span class="screen-reader-text">: <?php echo esc_html( $jodyn_p['title'] ); ?></span></a>
							</div>
						</li>
					<?php endforeach; ?>
				</ol>
			<?php endif; ?>

			<div class="jd-cal-list__empty" data-empty<?php echo $jodyn_runs ? ' hidden' : ''; ?>>
				<h3>No programmes match your search right now.</h3>
				<p>Try changing your filters, or tell us what you are looking for, and we will let you know when it is scheduled. We can also deliver any programme exclusively for your team.</p>
				<a class="jd-btn jd-btn--arrow" href="<?php echo esc_url( home_url( '/contact-us/?enquiry=training' ) ); ?>">
					Request a Programme
					<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</a>
			</div>

			<nav class="jd-cal-list__pager" aria-label="<?php esc_attr_e( 'Programme pages', 'jodyn' ); ?>" data-pager data-prev="<?php esc_attr_e( 'Previous', 'jodyn' ); ?>" data-next="<?php esc_attr_e( 'Next', 'jodyn' ); ?>" data-page-label="<?php /* translators: %d: page number. */ esc_attr_e( 'Page %d', 'jodyn' ); ?>" hidden></nav>
		</div>
	</div>
</section>
