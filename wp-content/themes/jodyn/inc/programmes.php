<?php
/**
 * Training programmes: a "Programmes" list in the admin, one entry per programme.
 *
 * Title, summary (excerpt), photo (featured image) and description are the normal fields;
 * the "Programme details" box adds the area, and for a scheduled run the start date, format,
 * venue, fee and registration link. The home page shows the next upcoming ones via
 * jodyn_get_upcoming_programmes(); the Training programmes page lists every one, filterable,
 * via jodyn_get_all_programmes().
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Formats offered, keyed by stored value.
 *
 * @return array<string,string>
 */
function jodyn_programme_formats() {
	return array(
		'in-person' => __( 'In-person', 'jodyn' ),
		'online'    => __( 'Online', 'jodyn' ),
		'hybrid'    => __( 'Hybrid', 'jodyn' ),
	);
}

/**
 * The six training areas, keyed by stored value (also the ?area= value on the Training page).
 *
 * @return array<string,string>
 */
function jodyn_programme_areas() {
	return array(
		'leadership'   => __( 'Leadership & Management Development', 'jodyn' ),
		'strategy'     => __( 'Strategy, Planning & Execution', 'jodyn' ),
		'hr'           => __( 'Human Resources & People Management', 'jodyn' ),
		'professional' => __( 'Personal Effectiveness & Professional Skills', 'jodyn' ),
		'customer'     => __( 'Customer Service & Business Development', 'jodyn' ),
		'projects'     => __( 'Project Management & Operational Excellence', 'jodyn' ),
	);
}

/**
 * Shorter area names for the Training calendar's category filter and tags
 * (content/pages/training-calendar.md, section 2). Same six areas, same keys.
 *
 * @return array key => label
 */
function jodyn_programme_area_short() {
	return array(
		'leadership'   => __( 'Leadership & Management', 'jodyn' ),
		'strategy'     => __( 'Strategy & Execution', 'jodyn' ),
		'hr'           => __( 'HR & People Management', 'jodyn' ),
		'professional' => __( 'Personal Effectiveness', 'jodyn' ),
		'customer'     => __( 'Customer Service & Business Development', 'jodyn' ),
		'projects'     => __( 'Project Management & Operations', 'jodyn' ),
	);
}

/**
 * Seats status of a scheduled run, shown as a badge on the Training calendar.
 * "Fully booked" turns its Register Now button off.
 *
 * @return array key => label
 */
function jodyn_programme_seats() {
	return array(
		'open' => __( 'Seats available', 'jodyn' ),
		'few'  => __( 'Few seats left', 'jodyn' ),
		'full' => __( 'Fully booked', 'jodyn' ),
	);
}

/**
 * Register the post type.
 */
