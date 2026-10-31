<?php
/**
 * Article authors: Posts → Authors, a list like Categories, so articles can be written by
 * different people without each one needing a WordPress login.
 *
 * Each author has a name, a job title, a short bio (the Description field) and a photo. In the
 * article editor, the Authors box in the sidebar picks one or more; the article shows their
 * photo and name under the title and an "About the author" box for each. An article with no
 * author ticked falls back to the WordPress user who published it (Users → Profile: name, job
 * title and biographical info), shown with their initials.
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Authors list. Not public: authors have no page of their own, only their articles do.
 */
function jodyn_register_writers() {
	register_taxonomy(
		'jodyn_writer',
		'post',
		array(
			'labels'            => array(
				'name'          => __( 'Authors', 'jodyn' ),
				'singular_name' => __( 'Author', 'jodyn' ),
				'menu_name'     => __( 'Authors', 'jodyn' ),
				'all_items'     => __( 'All authors', 'jodyn' ),
				'edit_item'     => __( 'Edit author', 'jodyn' ),
				'update_item'   => __( 'Update author', 'jodyn' ),
				'add_new_item'  => __( 'Add author', 'jodyn' ),
				'new_item_name' => __( 'Author name', 'jodyn' ),
				'search_items'  => __( 'Search authors', 'jodyn' ),
				'not_found'     => __( 'No authors yet.', 'jodyn' ),
				'back_to_items' => __( '← Back to authors', 'jodyn' ),
			),
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_in_rest'      => true, // the Authors box in the block editor
			'show_admin_column' => true,
			'hierarchical'      => true, // tick boxes in the editor, so names are picked, not retyped
			'rewrite'           => false,
			'query_var'         => false,
			'capabilities'      => array(
				'manage_terms' => 'manage_categories',
				'edit_terms'   => 'manage_categories',
				'delete_terms' => 'manage_categories',
				'assign_terms' => 'edit_posts',
			),
		)
	);
}
add_action( 'init', 'jodyn_register_writers' );

/**
 * Initials for the avatar: the first letter of the first two words.
 *
 * @param string $name Name.
 * @return string
 */
function jodyn_initials( $name ) {
	preg_match_all( '/(?<!\p{L})\p{L}/u', $name, $m );
	return mb_strtoupper( implode( '', array_slice( $m[0], 0, 2 ) ) );
}

/**
 * One author's details from Posts → Authors.
 *
 * @param WP_Term $term Author.
 * @return array{name: string, job: string, bio: string, photo: int, initials: string}
 */
function jodyn_writer_details( $term ) {
	$photo = (int) get_term_meta( $term->term_id, 'jodyn_photo', true );
	return array(
		'name'     => $term->name,
		'job'      => (string) get_term_meta( $term->term_id, 'jodyn_job_title', true ),
		'bio'      => $term->description,
		'photo'    => $photo && wp_attachment_is_image( $photo ) ? $photo : 0,
		'initials' => jodyn_initials( $term->name ),
	);
}

/**
 * The authors of an article, or the WordPress user who published it when none is ticked.
 *
 * @param int|WP_Post|null $post Article (default: the current one).
 * @return array[] jodyn_writer_details() for each.
 */
function jodyn_article_authors( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return array();
	}
	$terms = get_the_terms( $post, 'jodyn_writer' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		return array_map( 'jodyn_writer_details', $terms );
	}
	$name = (string) get_the_author_meta( 'display_name', $post->post_author );
	if ( '' === $name ) {
		return array();
	}
	return array(
		array(
			'name'     => $name,
			'job'      => (string) get_the_author_meta( 'jodyn_job_title', $post->post_author ),
			'bio'      => (string) get_the_author_meta( 'description', $post->post_author ),
			'photo'    => 0,
			'initials' => jodyn_initials( $name ),
		),
	);
}

/**
 * The round avatar: the author's photo, or their initials.
 *
 * @param array  $author One of jodyn_article_authors().
 * @param string $class  Extra class ('is-lg' for the author box).
 * @return string HTML.
 */
function jodyn_author_avatar( $author, $class = '' ) {
	$classes = trim( 'jd-art__avatar ' . $class . ( $author['photo'] ? ' has-photo' : '' ) );
	$inner   = $author['photo']
		? wp_get_attachment_image( $author['photo'], 'thumbnail', false, array( 'alt' => '', 'loading' => 'lazy' ) )
		: esc_html( $author['initials'] );
	return '<span class="' . esc_attr( $classes ) . '" aria-hidden="true">' . $inner . '</span>';
}

