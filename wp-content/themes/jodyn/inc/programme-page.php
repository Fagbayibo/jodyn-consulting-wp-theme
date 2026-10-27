<?php
/**
 * A programme's own page (/programme/[name]/, single-jodyn_programme.php), option B.
 * The fields come from inc/programmes.php; the layout is template-parts/programme/page.php.
 * Copy: content/pages/programme.md (approved).
 *
 * @package JoDyn
 */

/**
 * Everything the programme page shows, built from the programme's fields. Empty sections come
 * back empty, and the template leaves them out.
 *
 * @param int|WP_Post $post Programme.
 * @return array
 */
function jodyn_programme_page( $post ) {
	$post = get_post( $post );
	$p    = jodyn_programme_data( $post );

	// The date range alone ("19–20 November 2026"); jodyn_programme_data() adds " · 2 days".
	$p['range'] = $p['upcoming'] ? explode( ' · ', $p['when'] )[0] : '';

	$duration = trim( (string) get_post_meta( $post->ID, '_jodyn_duration', true ) );
	if ( '' === $duration && $p['upcoming'] ) {
		$parts    = explode( ' · ', $p['when'] );
		$duration = isset( $parts[1] ) ? $parts[1] : '';
	}
	$p['duration'] = $duration;

	// The overview is the editor content, unless it only repeats the summary.
	$content       = trim( (string) $post->post_content );
	$p['overview'] = '' !== $content && trim( wp_strip_all_tags( $content ) ) !== trim( $p['summary'] ) ? apply_filters( 'the_content', $content ) : ''; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core filter.

	// A programme with no run yet only shows a format the client has chosen (the stored value
	// falls back to In-person when empty).
	$p['show_format'] = $p['upcoming'] || '' !== (string) get_post_meta( $post->ID, '_jodyn_format', true );

	$p['who']      = jodyn_programme_lines( $post->ID, 'who' );
	$p['outcomes'] = jodyn_programme_lines( $post->ID, 'outcomes' );
	$p['modules']  = jodyn_programme_modules( $post->ID );
	$p['includes'] = jodyn_programme_lines( $post->ID, 'includes' );
	$p['brochure'] = (string) get_post_meta( $post->ID, '_jodyn_brochure', true );
	$p['facilitator'] = array(
		'name'  => trim( (string) get_post_meta( $post->ID, '_jodyn_facilitator_name', true ) ),
		'role'  => trim( (string) get_post_meta( $post->ID, '_jodyn_facilitator_role', true ) ),
		'photo' => (string) get_post_meta( $post->ID, '_jodyn_facilitator_photo', true ),
		'bio'   => jodyn_programme_lines( $post->ID, 'facilitator_bio' ),
	);

	// What the main button does: register, ask about the next date (fully booked), or enquire
	// (no date yet). The last two open the contact form with Training selected and this programme named.
	$ask = home_url( '/contact-us/?enquiry=training&programme=' . rawurlencode( $p['slug'] ) );
	if ( $p['open'] ) {
		$p['action'] = array( __( 'Register Now', 'jodyn' ), $p['url'] );
	} elseif ( $p['upcoming'] ) {
		$p['action'] = array( __( 'Ask About the Next Date', 'jodyn' ), $ask );
	} else {
		$p['action'] = array( __( 'Enquire About This Programme', 'jodyn' ), $ask );
	}
	$p['in_house'] = home_url( '/contact-us/?enquiry=custom-training&programme=' . rawurlencode( $p['slug'] ) );

	// The group discount line (Customize → Training calendar), the same on every programme.
	$off  = rtrim( trim( (string) get_theme_mod( 'jodyn_group_discount', '' ) ), '%' );
	$size = trim( (string) get_theme_mod( 'jodyn_group_size', '' ) );
	/* translators: 1: discount percentage, 2: number of participants. */
	$p['discount'] = sprintf( __( '%1$s%% group discount for %2$s+ participants', 'jodyn' ), '' === $off ? '[X]' : $off, '' === $size ? '[3]' : $size );

	// Three more from the same area: scheduled runs first, soonest first.
	$p['related'] = array_slice(
		array_values(
			array_filter(
				jodyn_get_all_programmes(),
				static function ( $other ) use ( $p ) {
					return $other['area'] === $p['area'] && $other['id'] !== $p['id'];
				}
			)
		),
		0,
		3
	);

	// Standard questions from the FAQs page (content/pages/programme.md, section 10).
	$wanted   = array( 'How do I register for a programme?', 'How do I pay for a programme?', 'Will I receive a certificate?', 'What is your cancellation and refund policy?' );
	$p['faq'] = array();
	foreach ( jodyn_faqs_content()['groups'] as $group ) {
		foreach ( $group['items'] as $item ) {
			if ( in_array( $item[0], $wanted, true ) ) {
				$p['faq'][] = $item;
			}
		}
	}
	return $p;
}

/**
 * Programme pages end with the Training Calendar's footer card.
 */
add_filter(
	'jodyn_footer_cta',
	static function ( $cta ) {
		if ( ! is_singular( 'jodyn_programme' ) ) {
			return $cta;
		}
		$cta['title']   = 'Can\'t Find What You <em>Need?</em>';
		$cta['text']    = 'Our team can recommend the right programme for your goals or design one specifically for your organisation.';
		$cta['buttons'] = array(
			array( 'Talk to Our Training Team', '/contact-us/?enquiry=training' ),
		);
		return $cta;
	}
);

/**
 * In the header menu, a programme page belongs under Training: WordPress marks the posts page
 * (Insights) as the parent of any single item that isn't a page, so move that mark to Training
 * programmes and its Training dropdown.
 *
 * @param array $items Menu items with their classes.
 * @return array
 */
function jodyn_programme_menu_current( $items ) {
	if ( ! is_singular( 'jodyn_programme' ) ) {
		return $items;
	}
	$posts_page = (int) get_option( 'page_for_posts' );
	$programmes = get_page_by_path( 'training/programmes' );
	$parent_ids = array();
	foreach ( $items as $item ) {
		if ( 'page' === $item->object && (int) $item->object_id === $posts_page ) {
			$item->classes = array_diff( (array) $item->classes, array( 'current_page_parent' ) );
		}
		if ( $programmes && 'page' === $item->object && (int) $item->object_id === $programmes->ID ) {
			$item->classes[] = 'current_page_parent';
			$parent_ids[]    = (int) $item->menu_item_parent;
		}
	}
	foreach ( $items as $item ) {
		if ( in_array( (int) $item->ID, $parent_ids, true ) ) {
			$item->classes[] = 'current-menu-ancestor';
		}
	}
	return $items;
}
add_filter( 'wp_nav_menu_objects', 'jodyn_programme_menu_current' );
