<?php

/**
 * Site header: floating navbar.
 *
 * Over a full-screen hero (front page) it starts transparent with the white
 * logo, then condenses into a frosted white pill after 80px of scroll.
 * Other templates start in the pill state.
 *
 * @package JoDyn
 */

$jodyn_overlay = jodyn_header_is_overlay();
$jodyn_cta     = jodyn_header_cta();
$jodyn_img     = get_template_directory_uri() . '/assets/img';
$jodyn_menu    = array(
  'theme_location' => 'primary',
  'container'      => false,
  'depth'          => 2,
  'fallback_cb'    => 'jodyn_nav_fallback',
  'walker'         => new Jodyn_Nav_Walker(),
  'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
);
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <?php wp_head(); ?>
</head>

<body id="top" <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  <a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to content', 'jodyn'); ?></a>

  <header class="jd-header<?php echo $jodyn_overlay ? ' is-overlay' : ' is-condensed is-solid'; ?>" data-jd-header>
    <div class="jd-header__bar">
      <a class="jd-logo" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
        <span class="screen-reader-text"><?php bloginfo('name'); ?></span>
        <img class="jd-logo__white" src="<?php echo esc_url($jodyn_img . '/logo-white.svg'); ?>" alt="" width="687" height="483">
        <picture class="jd-logo__color">
          <source type="image/webp" srcset="<?php echo esc_url($jodyn_img . '/logo-color.webp'); ?>">
          <img src="<?php echo esc_url($jodyn_img . '/logo-color.png'); ?>" alt="" width="480" height="333">
        </picture>
      </a>

      <nav class="jd-nav" aria-label="<?php esc_attr_e('Primary', 'jodyn'); ?>">
        <?php wp_nav_menu(array_merge($jodyn_menu, array('menu_class' => 'jd-nav__list'))); ?>
      </nav>

      <div class="jd-header__actions">
        <a class="jd-btn jd-btn--accent jd-btn--arrow jd-header__cta" href="<?php echo esc_url($jodyn_cta['url']); ?>">
          <?php echo esc_html($jodyn_cta['label']); ?>
          <span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                      ?></span>
        </a>
        <button class="jd-menu-btn" type="button" aria-expanded="false" aria-controls="jd-mobile-menu" aria-label="<?php esc_attr_e('Open menu', 'jodyn'); ?>">
          <span class="jd-menu-btn__lines" aria-hidden="true"><span></span><span></span></span>
        </button>
      </div>
    </div>

    <div class="jd-mobile" id="jd-mobile-menu" inert>
      <nav class="jd-mobile__nav" aria-label="<?php esc_attr_e('Primary', 'jodyn'); ?>">
        <?php
        wp_nav_menu(
          array_merge(
            $jodyn_menu,
            array(
              'menu_class'    => 'jd-mnav__list',
              'walker'        => new Jodyn_Nav_Walker(),
              'jodyn_variant' => 'mobile',
            )
          )
        );
        ?>
      </nav>
      <div class="jd-mobile__foot">
        <a class="jd-btn jd-btn--accent jd-btn--lg jd-btn--arrow" href="<?php echo esc_url($jodyn_cta['url']); ?>">
          <?php echo esc_html($jodyn_cta['label']); ?>
          <span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                      ?></span>
        </a>
        <p class="jd-mobile__meta"><?php bloginfo('name'); ?></p>
      </div>
    </div>
  </header>