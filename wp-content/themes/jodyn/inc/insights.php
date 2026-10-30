<?php
/**
 * Insights (the blog, /insights/): the featured article, reading time and category helpers.
 *
 * The featured article is the newest post ticked "Stick to the top of the blog" in the editor,
 * or the newest post when none is. Copy: content/pages/insights.md (approved).
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The post shown as the featured article at the top of /insights/, or null when there are none.
 *
 * @return WP_Post|null
 */
function jodyn_featured_post() {
	static $featured = false;
	if ( false !== $featured ) {
		return $featured;
	}
	$args   = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'numberposts'         => 1,
		'ignore_sticky_posts' => true,
		'suppress_filters'    => false,
	);
	$sticky = array_filter( array_map( 'intval', (array) get_option( 'sticky_posts' ) ) );
	$posts  = $sticky ? get_posts( $args + array( 'post__in' => $sticky ) ) : array();
	if ( ! $posts ) {
		$posts = get_posts( $args );
	}
	$featured = $posts ? $posts[0] : null;
	return $featured;
}

/**
 * Estimated reading time in minutes (about 200 words a minute, at least 1).
 *
 * @param int|WP_Post $post Post.
 */
function jodyn_reading_time( $post ) {
	$words = str_word_count( wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', $post ) ) ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * "5 min read".
 *
 * @param int|WP_Post $post Post.
 */
function jodyn_reading_label( $post ) {
	/* translators: %d: minutes. */
	return sprintf( _n( '%d min read', '%d min read', jodyn_reading_time( $post ), 'jodyn' ), jodyn_reading_time( $post ) );
}

/**
 * The post's first category (skipping "Uncategorized"), or null.
 *
 * @param int|WP_Post $post Post.
 * @return WP_Term|null
 */
function jodyn_post_category( $post ) {
	foreach ( (array) get_the_category( is_object( $post ) ? $post->ID : $post ) as $term ) {
		if ( 'uncategorized' !== $term->slug ) {
			return $term;
		}
	}
	return null;
}

/**
 * A short excerpt (about 25 words) for cards.
 *
 * @param int|WP_Post $post Post.
 */
function jodyn_card_excerpt( $post, $words = 25 ) {
	return wp_trim_words( get_the_excerpt( $post ), $words, '…' );
}

/**
 * The approved categories, in tab order (slug => name). Site setup creates any that are missing.
 */
function jodyn_insight_categories() {
	return array(
		'strategy'             => __( 'Strategy', 'jodyn' ),
		'systems-audit'        => __( 'Systems Audit', 'jodyn' ),
		'tax'                  => __( 'Tax', 'jodyn' ),
		'people-culture'       => __( 'People & Culture', 'jodyn' ),
		'performance'          => __( 'Performance', 'jodyn' ),
		'leadership'           => __( 'Leadership', 'jodyn' ),
		'learning-development' => __( 'Learning & Development', 'jodyn' ),
		'case-studies'         => __( 'Case Studies', 'jodyn' ),
		'news-events'          => __( 'News & Events', 'jodyn' ),
	);
}

/**
 * The category tabs: the approved categories in order, then any others the editor has added
 * that have articles. "Uncategorized" never shows.
 *
 * @return WP_Term[]
 */
function jodyn_insight_tabs() {
	$tabs = array();
	foreach ( array_keys( jodyn_insight_categories() ) as $slug ) {
		$term = get_term_by( 'slug', $slug, 'category' );
		if ( $term ) {
			$tabs[ $term->term_id ] = $term;
		}
	}
	foreach ( get_categories( array( 'hide_empty' => true ) ) as $term ) {
		if ( 'uncategorized' !== $term->slug && ! isset( $tabs[ $term->term_id ] ) ) {
			$tabs[ $term->term_id ] = $term;
		}
	}
	return array_values( $tabs );
}

/**
 * The Insights page address (Settings → Reading → Posts page).
 */
function jodyn_blog_url() {
	$blog = (int) get_option( 'page_for_posts' );
	return $blog ? get_permalink( $blog ) : home_url( '/insights/' );
}

/**
 * On /insights/ the grid leaves out the featured article (shown above it) on every page, so
 * page 2 follows on from page 1, and pinned posts are not pulled to the top a second time.
 */
add_action(
	'pre_get_posts',
	static function ( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_home() ) {
			return;
		}
		$query->set( 'ignore_sticky_posts', true );
		$featured = jodyn_featured_post();
		if ( $featured ) {
			$query->set( 'post__not_in', array( $featured->ID ) );
		}
	}
);

/**
 * The footer's lime card on /insights/ and the category pages (insights.md, footer card).
 */
add_filter(
	'jodyn_footer_cta',
	static function ( $cta ) {
		if ( ! is_home() && ! is_category() ) {
			return $cta;
		}
		$cta['title']   = 'Turn Insight into <em>Action</em>';
		$cta['text']    = 'Ready to apply these ideas in your organisation? Our advisors and facilitators can help you put them into practice.';
		$cta['buttons'] = array(
			array( 'Talk to an Advisor', '/contact-us/' ),
			array( 'View Training Programmes', '/training/programmes/' ),
		);
		return $cta;
	}
);

/*
 * ---- Single article (single-post.php, template-parts/insights/article.php) ----
 */

/**
 * The article's content with an id on every H2 (for "On this page"), and the list of those
 * headings as array( id, text ).
 *
 * @return array{0: string, 1: array<int, array{0: string, 1: string}>}
 */
function jodyn_article_content() {
	$html  = apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core filter.
	$heads = array();
	$used  = array();
	$html  = preg_replace_callback(
		'#<h2([^>]*)>(.*?)</h2>#is',
		static function ( $m ) use ( &$heads, &$used ) {
			$text = trim( wp_strip_all_tags( $m[2] ) );
			if ( preg_match( '#\sid=["\']([^"\']+)["\']#i', $m[1], $id ) ) {
				$id = $id[1];
			} else {
				$base = sanitize_title( $text );
				$base = $base ? $base : 'section';
				$id   = $base;
				for ( $n = 2; isset( $used[ $id ] ); $n++ ) {
					$id = "$base-$n";
				}
				$m[1] .= ' id="' . esc_attr( $id ) . '"';
			}
			$used[ $id ] = true;
			$heads[]     = array( $id, $text );
			return '<h2' . $m[1] . '>' . $m[2] . '</h2>';
		},
		$html
	);
	return array( $html, $heads );
}

/**
 * Share links for the current post: LinkedIn, WhatsApp, X and email (copy link is a button).
 *
 * @return array<string, array{0: string, 1: string}> network => array( label, url )
 */
function jodyn_share_links() {
	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( html_entity_decode( get_the_title(), ENT_QUOTES, 'UTF-8' ) );
	return array(
		'linkedin' => array( 'LinkedIn', "https://www.linkedin.com/sharing/share-offsite/?url=$url" ),
		'whatsapp' => array( 'WhatsApp', "https://wa.me/?text=$title%20$url" ),
		'x'        => array( 'X', "https://x.com/intent/post?url=$url&text=$title" ),
		'email'    => array( __( 'email', 'jodyn' ), "mailto:?subject=$title&body=$url" ),
	);
}

/**
 * Three related articles: the newest in the same category, topped up with the newest overall.
 *
 * @return WP_Post[]
 */
function jodyn_related_posts( $post_id, $count = 3 ) {
	$args    = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'numberposts'         => $count,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true,
		'suppress_filters'    => false,
	);
	$cat     = jodyn_post_category( $post_id );
	$related = $cat ? get_posts( $args + array( 'cat' => $cat->term_id ) ) : array();
	if ( count( $related ) < $count ) {
		$args['numberposts']  = $count - count( $related );
		$args['post__not_in'] = array_merge( array( $post_id ), wp_list_pluck( $related, 'ID' ) );
		$related              = array_merge( $related, get_posts( $args ) );
	}
	return $related;
}

