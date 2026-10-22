/**
 * JoDyn header: scroll states, desktop dropdowns, mobile menu.
 * No dependencies. Loaded with `defer`.
 */
(function () {
  'use strict';

  var header = document.querySelector('[data-jd-header]');
  if (!header) return;

  var labels = window.jodynHeader || { openLabel: 'Open menu', closeLabel: 'Close menu' };
  var isSolid = header.classList.contains('is-solid');
  var desktop = window.matchMedia('(min-width: 1080px)');
  var CONDENSE_AT = 80;   // px scrolled before the bar becomes the pill
  var HIDE_DELTA = 8;     // px of travel before hide/show reacts

  /* ------------------------------------------------------------------
   * Scroll: condense into the pill, hide going down, return going up
   * ------------------------------------------------------------------ */
  var lastY = window.scrollY;
  var ticking = false;

  function isBusy() {
    return header.classList.contains('is-menu-open') || openItem !== null || header.querySelector(':focus-visible') !== null;
  }

  function update() {
    var y = Math.max(0, window.scrollY);
    if (!isSolid) header.classList.toggle('is-condensed', y > CONDENSE_AT);

    var delta = y - lastY;
    if (y <= CONDENSE_AT) {
      header.classList.remove('is-hidden');
      lastY = y;
    } else if (Math.abs(delta) >= HIDE_DELTA) {
      var goingDown = delta > 0;
      if (goingDown && y > window.innerHeight * 0.5 && !isBusy()) {
        header.classList.add('is-hidden');
      } else if (!goingDown) {
        header.classList.remove('is-hidden');
      }
      lastY = y;
    }
    ticking = false;
  }

  window.addEventListener('scroll', function () {
    if (!ticking) {
      ticking = true;
      window.requestAnimationFrame(update);
    }
  }, { passive: true });
  update();

  // Keyboard users never lose the header.
  header.addEventListener('focusin', function () { header.classList.remove('is-hidden'); });

  /* ------------------------------------------------------------------
   * Desktop dropdowns: open on hover (mouse) or click/Enter (everyone)
   * ------------------------------------------------------------------ */
  var openItem = null;
  var openedByHover = false;
  var closeTimer = null;

  function setOpen(item, open) {
    var toggle = item.querySelector('.jd-nav__toggle');
    item.classList.toggle('is-open', open);
    if (toggle) toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  }

  function openDropdown(item, byHover) {
    window.clearTimeout(closeTimer);
    if (openItem && openItem !== item) setOpen(openItem, false);
    setOpen(item, true);
    openItem = item;
    openedByHover = byHover;
  }

  function closeDropdown(returnFocus) {
    window.clearTimeout(closeTimer);
    if (!openItem) return;
    var item = openItem;
    setOpen(item, false);
    openItem = null;
    if (returnFocus) {
      var toggle = item.querySelector('.jd-nav__toggle');
      if (toggle) toggle.focus();
    }
  }

  Array.prototype.forEach.call(header.querySelectorAll('.jd-nav__item--has-menu'), function (item) {
    var toggle = item.querySelector('.jd-nav__toggle');
    if (!toggle) return;

    item.addEventListener('pointerenter', function (e) {
      if (e.pointerType === 'mouse') openDropdown(item, true);
    });
    item.addEventListener('pointerleave', function (e) {
      if (e.pointerType !== 'mouse') return;
      closeTimer = window.setTimeout(function () {
        if (openItem === item) closeDropdown(false);
      }, 180);
    });

    toggle.addEventListener('click', function () {
      if (openItem === item) {
        // A click right after hover-open keeps it open instead of flickering shut.
        if (openedByHover) { openedByHover = false; return; }
        closeDropdown(false);
      } else {
        openDropdown(item, false);
      }
    });

    item.addEventListener('focusout', function (e) {
      if (openItem === item && !item.contains(e.relatedTarget)) closeDropdown(false);
    });
  });

  document.addEventListener('pointerdown', function (e) {
    if (openItem && !openItem.contains(e.target)) closeDropdown(false);
  });

  /* ------------------------------------------------------------------
   * Mobile menu
   * ------------------------------------------------------------------ */
  var menuBtn = header.querySelector('.jd-menu-btn');
  var panel = document.getElementById('jd-mobile-menu');

  function menuIsOpen() { return header.classList.contains('is-menu-open'); }

  function openMenu() {
    header.classList.add('is-menu-open');
    header.classList.remove('is-hidden');
    menuBtn.setAttribute('aria-expanded', 'true');
    menuBtn.setAttribute('aria-label', labels.closeLabel);
    panel.inert = false;
    document.body.classList.add('is-locked');
    window.setTimeout(function () {
      var first = panel.querySelector('a, button');
      if (first && menuIsOpen()) first.focus({ preventScroll: true });
    }, 350);
  }

  function closeMenu(returnFocus) {
    if (!menuIsOpen()) return;
    header.classList.remove('is-menu-open');
    menuBtn.setAttribute('aria-expanded', 'false');
    menuBtn.setAttribute('aria-label', labels.openLabel);
    panel.inert = true;
    document.body.classList.remove('is-locked');
    if (returnFocus) menuBtn.focus();
  }

  if (menuBtn && panel) {
    menuBtn.addEventListener('click', function () {
      if (menuIsOpen()) closeMenu(true); else openMenu();
    });

    // Accordion for items with children.
    Array.prototype.forEach.call(panel.querySelectorAll('.jd-mnav__toggle'), function (toggle) {
      toggle.addEventListener('click', function () {
        var item = toggle.closest('.jd-mnav__item');
        var open = toggle.getAttribute('aria-expanded') !== 'true';
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        item.classList.toggle('is-open', open);
      });
    });

    // Close after choosing a link (same-page anchors included).
    panel.addEventListener('click', function (e) {
      if (e.target.closest('a')) closeMenu(false);
    });

    // Keep Tab inside the header while the menu is open.
    header.addEventListener('keydown', function (e) {
      if (e.key !== 'Tab' || !menuIsOpen()) return;
      var focusables = Array.prototype.filter.call(
        header.querySelectorAll('a[href], button:not([disabled])'),
        function (el) { return el.getClientRects().length > 0 && !el.closest('[inert]'); }
      );
      if (!focusables.length) return;
      var first = focusables[0];
      var last = focusables[focusables.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    });

    // Leaving the mobile layout closes the menu.
    var onBreakpoint = function (e) { if (e.matches) closeMenu(false); };
    if (desktop.addEventListener) desktop.addEventListener('change', onBreakpoint);
    else desktop.addListener(onBreakpoint);
  }

  /* ------------------------------------------------------------------
   * Escape closes whatever is open
   * ------------------------------------------------------------------ */
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    if (menuIsOpen()) closeMenu(true);
    else if (openItem) closeDropdown(true);
  });
})();
