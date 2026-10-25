<?php
/**
 * Insights (the blog, /insights/): the featured article, reading time and category helpers.
 *
 * The featured article is the newest post ticked "Stick to the top of the blog" in the editor,
 * or the newest post when none is. Copy: content/pages/insights.md (approved).
 *
 * @package JoDyn
 */

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
 * Articles with a featured image open with the photo under the see-through header.
 */
add_filter(
	'jodyn_header_is_overlay',
	static function ( $overlay ) {
		return $overlay || ( is_singular( 'post' ) && has_post_thumbnail() );
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