/**
 * A "Job title" box on each user's profile, shown in the article's author box.
 */
add_filter(
	'user_contactmethods',
	static function ( $methods ) {
		$methods['jodyn_job_title'] = __( 'Job title (shown on Insights articles)', 'jodyn' );
		return $methods;
	}
);

/**
 * Two box styles the writer can pick for a Group block in the editor: the related programme box
 * halfway through an article, and Key takeaways near the end (insights.md, article template).
 */
add_action(
	'init',
	static function () {
		register_block_style(
			'core/group',
			array(
				'name'  => 'jodyn-callout',
				'label' => __( 'JoDyn: related programme box', 'jodyn' ),
			)
		);
		register_block_style(
			'core/group',
			array(
				'name'  => 'jodyn-takeaways',
				'label' => __( 'JoDyn: key takeaways', 'jodyn' ),
			)
		);
	}
);

/**
 * The photo across the top of the article: its "Article banner", or none (a plain forest header).
 * The featured image is for the Insights cards and link previews only: it is often a designed
 * graphic with the title already in it, which would repeat under the page's own title.
 *
 * @param int|WP_Post|null $post Post (default: the current one).
 * @return int Attachment ID, or 0 for none.
 */
function jodyn_article_banner_id( $post = null ) {
	$post   = get_post( $post );
	$banner = $post ? (int) get_post_meta( $post->ID, '_jodyn_banner', true ) : 0;
	return $banner && wp_attachment_is_image( $banner ) ? $banner : 0;
}

