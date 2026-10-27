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

	foreach ( jodyn_programme_page_fields() as $key => $field ) {
		$value = isset( $_POST[ "jodyn_$key" ] ) ? wp_unslash( $_POST[ "jodyn_$key" ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized below by type.
		if ( 'url' === $field[1] ) {
			$value = esc_url_raw( $value );
		} elseif ( 'text' === $field[1] ) {
			$value = sanitize_text_field( $value );
		} else {
			$value = sanitize_textarea_field( $value );
		}
		update_post_meta( $post_id, "_jodyn_$key", $value );
	}
}
add_action( 'save_post_jodyn_programme', 'jodyn_programme_save' );

/**
 * The programme page's own fields (content/pages/programme.md, "Fields in the admin"):
 * key => array( label, type, help ). Lists are one item per line.
 *
 * @return array
 */
function jodyn_programme_page_fields() {
	return array(
		'duration'          => array( __( 'Duration', 'jodyn' ), 'text', __( 'e.g. 2 days. Leave empty to count the days from the start and end dates.', 'jodyn' ) ),
		'who'               => array( __( 'Who should attend', 'jodyn' ), 'list', __( 'One role per line.', 'jodyn' ) ),
		'outcomes'          => array( __( 'Learning outcomes', 'jodyn' ), 'list', __( 'One outcome per line. "By the end of this programme, you will be able to:" is added for you.', 'jodyn' ) ),
		'modules'           => array( __( 'Programme modules', 'jodyn' ), 'list', __( 'One module per line, as Title: short description. For example: The Manager’s Mindset: Understanding your new role…', 'jodyn' ) ),
		'includes'          => array( __( 'What’s included', 'jodyn' ), 'list', __( 'One item per line, e.g. Course materials.', 'jodyn' ) ),
		'facilitator_name'  => array( __( 'Facilitator: name (optional)', 'jodyn' ), 'text', __( 'Leave empty to leave the facilitator section out.', 'jodyn' ) ),
		'facilitator_role'  => array( __( 'Facilitator: title', 'jodyn' ), 'text', '' ),
		'facilitator_photo' => array( __( 'Facilitator: photo link', 'jodyn' ), 'url', __( 'Upload the photo to the Media Library and paste its file URL here.', 'jodyn' ) ),
		'facilitator_bio'   => array( __( 'Facilitator: short bio', 'jodyn' ), 'list', '' ),
		'brochure'          => array( __( 'Brochure PDF link (optional)', 'jodyn' ), 'url', __( 'Upload the PDF to the Media Library and paste its file URL here. Shows Download Brochure.', 'jodyn' ) ),
	);
}

/**
 * "Programme page" box under the editor.
 */
function jodyn_programme_page_meta_box() {
	add_meta_box( 'jodyn-programme-page', __( 'Programme page', 'jodyn' ), 'jodyn_programme_page_meta_box_html', 'jodyn_programme', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'jodyn_programme_page_meta_box' );

/**
 * @param WP_Post $post Programme.
 */
function jodyn_programme_page_meta_box_html( $post ) {
	echo '<p class="description">' . esc_html__( 'Everything on the programme’s own page. Write the overview in the editor above and the short summary in the Excerpt. Any section left empty is left off the page.', 'jodyn' ) . '</p>';
	foreach ( jodyn_programme_page_fields() as $key => $field ) {
		$value = get_post_meta( $post->ID, "_jodyn_$key", true );
		echo '<p><label for="jodyn-' . esc_attr( $key ) . '"><strong>' . esc_html( $field[0] ) . '</strong></label><br>';
		if ( 'list' === $field[1] ) {
			echo '<textarea id="jodyn-' . esc_attr( $key ) . '" name="jodyn_' . esc_attr( $key ) . '" rows="5" style="width:100%">' . esc_textarea( $value ) . '</textarea>';
		} else {
			echo '<input type="' . ( 'url' === $field[1] ? 'url' : 'text' ) . '" id="jodyn-' . esc_attr( $key ) . '" name="jodyn_' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" style="width:100%">';
		}
		if ( $field[2] ) {
			echo '<br><span class="description">' . esc_html( $field[2] ) . '</span>';
		}
		echo '</p>';
	}
}

/**
 * A list field as its non-empty lines.
 *
 * @param int    $post_id Programme ID.
 * @param string $key     Field key from jodyn_programme_page_fields().
 * @return string[]
 */
function jodyn_programme_lines( $post_id, $key ) {
	$lines = preg_split( '/\r\n|\r|\n/', (string) get_post_meta( $post_id, "_jodyn_$key", true ) );
	return array_values( array_filter( array_map( 'trim', $lines ), 'strlen' ) );
}

/**
 * The modules as array( title, description ). "Module 3: Title: description" and
 * "Title: description" both work; a line without a colon is a title on its own.
 *
 * @param int $post_id Programme ID.
 * @return array<int, string[]>
 */
function jodyn_programme_modules( $post_id ) {
	$modules = array();
	foreach ( jodyn_programme_lines( $post_id, 'modules' ) as $line ) {
		$line      = preg_replace( '/^module\s*\d+\s*[:.\-–]\s*/iu', '', $line );
		$parts     = array_map( 'trim', explode( ':', $line, 2 ) );
		$modules[] = array( $parts[0], isset( $parts[1] ) ? $parts[1] : '' );
	}
	return $modules;
}

/**
 * The client's fully written example programme (content/pages/programme.md, "Example"). Site
 * setup (version 6) writes it into Leadership Essentials for New Managers while that programme's
 * page fields are still empty, so there is one complete page to show.
 *
 * @return array
 */
function jodyn_programme_example() {
	return array(
		'title'    => 'Leadership Essentials for New Managers',
		'summary'  => 'Make a confident transition from team member to team leader. Learn how to lead former peers, delegate effectively, give feedback and motivate your team to deliver results.',
		'overview' => array(
			'Stepping into management for the first time is one of the biggest transitions in any career. The skills that made you excellent at your job are not the same skills you need to lead others. New managers are often expected to deliver results through people from day one, with little preparation for what that really involves.',
			'This practical two-day programme equips new and aspiring managers with the essential skills to lead with confidence. Through real workplace scenarios, practical tools and guided practice, participants learn how to set clear expectations, delegate well, hold productive conversations and build a motivated, accountable team.',
			'Participants leave with a personal leadership action plan and a toolkit they can apply immediately with their teams.',
		),
		'meta'     => array(
			'duration' => '2 days',
			'time'     => '[9:00 am to 4:00 pm]',
			'who'      => "Newly promoted supervisors, team leads and managers\nHigh-potential employees preparing for a leadership role\nManagers with less than two years of people-management experience\nTechnical experts who now lead teams",
			'outcomes' => "Understand the shift from individual contributor to manager and what is now expected of you;\nLead former peers with confidence and credibility;\nSet clear goals and expectations for your team;\nDelegate tasks effectively and follow up without micromanaging;\nGive constructive feedback and handle difficult conversations;\nMotivate team members and build a culture of accountability; and\nCreate a personal 90-day leadership action plan.",
			'modules'  => "The Manager's Mindset: Understanding your new role, responsibilities and the expectations of your team and leaders.\nLeading Former Peers: Building credibility, setting boundaries and earning respect in your new role.\nSetting Goals and Expectations: Translating organisational goals into clear team and individual objectives.\nDelegation That Works: What to delegate, to whom and how, plus how to follow up without micromanaging.\nFeedback and Difficult Conversations: Practical frameworks for giving feedback and addressing underperformance.\nMotivating and Developing Your Team: Understanding what drives people and building an engaged, accountable team.\nYour Leadership Action Plan: Bringing it all together into a practical 90-day plan.",
			'includes' => "Course materials\nPractical toolkit\nCertificate of participation\n[Lunch and refreshments for in-person sessions]",
		),
	);
}

/**
 * Writes the example into its programme while that programme's page is still empty.
 */
function jodyn_programme_apply_example() {
	$example = jodyn_programme_example();
	$found   = get_posts(
		array(
			'post_type'   => 'jodyn_programme',
			'post_status' => 'any',
			'title'       => $example['title'],
			'numberposts' => 1,
		)
	);
	if ( ! $found || '' !== (string) get_post_meta( $found[0]->ID, '_jodyn_who', true ) ) {
		return;
	}
	$post    = $found[0];
	$content = trim( wp_strip_all_tags( $post->post_content ) );
	$update  = array( 'ID' => $post->ID );
	if ( '' === $content || trim( $post->post_excerpt ) === $content ) {
		$update['post_content'] = '<p>' . implode( "</p>\n\n<p>", $example['overview'] ) . '</p>';
	}
	if ( '' === trim( $post->post_excerpt ) ) {
		$update['post_excerpt'] = $example['summary'];
	}
	if ( count( $update ) > 1 ) {
		wp_update_post( $update );
	}
	foreach ( $example['meta'] as $key => $value ) {
		if ( '' === (string) get_post_meta( $post->ID, "_jodyn_$key", true ) ) {
			update_post_meta( $post->ID, "_jodyn_$key", $value );
		}
	}
}

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
