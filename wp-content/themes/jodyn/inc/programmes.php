<?php
/**
 * Training programmes: a "Programmes" list in the admin, one entry per scheduled run.
 *
 * Title, summary (excerpt), photo (featured image) and description are the normal fields;
 * the "Programme details" box adds the start date, format, venue, fee and registration link.
 * The home page shows the next upcoming ones via jodyn_get_upcoming_programmes().
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
	$start  = get_post_meta( $post->ID, '_jodyn_start', true );
	$format = get_post_meta( $post->ID, '_jodyn_format', true );
	$venue  = get_post_meta( $post->ID, '_jodyn_venue', true );
	$fee    = get_post_meta( $post->ID, '_jodyn_fee', true );
	$url    = get_post_meta( $post->ID, '_jodyn_register_url', true );
	?>
	<p><label for="jodyn-start"><strong><?php esc_html_e( 'Start date', 'jodyn' ); ?></strong></label><br>
		<input type="date" id="jodyn-start" name="jodyn_start" value="<?php echo esc_attr( $start ); ?>" style="width:100%"></p>
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
		<span class="description"><?php esc_html_e( 'Leave empty to send people to this programme’s own page.', 'jodyn' ); ?></span></p>
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

	$start = isset( $_POST['jodyn_start'] ) ? sanitize_text_field( wp_unslash( $_POST['jodyn_start'] ) ) : '';
	$start = preg_match( '/^\d{4}-\d{2}-\d{2}$/', $start ) ? $start : '';
	update_post_meta( $post_id, '_jodyn_start', $start );

	$format = isset( $_POST['jodyn_format'] ) ? sanitize_key( $_POST['jodyn_format'] ) : '';
	update_post_meta( $post_id, '_jodyn_format', array_key_exists( $format, jodyn_programme_formats() ) ? $format : 'in-person' );

	update_post_meta( $post_id, '_jodyn_venue', isset( $_POST['jodyn_venue'] ) ? sanitize_text_field( wp_unslash( $_POST['jodyn_venue'] ) ) : '' );
	update_post_meta( $post_id, '_jodyn_fee', isset( $_POST['jodyn_fee'] ) ? sanitize_text_field( wp_unslash( $_POST['jodyn_fee'] ) ) : '' );
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
	$columns['jodyn_start']  = __( 'Starts', 'jodyn' );
	$columns['jodyn_format'] = __( 'Format', 'jodyn' );
	$columns['jodyn_fee']    = __( 'Fee', 'jodyn' );
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
	if ( 'jodyn_start' === $column ) {
		echo esc_html( $p['date_full'] ? $p['date_full'] : '—' );
	} elseif ( 'jodyn_format' === $column ) {
		echo esc_html( $p['format'] );
	} elseif ( 'jodyn_fee' === $column ) {
		echo esc_html( $p['fee'] ? $p['fee'] : '—' );
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

	return array(
		'id'        => $post->ID,
		'title'     => get_the_title( $post ),
		'summary'   => get_the_excerpt( $post ),
		'day'       => $time ? wp_date( 'd', $time, new DateTimeZone( 'UTC' ) ) : '',
		'month'     => $time ? wp_date( 'M', $time, new DateTimeZone( 'UTC' ) ) : '',
		'date_full' => $time ? wp_date( 'j M Y', $time, new DateTimeZone( 'UTC' ) ) : '',
		'datetime'  => $start,
		'format'    => ( isset( $formats[ $format ] ) ? $formats[ $format ] : $formats['in-person'] ) . ( $venue ? ' · ' . $venue : '' ),
		'fee'       => get_post_meta( $post->ID, '_jodyn_fee', true ),
		'url'       => $url ? $url : get_permalink( $post ),
		'image'     => get_post_thumbnail_id( $post ),
	);
}