/**
 * "Article banner" box beside the featured image box when editing an article.
 */
add_action(
	'add_meta_boxes_post',
	static function () {
		add_meta_box( 'jodyn-banner', __( 'Article banner', 'jodyn' ), 'jodyn_banner_meta_box_html', 'post', 'side', 'low' );
	}
);

/**
 * @param WP_Post $post Article.
 */
function jodyn_banner_meta_box_html( $post ) {
	$banner = (int) get_post_meta( $post->ID, '_jodyn_banner', true );
	wp_nonce_field( 'jodyn_banner_save', 'jodyn_banner_nonce' );
	?>
	<div class="jodyn-banner" data-jodyn-banner>
		<input type="hidden" name="jodyn_banner" value="<?php echo $banner ? (int) $banner : ''; ?>" data-jodyn-banner-id>
		<div data-jodyn-banner-preview><?php echo $banner ? wp_get_attachment_image( $banner, 'medium', false, array( 'style' => 'display:block;width:100%;height:auto;margin-bottom:8px' ) ) : ''; ?></div>
		<p class="description" style="margin-top:0"><?php esc_html_e( 'A photo for the top of the article page, behind the title (no text in it: the page writes the title over it). Leave empty for a plain green top. The featured image is for the Insights cards and shared links, best 2000 × 900 pixels.', 'jodyn' ); ?></p>
		<p>
			<button type="button" class="button" data-jodyn-banner-choose><?php echo $banner ? esc_html__( 'Change banner', 'jodyn' ) : esc_html__( 'Choose banner', 'jodyn' ); ?></button>
			<button type="button" class="button-link button-link-delete" data-jodyn-banner-remove<?php echo $banner ? '' : ' hidden'; ?>><?php esc_html_e( 'Remove', 'jodyn' ); ?></button>
		</p>
	</div>
	<?php
}

/**
 * Saves the banner choice.
 *
 * @param int $post_id Article.
 */
function jodyn_banner_save( $post_id ) {
	if ( ! isset( $_POST['jodyn_banner_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['jodyn_banner_nonce'] ), 'jodyn_banner_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$banner = isset( $_POST['jodyn_banner'] ) ? absint( $_POST['jodyn_banner'] ) : 0;
	if ( $banner && wp_attachment_is_image( $banner ) ) {
		update_post_meta( $post_id, '_jodyn_banner', $banner );
	} else {
		delete_post_meta( $post_id, '_jodyn_banner' );
	}
}
add_action( 'save_post_post', 'jodyn_banner_save' );

/**
 * The Media Library picker for the banner box.
 *
 * @param string $hook Admin screen.
 */
function jodyn_banner_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) || 'post' !== get_current_screen()->post_type ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'jodyn-admin-banner', get_template_directory_uri() . '/assets/js/admin-banner.js', array( 'jquery' ), jodyn_asset_version( 'assets/js/admin-banner.js' ), true );
	wp_localize_script(
		'jodyn-admin-banner',
		'jodynBanner',
		array(
			'title'  => __( 'Choose the article banner', 'jodyn' ),
			'button' => __( 'Use as banner', 'jodyn' ),
			'change' => __( 'Change banner', 'jodyn' ),
			'choose' => __( 'Choose banner', 'jodyn' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'jodyn_banner_admin_assets' );

/**
 * Articles with a banner open with the photo under the see-through header.
 */
add_filter(
	'jodyn_header_is_overlay',
	static function ( $overlay ) {
		return $overlay || ( is_singular( 'post' ) && jodyn_article_banner_id() );
	}
);

/**
 * The footer's lime card on articles: the standard end-of-article call to action.
 */
add_filter(
	'jodyn_footer_cta',
	static function ( $cta ) {
		if ( ! is_singular( 'post' ) ) {
			return $cta;
		}
		$cta['title']   = 'Ready to Put These Ideas into <em>Practice?</em>';
		$cta['text']    = 'JoDyn helps organisations build stronger leaders, clearer strategies, sound systems and compliant tax positions through practical advisory and training.';
		$cta['buttons'] = array(
			array( 'Talk to an Advisor', '/contact-us/' ),
			array( 'View Related Programmes', '/training/calendar/' ),
		);
		return $cta;
	}
);
