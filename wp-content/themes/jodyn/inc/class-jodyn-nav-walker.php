<?php
/**
 * Menu walker for the header.
 *
 * Desktop: a parent item with children becomes a <button aria-expanded> that
 * opens a card of links (each with the menu item's Description as a summary),
 * closed by an "overview" link to the parent's own page.
 * Mobile ('jodyn_variant' => 'mobile'): large links, children in an accordion.
 *
 * Menu item descriptions show only after enabling "Description" under
 * Screen Options in Appearance → Menus.
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Jodyn_Nav_Walker extends Walker_Nav_Menu {

	/** @var array Stack of parent items whose submenu is being printed. */
	private $parents = array();

	/** @var int Position of the current top-level item (for staggered motion). */
	private $top_index = 0;

	/** @var int Position of the current child inside its submenu. */
	private $sub_index = 0;

	private function variant( $args ) {
		return ( is_object( $args ) && isset( $args->jodyn_variant ) && 'mobile' === $args->jodyn_variant ) ? 'mobile' : 'desktop';
	}

	private function is_current( $item ) {
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		return ! empty( $item->current ) || array_intersect( array( 'current-menu-ancestor', 'current-menu-parent', 'current_page_parent' ), $classes );
	}

	private function link_attributes( $item ) {
		$atts = '';
		if ( ! empty( $item->target ) ) {
			$atts .= ' target="' . esc_attr( $item->target ) . '"';
		}
		$rel = empty( $item->xfn ) ? '' : $item->xfn;
		if ( '_blank' === $item->target && false === strpos( $rel, 'noopener' ) ) {
			$rel = trim( $rel . ' noopener' );
		}
		if ( $rel ) {
			$atts .= ' rel="' . esc_attr( $rel ) . '"';
		}
		if ( ! empty( $item->attr_title ) ) {
			$atts .= ' title="' . esc_attr( $item->attr_title ) . '"';
		}
		if ( ! empty( $item->current ) ) {
			$atts .= ' aria-current="page"';
		}
		return $atts;
	}

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$parent          = end( $this->parents );
		$this->sub_index = 0;

		if ( 'mobile' === $this->variant( $args ) ) {
			$output .= '<div class="jd-mnav__sub" id="jd-msub-' . (int) $parent->ID . '"><div class="jd-mnav__sub-inner"><ul class="jd-mnav__sublist">';
			return;
		}
		$output .= '<div class="jd-dropdown" id="jd-dropdown-' . (int) $parent->ID . '"><ul class="jd-dropdown__list">';
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$parent   = end( $this->parents );
		$has_page = $parent && ! empty( $parent->url ) && '#' !== $parent->url;
		/* translators: %s: parent menu item title, e.g. "Services". */
		$overview = $has_page ? sprintf( __( '%s overview', 'jodyn' ), $parent->title ) : '';

		if ( 'mobile' === $this->variant( $args ) ) {
			$output .= '</ul>';
			if ( $has_page ) {
				$output .= '<a class="jd-mnav__overview" href="' . esc_url( $parent->url ) . '">' . esc_html( $overview ) . jodyn_icon( 'arrow' ) . '</a>';
			}
			$output .= '</div></div>';
			return;
		}

		$output .= '</ul>';
		if ( $has_page ) {
			$output .= '<a class="jd-dropdown__all" href="' . esc_url( $parent->url ) . '"><span>' . esc_html( $overview ) . '</span><span class="jd-dropdown__disc">' . jodyn_arrow_swap() . '</span></a>';
		}
		$output .= '</div>';
	}

	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$item         = $data_object;
		$classes      = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_children = in_array( 'menu-item-has-children', $classes, true );
		$title        = apply_filters( 'nav_menu_item_title', $item->title, $item, $args, $depth );
		$variant      = $this->variant( $args );

		if ( $depth > 0 ) {
			$i = $this->sub_index++;
			if ( 'mobile' === $variant ) {
				$output .= '<li><a class="jd-mnav__sublink" href="' . esc_url( $item->url ) . '"' . $this->link_attributes( $item ) . '>' . esc_html( $title ) . '</a>';
				return;
			}
			$output .= '<li style="--i:' . (int) $i . '"><a class="jd-dropdown__link" href="' . esc_url( $item->url ) . '"' . $this->link_attributes( $item ) . '>';
			$output .= '<span class="jd-dropdown__title">' . esc_html( $title ) . '</span>';
			if ( ! empty( $item->description ) ) {
				$output .= '<span class="jd-dropdown__desc">' . esc_html( $item->description ) . '</span>';
			}
			$output .= '</a>';
			return;
		}

		$i       = $this->top_index++;
		$current = $this->is_current( $item ) ? ' is-current' : '';

		if ( 'mobile' === $variant ) {
			$output .= '<li class="jd-mnav__item' . ( $has_children ? ' jd-mnav__item--has-menu' : '' ) . '" style="--i:' . (int) $i . '">';
			if ( $has_children ) {
				$this->parents[] = $item;
				$output         .= '<button type="button" class="jd-mnav__link jd-mnav__toggle' . $current . '" aria-expanded="false" aria-controls="jd-msub-' . (int) $item->ID . '">' . esc_html( $title ) . jodyn_icon( 'chevron', 'jd-mnav__chev' ) . '</button>';
			} else {
				$output .= '<a class="jd-mnav__link' . $current . '" href="' . esc_url( $item->url ) . '"' . $this->link_attributes( $item ) . '>' . esc_html( $title ) . '</a>';
			}
			return;
		}

		$output .= '<li class="jd-nav__item' . ( $has_children ? ' jd-nav__item--has-menu' : '' ) . '" style="--i:' . (int) $i . '">';
		if ( $has_children ) {
			$this->parents[] = $item;
			$output         .= '<button type="button" class="jd-nav__link jd-nav__toggle' . $current . '" aria-expanded="false" aria-controls="jd-dropdown-' . (int) $item->ID . '">' . esc_html( $title ) . jodyn_icon( 'chevron', 'jd-nav__chev' ) . '</button>';
		} else {
			$output .= '<a class="jd-nav__link' . $current . '" href="' . esc_url( $item->url ) . '"' . $this->link_attributes( $item ) . '>' . esc_html( $title ) . '</a>';
		}
	}

	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
		$classes = empty( $data_object->classes ) ? array() : (array) $data_object->classes;
		if ( 0 === $depth && in_array( 'menu-item-has-children', $classes, true ) ) {
			array_pop( $this->parents );
		}
		$output .= '</li>';
	}
}