/**
 * "Job title" and "Photo" on the add and edit author screens. WordPress's Description field
 * above them is the bio (relabelled "Short bio" by jodyn_writer_admin_screen()).
 *
 * @param WP_Term|string $term The author being edited, or the taxonomy name when adding.
 */
function jodyn_writer_fields( $term ) {
	$editing = $term instanceof WP_Term;
	$job     = $editing ? (string) get_term_meta( $term->term_id, 'jodyn_job_title', true ) : '';
	$photo   = $editing ? (int) get_term_meta( $term->term_id, 'jodyn_photo', true ) : 0;
	$fields  = array(
		'job'   => array( __( 'Job title', 'jodyn' ), '<input type="text" name="jodyn_job_title" id="jodyn-job-title" value="' . esc_attr( $job ) . '">', __( 'Shown under the name, e.g. Senior Consultant, JoDyn Advisory and Consulting.', 'jodyn' ) ),
		'photo' => array( __( 'Photo', 'jodyn' ), jodyn_media_picker( 'jodyn_photo', $photo, __( 'Choose photo', 'jodyn' ), __( 'Change photo', 'jodyn' ), __( 'Choose the author photo', 'jodyn' ), __( 'Use as photo', 'jodyn' ), 'thumbnail' ), __( 'A square head-and-shoulders photo works best. Without one, the initials show.', 'jodyn' ) ),
	);
	wp_nonce_field( 'jodyn_writer_save', 'jodyn_writer_nonce' );
	foreach ( $fields as $key => $field ) {
		if ( $editing ) {
			printf( '<tr class="form-field"><th scope="row"><label for="jodyn-%1$s">%2$s</label></th><td>%3$s<p class="description">%4$s</p></td></tr>', esc_attr( $key ), esc_html( $field[0] ), $field[1], esc_html( $field[2] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above from escaped parts.
		} else {
			printf( '<div class="form-field"><label for="jodyn-%1$s">%2$s</label>%3$s<p>%4$s</p></div>', esc_attr( $key ), esc_html( $field[0] ), $field[1], esc_html( $field[2] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above from escaped parts.
		}
	}
}
add_action( 'jodyn_writer_add_form_fields', 'jodyn_writer_fields' );
add_action( 'jodyn_writer_edit_form_fields', 'jodyn_writer_fields' );

/**
 * Saves the job title and photo.
 *
 * @param int $term_id Author.
 */
function jodyn_writer_save( $term_id ) {
	if ( ! isset( $_POST['jodyn_writer_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['jodyn_writer_nonce'] ), 'jodyn_writer_save' ) || ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	$job = isset( $_POST['jodyn_job_title'] ) ? sanitize_text_field( wp_unslash( $_POST['jodyn_job_title'] ) ) : '';
	$job ? update_term_meta( $term_id, 'jodyn_job_title', $job ) : delete_term_meta( $term_id, 'jodyn_job_title' );
	$photo = isset( $_POST['jodyn_photo'] ) ? absint( $_POST['jodyn_photo'] ) : 0;
	$photo && wp_attachment_is_image( $photo ) ? update_term_meta( $term_id, 'jodyn_photo', $photo ) : delete_term_meta( $term_id, 'jodyn_photo' );
}
add_action( 'created_jodyn_writer', 'jodyn_writer_save' );
add_action( 'edited_jodyn_writer', 'jodyn_writer_save' );

/**
 * The Authors list: a photo column, no slug, and the bio column named as such.
 *
 * @param string[] $columns Columns.
 * @return string[]
 */
function jodyn_writer_columns( $columns ) {
	unset( $columns['slug'] );
	if ( isset( $columns['description'] ) ) {
		$columns['description'] = __( 'Short bio', 'jodyn' );
	}
	return array_slice( $columns, 0, 1, true ) + array( 'jodyn_photo' => __( 'Photo', 'jodyn' ) ) + array_slice( $columns, 1, null, true );
}
add_filter( 'manage_edit-jodyn_writer_columns', 'jodyn_writer_columns' );

/**
 * @param string $out     Column HTML so far.
 * @param string $column  Column.
 * @param int    $term_id Author.
 * @return string
 */
function jodyn_writer_column( $out, $column, $term_id ) {
	if ( 'jodyn_photo' !== $column ) {
		return $out;
	}
	return jodyn_author_avatar( jodyn_writer_details( get_term( $term_id, 'jodyn_writer' ) ), 'is-admin' );
}
add_filter( 'manage_jodyn_writer_custom_column', 'jodyn_writer_column', 10, 3 );

/**
 * The Authors screens: only the fields that matter (no slug or parent), "Description" called
 * "Short bio", and the avatar style in the list.
 */
function jodyn_writer_admin_screen() {
	$screen = get_current_screen();
	if ( ! $screen || 'jodyn_writer' !== $screen->taxonomy ) {
		return;
	}
	?>
	<style>.term-slug-wrap,.term-parent-wrap{display:none}.column-jodyn_photo{width:64px}.jd-art__avatar.is-admin{display:grid;place-items:center;width:40px;height:40px;overflow:hidden;border-radius:50%;background:#233831;color:#e5fd4a;font-weight:700}.jd-art__avatar.is-admin img{width:100%;height:100%;object-fit:cover}</style>
	<?php
	add_action(
		'admin_footer',
		static function () {
			$label = wp_json_encode( __( 'Short bio', 'jodyn' ) );
			$help  = wp_json_encode( __( 'A sentence or two about the author, shown in the box under their articles.', 'jodyn' ) );
			echo "<script>document.querySelectorAll('.term-description-wrap').forEach(function(w){var l=w.querySelector('label');if(l)l.textContent={$label};var p=w.querySelector('p');if(p)p.textContent={$help};});</script>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-encoded.
		}
	);
}
add_action( 'admin_head-edit-tags.php', 'jodyn_writer_admin_screen' );
add_action( 'admin_head-term.php', 'jodyn_writer_admin_screen' );

/**
 * A Media Library picker: a hidden ID field, a preview, and Choose / Remove buttons
 * (assets/js/admin-media.js).
 *
 * @param string $name    Field name.
 * @param int    $id      Current attachment ID, or 0.
 * @param string $choose  Button label with nothing chosen.
 * @param string $change  Button label with something chosen.
 * @param string $title   Media Library window title.
 * @param string $button  Media Library select button.
 * @param string $size    Preview size.
 * @return string HTML.
 */
function jodyn_media_picker( $name, $id, $choose, $change, $title, $button, $size = 'medium' ) {
	$preview = $id ? wp_get_attachment_image( $id, $size, false, array( 'style' => 'display:block;max-width:100%;height:auto;margin-bottom:8px' ) ) : '';
	return sprintf(
		'<div data-jodyn-media data-title="%1$s" data-button="%2$s" data-choose="%3$s" data-change="%4$s" data-size="%5$s">'
		. '<input type="hidden" name="%6$s" value="%7$s" data-jodyn-media-id>'
		. '<div data-jodyn-media-preview>%8$s</div>'
		. '<p><button type="button" class="button" data-jodyn-media-choose>%9$s</button> '
		. '<button type="button" class="button-link button-link-delete" data-jodyn-media-remove%10$s>%11$s</button></p>'
		. '</div>',
		esc_attr( $title ),
		esc_attr( $button ),
		esc_attr( $choose ),
		esc_attr( $change ),
		esc_attr( $size ),
		esc_attr( $name ),
		$id ? (int) $id : '',
		$preview,
		esc_html( $id ? $change : $choose ),
		$id ? '' : ' hidden',
		esc_html__( 'Remove', 'jodyn' )
	);
}

/**
 * The picker's script on the article editor (Article banner) and the Authors screens.
 *
 * @param string $hook Admin screen.
 */
function jodyn_media_picker_assets( $hook ) {
	$screen = get_current_screen();
	$post   = in_array( $hook, array( 'post.php', 'post-new.php' ), true ) && 'post' === $screen->post_type;
	$writer = in_array( $hook, array( 'edit-tags.php', 'term.php' ), true ) && 'jodyn_writer' === $screen->taxonomy;
	if ( ! $post && ! $writer ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'jodyn-admin-media', get_template_directory_uri() . '/assets/js/admin-media.js', array( 'jquery' ), jodyn_asset_version( 'assets/js/admin-media.js' ), true );
}
add_action( 'admin_enqueue_scripts', 'jodyn_media_picker_assets' );
