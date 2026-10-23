<?php
/**
 * Small template helpers.
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline icon markup. Line icons, 1.75 stroke, currentColor.
 *
 * @param string $name  arrow | arrow-external | chevron.
 * @param string $class Optional class on the svg.
 */
function jodyn_icon( $name, $class = '' ) {
	$paths = array(
		'arrow'          => 'M5 12h14M13 6l6 6-6 6',
		'arrow-external' => 'M7 17L17 7M8 7h9v9',
		'chevron'        => 'M6 9l6 6 6-6',
		'calendar'       => 'M5 5h14a1.5 1.5 0 0 1 1.5 1.5v12A1.5 1.5 0 0 1 19 20H5a1.5 1.5 0 0 1-1.5-1.5v-12A1.5 1.5 0 0 1 5 5zM3.5 10h17M8 3v4M16 3v4',
		'pin'            => 'M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21zM12 7a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z',
		'phone'          => 'M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2',
		'mail'           => 'M5 5h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2zM3 7l9 6 9-6',
		'arrow-up'       => 'M12 19V5M6 11l6-6 6 6',
		'arrow-down'     => 'M12 5v14M6 13l6 6 6-6',
		'check'          => 'M5 12.5l4.5 4.5L19 7.5',
		'pen'            => 'M4 20h4L19 9l-4-4L4 16zM13.5 6.5l4 4',
		'doc'            => 'M7 3h7l5 5v13H7zM14 3v5h5M10 13h6M10 17h6',
		'tag'            => 'M20 12.5 12.5 20a1.5 1.5 0 0 1-2.1 0L4 13.6V4h9.6l6.4 6.4a1.5 1.5 0 0 1 0 2.1zM8.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z',
		'download'       => 'M12 4v11M7 10l5 5 5-5M5 19h14',
		'sliders'        => 'M4 7h9M17 7h3M4 17h3M11 17h9M15 5v4M9 15v4',
		'tool'           => 'M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-2.6 2.6-2.4-.6-.6-2.4z',
		'award'          => 'M12 15a6 6 0 1 0 0-12 6 6 0 0 0 0 12zM8.5 14 7 22l5-3 5 3-1.5-8',
		'shield'         => 'M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6zM9.5 12l2 2 3.5-4',
		'target'         => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18zM12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM12 12h.01',
		'users'          => 'M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM2 21v-1a6 6 0 0 1 6-6h2a6 6 0 0 1 6 6v1M16 3.5a4 4 0 0 1 0 7.5M22 21v-1a6 6 0 0 0-4-5.6',
		'building'       => 'M4 21V5l8-3 8 3v16M4 21h16M9 8h.01M15 8h.01M9 12h.01M15 12h.01M10 21v-4h4v4',
		'landmark'       => 'M3 21h18M5 21V10M9.5 21V10M14.5 21V10M19 21V10M2 10h20L12 3z',
		'store'          => 'M4 10v10h16V10M3 10l2-6h14l2 6M3 10a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0M10 20v-5h4v5',
		'heart'          => 'M12 20s-7-4.5-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.5-7 10-7 10z',
		'user'           => 'M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 21v-1a7 7 0 0 1 7-7h2a7 7 0 0 1 7 7v1',
		'trend'          => 'M3 17l6-6 4 4 8-8M15 7h6v6',
		'compass'        => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18zM15.5 8.5l-2 5-5 2 2-5z',
		'search'         => 'M11 18a7 7 0 1 0 0-14 7 7 0 0 0 0 14zM20 20l-4-4',
		'receipt'        => 'M6 3h12v18l-3-2-3 2-3-2-3 2zM9 8h6M9 12h6M9 16h3',
		'cap'            => 'M2 9l10-5 10 5-10 5zM6 11v5c3 2 9 2 12 0v-5M22 9v6',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return sprintf(
		'<svg%s viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="%s"/></svg>',
		$class ? ' class="' . esc_attr( $class ) . '"' : '',
		esc_attr( $paths[ $name ] )
	);
}

/**
 * The two-arrow swap used inside buttons and arrow links.
 */
function jodyn_arrow_swap() {
	return '<span class="jd-swap" aria-hidden="true">' . jodyn_icon( 'arrow', 'jd-swap__a' ) . jodyn_icon( 'arrow', 'jd-swap__b' ) . '</span>';
}

/**
 * Whether the header starts transparent over a full-screen hero.
 * Filter 'jodyn_header_is_overlay' to enable it on other templates.
 */
function jodyn_header_is_overlay() {
	return (bool) apply_filters( 'jodyn_header_is_overlay', is_front_page() );
}

/**
 * Header call to action, editable in Appearance → Customize → Header.
 */
function jodyn_header_cta() {
	return array(
		'label' => get_theme_mod( 'jodyn_header_cta_label', __( 'Contact us', 'jodyn' ) ),
		'url'   => get_theme_mod( 'jodyn_header_cta_url', home_url( '/contact-us/' ) ),
	);
}

/**
 * Build a menu item object for the fallback menu.
 */
function jodyn_fallback_menu_item( $id, $parent, $title, $url, $description = '', $has_children = false ) {
	return (object) array(
		'ID'               => $id,
		'db_id'            => $id,
		'menu_item_parent' => $parent,
		'object_id'        => 0,
		'object'           => 'custom',
		'type'             => 'custom',
		'title'            => $title,
		'url'              => $url,
		'description'      => $description,
		'attr_title'       => '',
		'target'           => '',
		'xfn'              => '',
		'current'          => false,
		'classes'          => $has_children ? array( 'menu-item-has-children' ) : array(),
	);
}

/**
 * Shown until a menu is assigned to the Primary location, so a fresh install
 * still has a complete header. Mirrors the agreed site map.
 *
 * @param array $args wp_nav_menu() arguments.
 */
function jodyn_nav_fallback( $args ) {
	$args  = (object) $args;
	$items = array();
	$id    = 900000;

	$tree = array(
		array( __( 'About us', 'jodyn' ), '/about-us/' ),
		array(
			__( 'Services', 'jodyn' ),
			'/services/',
			array(
				array( __( 'Strategy & advisory', 'jodyn' ), __( 'Direction, priorities and the plan to get there', 'jodyn' ) ),
				array( __( 'People & organisation', 'jodyn' ), __( 'Structures, talent and culture that perform', 'jodyn' ) ),
				array( __( 'Innovation consulting', 'jodyn' ), __( 'New products, models and ways of working', 'jodyn' ) ),
				array( __( 'Project management', 'jodyn' ), __( 'Delivery that lands on time and on budget', 'jodyn' ) ),
				array( __( 'Research & insights', 'jodyn' ), __( 'Evidence for the decisions that matter', 'jodyn' ) ),
				array( __( 'Training & capability', 'jodyn' ), __( 'Programmes from the training calendar', 'jodyn' ) ),
			),
		),
		array( __( 'Training calendar', 'jodyn' ), '/training-calendar/' ),
		array( __( 'Insights', 'jodyn' ), '/insights/' ),
		array( __( 'Careers', 'jodyn' ), '/careers/' ),
	);

	foreach ( $tree as $node ) {
		$parent_id = ++$id;
		$children  = isset( $node[2] ) ? $node[2] : array();
		$items[]   = jodyn_fallback_menu_item( $parent_id, 0, $node[0], home_url( $node[1] ), '', ! empty( $children ) );
		foreach ( $children as $child ) {
			$items[] = jodyn_fallback_menu_item( ++$id, $parent_id, $child[0], home_url( $node[1] . '#' . sanitize_title( $child[0] ) ), $child[1] );
		}
	}

	$walker = ( isset( $args->walker ) && $args->walker instanceof Walker ) ? $args->walker : new Jodyn_Nav_Walker();
	$html   = $walker->walk( $items, isset( $args->depth ) ? (int) $args->depth : 2, $args );
	$out    = sprintf( '<ul class="%s">%s</ul>', esc_attr( $args->menu_class ), $html );

	if ( ! isset( $args->echo ) || $args->echo ) {
		echo $out; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts.
		return null;
	}
	return $out;
}
