<?php
/**
 * Training · 3. Explore our training programmes.
 *
 * Soft green. The six areas are tiles that filter the list; a white panel below holds the
 * format chips, a search box and every programme (Programmes in the admin, each tagged with
 * an area). Scheduled runs come first with their date, format, fee and Register Now; the rest
 * offer Request In-House Training. Filtering is in assets/js/training.js and works without
 * motion; with no JS the full list shows. ?area=<key> opens the list already filtered.
 * Copy: content/pages/training.md, section 3 (approved).
 *
 * @package JoDyn
 */

// Area copy: icon, description and "Designed for", keyed like jodyn_programme_areas().
$jodyn_area_copy = array(
	'leadership'   => array( 'award', 'Effective leadership is the foundation of high-performing organisations. Our leadership programmes equip managers and executives at every level with the skills to inspire teams, make sound decisions and deliver results.', 'first-time supervisors, middle managers, senior executives and high-potential employees.' ),
	'strategy'     => array( 'compass', 'A great strategy only creates value when it is executed well. These programmes help leaders and teams think strategically, plan effectively and turn goals into measurable results.', 'leadership teams, managers, strategy and planning officers, and business owners.' ),
	'hr'           => array( 'users', "People are an organisation's greatest asset. Our HR programmes equip HR professionals and line managers to attract, develop, engage and retain the right talent.", 'HR professionals, line managers, team leads and business owners managing people.' ),
	'professional' => array( 'user', 'The skills that shape everyday performance at work. These programmes help professionals communicate clearly, work productively and build strong working relationships.', 'professionals at all levels, graduate trainees and teams seeking to improve collaboration.' ),
	'customer'     => array( 'heart', 'Customers remember how they are treated. These programmes help teams deliver excellent service, build lasting relationships and grow revenue.', 'frontline staff, customer service teams, sales teams and relationship managers.' ),
	'projects'     => array( 'target', 'Deliver projects on time, on budget and to standard. These programmes equip teams with practical tools to plan, manage and improve how work gets done.', 'project officers, operations teams, programme managers and team leads.' ),
);
$jodyn_areas      = jodyn_programme_areas();
$jodyn_programmes = jodyn_get_all_programmes();
$jodyn_counts     = array_count_values( wp_list_pluck( $jodyn_programmes, 'area' ) );
$jodyn_total      = count( $jodyn_programmes );
?>
<section class="jd-tr-pg" id="programmes" aria-labelledby="jd-tr-pg-title" data-jd-tr-pg>
	<div class="jd-container">
		<div class="jd-tr-pg__head">
			<div>
				<p class="jd-eyebrow" data-jd-rise>Our programmes</p>
				<h2 class="jd-tr-pg__title" id="jd-tr-pg-title" data-jd-lines>Explore Our Training <em>Programmes</em></h2>
			</div>
			<p class="jd-tr-pg__intro" data-jd-rise="0.1">Our programmes are organised into six areas of expertise. Each can be attended as an open programme or customised and delivered exclusively for your organisation.</p>
		</div>

		<div class="jd-tr-pg__tiles" role="group" aria-label="<?php esc_attr_e( 'Filter by area', 'jodyn' ); ?>" data-jd-stagger>
			<?php foreach ( $jodyn_areas as $jodyn_key => $jodyn_label ) : ?>
				<?php $jodyn_n = isset( $jodyn_counts[ $jodyn_key ] ) ? $jodyn_counts[ $jodyn_key ] : 0; ?>
				<button type="button" class="jd-tr-pg__tile" data-f-area="<?php echo esc_attr( $jodyn_key ); ?>" aria-pressed="false">
					<span class="jd-tr-pg__tile-ic"><?php echo jodyn_icon( $jodyn_area_copy[ $jodyn_key ][0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="jd-tr-pg__tile-name"><?php echo esc_html( $jodyn_label ); ?></span>
					<span class="jd-tr-pg__tile-n">
						<?php
						/* translators: %d: number of programmes in the area. */
						echo esc_html( sprintf( _n( '%d programme', '%d programmes', $jodyn_n, 'jodyn' ), $jodyn_n ) );
						?>
					</span>
				</button>
			<?php endforeach; ?>
		</div>

		<div class="jd-tr-pg__panel">
			<div class="jd-tr-pg__tools">
				<div class="jd-tr-pg__chips" role="group" aria-label="<?php esc_attr_e( 'Show all areas', 'jodyn' ); ?>">
					<button type="button" data-f-area="all" aria-pressed="true"><?php esc_html_e( 'All areas', 'jodyn' ); ?></button>
				</div>
				<div class="jd-tr-pg__chips" role="group" aria-label="<?php esc_attr_e( 'Filter by format', 'jodyn' ); ?>">
					<button type="button" data-f-format="all" aria-pressed="true"><?php esc_html_e( 'All formats', 'jodyn' ); ?></button>
					<?php foreach ( array( 'in-person' => __( 'In person', 'jodyn' ), 'online' => __( 'Online', 'jodyn' ), 'hybrid' => __( 'Hybrid', 'jodyn' ) ) as $jodyn_key => $jodyn_label ) : ?>
						<button type="button" data-f-format="<?php echo esc_attr( $jodyn_key ); ?>" aria-pressed="false"><?php echo esc_html( $jodyn_label ); ?></button>
					<?php endforeach; ?>
				</div>
				<label class="jd-tr-pg__search">
					<?php echo jodyn_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="screen-reader-text"><?php esc_html_e( 'Search programmes', 'jodyn' ); ?></span>
					<input type="search" placeholder="<?php esc_attr_e( 'Search programmes', 'jodyn' ); ?>" data-f-q>
				</label>
			</div>

			<?php foreach ( $jodyn_area_copy as $jodyn_key => $jodyn_copy ) : ?>
				<div class="jd-tr-pg__info" data-area-info="<?php echo esc_attr( $jodyn_key ); ?>" hidden>
					<span class="jd-tr-pg__info-ic"><?php echo jodyn_icon( $jodyn_copy[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<div>
						<h3><?php echo esc_html( $jodyn_areas[ $jodyn_key ] ); ?></h3>
						<p><?php echo esc_html( $jodyn_copy[1] ); ?></p>
						<p class="jd-tr-pg__for"><b>Designed for:</b> <?php echo esc_html( $jodyn_copy[2] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>

			<p class="jd-tr-pg__status" aria-live="polite"><span data-count><?php echo esc_html( $jodyn_total ); ?></span> <span data-count-label data-one="<?php esc_attr_e( 'programme', 'jodyn' ); ?>" data-many="<?php esc_attr_e( 'programmes', 'jodyn' ); ?>"><?php echo esc_html( _n( 'programme', 'programmes', $jodyn_total, 'jodyn' ) ); ?></span></p>

			<ul class="jd-tr-pg__list">
				<?php foreach ( $jodyn_programmes as $jodyn_i => $jodyn_p ) : ?>
					<li class="jd-tr-pg__row" data-item data-area="<?php echo esc_attr( $jodyn_p['area'] ); ?>" data-format="<?php echo esc_attr( $jodyn_p['upcoming'] ? $jodyn_p['format_key'] : '' ); ?>" data-title="<?php echo esc_attr( strtolower( wp_strip_all_tags( $jodyn_p['title'] ) ) ); ?>">
						<span class="jd-tr-pg__dot" style="--i: <?php echo esc_attr( array_search( $jodyn_p['area'], array_keys( $jodyn_areas ), true ) ); ?>" aria-hidden="true"></span>
						<div class="jd-tr-pg__main">
							<h3 class="jd-tr-pg__name"><a href="<?php echo esc_url( get_permalink( $jodyn_p['id'] ) ); ?>"><?php echo esc_html( $jodyn_p['title'] ); ?></a></h3>
							<p class="jd-tr-pg__area"><?php echo esc_html( $jodyn_p['area_label'] ); ?></p>
						</div>
						<?php if ( $jodyn_p['upcoming'] ) : ?>
							<span class="jd-tr-pg__when">
								<?php echo jodyn_icon( 'calendar' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span><time datetime="<?php echo esc_attr( $jodyn_p['datetime'] ); ?>"><b><?php echo esc_html( $jodyn_p['date_full'] ); ?></b></time> · <?php echo esc_html( $jodyn_p['format'] ); ?><?php echo $jodyn_p['fee'] ? ' · ' . esc_html( $jodyn_p['fee'] ) : ''; ?></span>
							</span>
							<a class="jd-tr-pg__act jd-tr-pg__act--reg" href="<?php echo esc_url( $jodyn_p['url'] ); ?>">Register Now<span aria-hidden="true">→</span><span class="screen-reader-text">: <?php echo esc_html( $jodyn_p['title'] ); ?></span></a>
						<?php else : ?>
							<a class="jd-tr-pg__act" href="<?php echo esc_url( home_url( '/contact-us/?enquiry=training&programme=' . rawurlencode( $jodyn_p['slug'] ) ) ); ?>">Request In-House Training<span aria-hidden="true">→</span><span class="screen-reader-text">: <?php echo esc_html( $jodyn_p['title'] ); ?></span></a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<p class="jd-tr-pg__empty" data-empty hidden><?php esc_html_e( 'No programmes match these filters. Try another area or format.', 'jodyn' ); ?></p>
		</div>
	</div>
</section>