function jodyn_register_programmes() {
	register_post_type(
		'jodyn_programme',
		array(
			'labels'        => array(
				'name'               => __( 'Programmes', 'jodyn' ),
				'singular_name'      => __( 'Programme', 'jodyn' ),
				'add_new_item'       => __( 'Add programme', 'jodyn' ),
				'edit_item'          => __( 'Edit programme', 'jodyn' ),
				'new_item'           => __( 'New programme', 'jodyn' ),
				'view_item'          => __( 'View programme', 'jodyn' ),
				'search_items'       => __( 'Search programmes', 'jodyn' ),
				'not_found'          => __( 'No programmes found', 'jodyn' ),
				'all_items'          => __( 'All programmes', 'jodyn' ),
				'featured_image'     => __( 'Programme photo', 'jodyn' ),
				'set_featured_image' => __( 'Set programme photo', 'jodyn' ),
			),
			'public'        => true,
			'has_archive'   => false,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-calendar-alt',
			'menu_position' => 21,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'rewrite'       => array(
				'slug'       => 'programme',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'jodyn_register_programmes' );

/**
 * "Programme details" box.
 */
function jodyn_programme_meta_box() {
	add_meta_box( 'jodyn-programme-details', __( 'Programme details', 'jodyn' ), 'jodyn_programme_meta_box_html', 'jodyn_programme', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'jodyn_programme_meta_box' );

/**
 * @param WP_Post $post Programme.
 */
function jodyn_programme_meta_box_html( $post ) {
	wp_nonce_field( 'jodyn_programme_save', 'jodyn_programme_nonce' );
	$area   = get_post_meta( $post->ID, '_jodyn_area', true );
	$start  = get_post_meta( $post->ID, '_jodyn_start', true );
	$end    = get_post_meta( $post->ID, '_jodyn_end', true );
	$seats  = get_post_meta( $post->ID, '_jodyn_seats', true );
	$format = get_post_meta( $post->ID, '_jodyn_format', true );
	$venue  = get_post_meta( $post->ID, '_jodyn_venue', true );
	$fee    = get_post_meta( $post->ID, '_jodyn_fee', true );
	$url    = get_post_meta( $post->ID, '_jodyn_register_url', true );
	$hours  = get_post_meta( $post->ID, '_jodyn_time', true );
	?>
	<p><label for="jodyn-area"><strong><?php esc_html_e( 'Area', 'jodyn' ); ?></strong></label><br>
		<select id="jodyn-area" name="jodyn_area" style="width:100%">
			<?php foreach ( jodyn_programme_areas() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $area, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select></p>
	<p><label for="jodyn-start"><strong><?php esc_html_e( 'Start date', 'jodyn' ); ?></strong></label><br>
		<input type="date" id="jodyn-start" name="jodyn_start" value="<?php echo esc_attr( $start ); ?>" style="width:100%">
		<span class="description"><?php esc_html_e( 'Leave empty until the next run is scheduled. The format, venue, fee and registration link apply to that run.', 'jodyn' ); ?></span></p>
	<p><label for="jodyn-end"><strong><?php esc_html_e( 'End date (optional)', 'jodyn' ); ?></strong></label><br>
		<input type="date" id="jodyn-end" name="jodyn_end" value="<?php echo esc_attr( $end ); ?>" style="width:100%">
		<span class="description"><?php esc_html_e( 'For runs longer than a day. Leave empty for a one-day programme.', 'jodyn' ); ?></span></p>
	<p><label for="jodyn-time"><strong><?php esc_html_e( 'Time (optional)', 'jodyn' ); ?></strong></label><br>
		<input type="text" id="jodyn-time" name="jodyn_time" value="<?php echo esc_attr( $hours ); ?>" placeholder="<?php esc_attr_e( 'e.g. 9:00am – 4:00pm', 'jodyn' ); ?>" style="width:100%">
		<span class="description"><?php esc_html_e( 'Shown in the registration confirmation email.', 'jodyn' ); ?></span></p>
	<p><label for="jodyn-seats"><strong><?php esc_html_e( 'Seats', 'jodyn' ); ?></strong></label><br>
		<select id="jodyn-seats" name="jodyn_seats" style="width:100%">
			<?php foreach ( jodyn_programme_seats() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $seats ? $seats : 'open', $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php esc_html_e( '"Fully booked" turns off Register Now on the Training calendar.', 'jodyn' ); ?></span></p>
	<p><label for="jodyn-format"><strong><?php esc_html_e( 'Format', 'jodyn' ); ?></strong></label><br>
		<select id="jodyn-format" name="jodyn_format" style="width:100%">
			<?php foreach ( jodyn_programme_formats() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $format, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select></p>
	<p><label for="jodyn-venue"><strong><?php esc_html_e( 'Venue (optional)', 'jodyn' ); ?></strong></label><br>
		<input type="text" id="jodyn-venue" name="jodyn_venue" value="<?php echo esc_attr( $venue ); ?>" placeholder="<?php esc_attr_e( 'e.g. Victoria Island, Lagos', 'jodyn' ); ?>" style="width:100%"></p>
	<p><label for="jodyn-fee"><strong><?php esc_html_e( 'Fee', 'jodyn' ); ?></strong></label><br>
		<input type="text" id="jodyn-fee" name="jodyn_fee" value="<?php echo esc_attr( $fee ); ?>" placeholder="<?php esc_attr_e( 'e.g. ₦250,000', 'jodyn' ); ?>" style="width:100%"></p>
	<p><label for="jodyn-register"><strong><?php esc_html_e( 'Registration link (optional)', 'jodyn' ); ?></strong></label><br>
		<input type="url" id="jodyn-register" name="jodyn_register_url" value="<?php echo esc_attr( $url ); ?>" placeholder="https://" style="width:100%">
		<span class="description"><?php esc_html_e( 'Leave empty to use the site’s own registration form (Register page), with this programme already selected.', 'jodyn' ); ?></span></p>
	<p class="description"><?php esc_html_e( 'Use the Excerpt for the short summary shown on the home page, and the Programme photo for its image.', 'jodyn' ); ?></p>
	<?php
}

/**
 * @param int $post_id Programme ID.
 */
function jodyn_programme_save( $post_id ) {
	if ( ! isset( $_POST['jodyn_programme_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['jodyn_programme_nonce'] ), 'jodyn_programme_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$area = isset( $_POST['jodyn_area'] ) ? sanitize_key( $_POST['jodyn_area'] ) : '';
	update_post_meta( $post_id, '_jodyn_area', array_key_exists( $area, jodyn_programme_areas() ) ? $area : 'leadership' );

	$start = isset( $_POST['jodyn_start'] ) ? sanitize_text_field( wp_unslash( $_POST['jodyn_start'] ) ) : '';
	$start = preg_match( '/^\d{4}-\d{2}-\d{2}$/', $start ) ? $start : '';
	update_post_meta( $post_id, '_jodyn_start', $start );

	$end = isset( $_POST['jodyn_end'] ) ? sanitize_text_field( wp_unslash( $_POST['jodyn_end'] ) ) : '';
	update_post_meta( $post_id, '_jodyn_end', preg_match( '/^\d{4}-\d{2}-\d{2}$/', $end ) && $start && $end > $start ? $end : '' );

	$seats = isset( $_POST['jodyn_seats'] ) ? sanitize_key( $_POST['jodyn_seats'] ) : '';
	update_post_meta( $post_id, '_jodyn_seats', array_key_exists( $seats, jodyn_programme_seats() ) ? $seats : 'open' );

	$format = isset( $_POST['jodyn_format'] ) ? sanitize_key( $_POST['jodyn_format'] ) : '';
	update_post_meta( $post_id, '_jodyn_format', array_key_exists( $format, jodyn_programme_formats() ) ? $format : 'in-person' );

	update_post_meta( $post_id, '_jodyn_venue', isset( $_POST['jodyn_venue'] ) ? sanitize_text_field( wp_unslash( $_POST['jodyn_venue'] ) ) : '' );
	update_post_meta( $post_id, '_jodyn_fee', isset( $_POST['jodyn_fee'] ) ? sanitize_text_field( wp_unslash( $_POST['jodyn_fee'] ) ) : '' );
	update_post_meta( $post_id, '_jodyn_time', isset( $_POST['jodyn_time'] ) ? sanitize_text_field( wp_unslash( $_POST['jodyn_time'] ) ) : '' );
	update_post_meta( $post_id, '_jodyn_register_url', isset( $_POST['jodyn_register_url'] ) ? esc_url_raw( wp_unslash( $_POST['jodyn_register_url'] ) ) : '' );
}
add_action( 'save_post_jodyn_programme', 'jodyn_programme_save' );

/**
 * Admin list: show date, format and fee; sort by date.
 *
 * @param array $columns Columns.
 */
function jodyn_programme_columns( $columns ) {
	$date = $columns['date'];
	unset( $columns['date'] );
	$columns['jodyn_area']   = __( 'Area', 'jodyn' );
	$columns['jodyn_start']  = __( 'Starts', 'jodyn' );
	$columns['jodyn_format'] = __( 'Format', 'jodyn' );
	$columns['jodyn_fee']    = __( 'Fee', 'jodyn' );
	$columns['jodyn_seats']  = __( 'Seats', 'jodyn' );
	$columns['date']         = $date;
	return $columns;
}
add_filter( 'manage_jodyn_programme_posts_columns', 'jodyn_programme_columns' );

/**
 * @param string $column  Column key.
 * @param int    $post_id Programme ID.
 */
function jodyn_programme_column_content( $column, $post_id ) {
	$p = jodyn_programme_data( $post_id );
	if ( 'jodyn_area' === $column ) {
		echo esc_html( $p['area_label'] );
	} elseif ( 'jodyn_start' === $column ) {
		echo esc_html( $p['when'] ? $p['when'] : '—' );
	} elseif ( 'jodyn_format' === $column ) {
		echo esc_html( $p['format'] );
	} elseif ( 'jodyn_fee' === $column ) {
		echo esc_html( $p['fee'] ? $p['fee'] : '—' );
	} elseif ( 'jodyn_seats' === $column ) {
		echo esc_html( $p['upcoming'] ? $p['seats_label'] : '—' );
	}
}
add_action( 'manage_jodyn_programme_posts_custom_column', 'jodyn_programme_column_content', 10, 2 );

add_filter(
	'manage_edit-jodyn_programme_sortable_columns',
	function ( $columns ) {
		$columns['jodyn_start'] = 'jodyn_start';
		return $columns;
	}
);
add_action(
	'pre_get_posts',
	function ( $query ) {
		if ( is_admin() && $query->is_main_query() && 'jodyn_start' === $query->get( 'orderby' ) ) {
			$query->set( 'meta_key', '_jodyn_start' );
			$query->set( 'orderby', 'meta_value' );
		}
	}
);

/**
 * Next programmes from today on, soonest first.
 *
 * @param int $limit How many.
 * @return WP_Post[]
 */
function jodyn_get_upcoming_programmes( $limit = 3 ) {
	return get_posts(
		array(
			'post_type'        => 'jodyn_programme',
			'post_status'      => 'publish',
			'numberposts'      => $limit,
			'meta_key'         => '_jodyn_start', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'orderby'          => 'meta_value',
			'order'            => 'ASC',
			'meta_query'       => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_jodyn_start',
					'value'   => current_time( 'Y-m-d' ),
					'compare' => '>=',
					'type'    => 'DATE',
				),
			),
			'suppress_filters' => false,
		)
	);
}

/**
 * Every programme for the Training page: scheduled runs first (soonest first), then the rest
 * in area order, keeping the order they were added in within an area.
 *
 * @return array[] jodyn_programme_data() for each.
 */
function jodyn_get_all_programmes() {
	$posts = get_posts(
		array(
			'post_type'        => 'jodyn_programme',
			'post_status'      => 'publish',
			'numberposts'      => -1,
			'orderby'          => array(
				'menu_order' => 'ASC',
				'ID'         => 'ASC',
			),
			'suppress_filters' => false,
		)
	);
	$order = array_flip( array_keys( jodyn_programme_areas() ) );
	$items = array_map( 'jodyn_programme_data', $posts );
	$rank  = array_flip( wp_list_pluck( $items, 'id' ) );
	usort(
		$items,
		static function ( $a, $b ) use ( $order, $rank ) {
			if ( $a['upcoming'] !== $b['upcoming'] ) {
				return $a['upcoming'] ? -1 : 1;
			}
			if ( $a['upcoming'] && $a['datetime'] !== $b['datetime'] ) {
				return strcmp( $a['datetime'], $b['datetime'] );
			}
			if ( $a['area'] !== $b['area'] ) {
				return $order[ $a['area'] ] - $order[ $b['area'] ];
			}
			return $rank[ $a['id'] ] - $rank[ $b['id'] ];
		}
	);
	return $items;
}

/**
 * Everything a template needs about one programme.
 *
 * @param int|WP_Post $post Programme.
 * @return array
 */
function jodyn_programme_data( $post ) {
	$post    = get_post( $post );
	$start   = get_post_meta( $post->ID, '_jodyn_start', true );
	$time    = $start ? strtotime( $start . ' 00:00:00' ) : 0;
	$formats = jodyn_programme_formats();
	$format  = get_post_meta( $post->ID, '_jodyn_format', true );
	$venue   = get_post_meta( $post->ID, '_jodyn_venue', true );
	$url     = get_post_meta( $post->ID, '_jodyn_register_url', true );
	$areas   = jodyn_programme_areas();
	$area    = get_post_meta( $post->ID, '_jodyn_area', true );
	$area    = isset( $areas[ $area ] ) ? $area : 'leadership';
	$short   = jodyn_programme_area_short();
	$seats   = jodyn_programme_seats();
	$seat    = get_post_meta( $post->ID, '_jodyn_seats', true );
	$seat    = isset( $seats[ $seat ] ) ? $seat : 'open';
	$end     = get_post_meta( $post->ID, '_jodyn_end', true );
	$end     = $start && $end > $start ? $end : '';
	$utc     = new DateTimeZone( 'UTC' );
	$days    = $end ? (int) round( ( strtotime( $end ) - $time ) / DAY_IN_SECONDS ) + 1 : 1;
	$fee     = get_post_meta( $post->ID, '_jodyn_fee', true );
	$open    = $start && $start >= current_time( 'Y-m-d' ) && 'full' !== $seat;

	// "16–17 November 2026 · 2 days", "30 November – 1 December 2026 · 2 days", "24 November 2026 · 1 day"
	$when = '';
	if ( $time ) {
		$end_time = $end ? strtotime( $end . ' 00:00:00' ) : 0;
		if ( ! $end_time ) {
			$range = wp_date( 'j F Y', $time, $utc );
		} elseif ( wp_date( 'Y-m', $time, $utc ) === wp_date( 'Y-m', $end_time, $utc ) ) {
			$range = wp_date( 'j', $time, $utc ) . '–' . wp_date( 'j F Y', $end_time, $utc );
		} elseif ( wp_date( 'Y', $time, $utc ) === wp_date( 'Y', $end_time, $utc ) ) {
			$range = wp_date( 'j F', $time, $utc ) . ' – ' . wp_date( 'j F Y', $end_time, $utc );
		} else {
			$range = wp_date( 'j F Y', $time, $utc ) . ' – ' . wp_date( 'j F Y', $end_time, $utc );
		}
		/* translators: %d: number of days. */
		$when = $range . ' · ' . sprintf( _n( '%d day', '%d days', $days, 'jodyn' ), $days );
	}

	return array(
		'id'          => $post->ID,
		'title'       => get_the_title( $post ),
		'summary'     => get_the_excerpt( $post ),
		'day'         => $time ? wp_date( 'd', $time, new DateTimeZone( 'UTC' ) ) : '',
		'month'       => $time ? wp_date( 'M', $time, new DateTimeZone( 'UTC' ) ) : '',
		'date_full'   => $time ? wp_date( 'j M Y', $time, new DateTimeZone( 'UTC' ) ) : '',
		'datetime'    => $start,
		'upcoming'    => $start && $start >= current_time( 'Y-m-d' ),
		'area'        => $area,
		'area_label'  => $areas[ $area ],
		'area_short'  => $short[ $area ],
		'end'         => $end,
		'when'        => $when,
		'weekday'     => $time ? wp_date( 'D', $time, $utc ) : '',
		'month_key'   => $time ? wp_date( 'Y-m', $time, $utc ) : '',
		'seats'       => $seat,
		'seats_label' => $seats[ $seat ],
		'details'     => get_permalink( $post ),
		'format_key'  => isset( $formats[ $format ] ) ? $format : 'in-person',
		'format'      => ( isset( $formats[ $format ] ) ? $formats[ $format ] : $formats['in-person'] ) . ( $venue ? ' · ' . $venue : '' ),
		'format_name' => isset( $formats[ $format ] ) ? $formats[ $format ] : $formats['in-person'],
		'venue'       => $venue,
		'time'        => get_post_meta( $post->ID, '_jodyn_time', true ),
		'fee'         => $fee,
		'fee_amount'  => (int) preg_replace( '/\D/', '', (string) $fee ),
		'open'        => $open,
		// Register Now: the programme's own link if set, else the Register page with this run selected
		'url'         => $url ? $url : ( $open ? add_query_arg( 'programme', $post->ID, home_url( '/register/' ) ) : get_permalink( $post ) ),
		'slug'        => $post->post_name,
		'image'       => get_post_thumbnail_id( $post ),
	);
}
