/**
 * Training programmes page motion.
 *
 * Hero: the three photos rise from the bottom edge on load, and the outer two open wider as
 * you scroll away. Their resting fan is set in training.css, so nothing here is needed to see it.
 *
 * Explore our programmes: the area tiles, format chips and search filter the list. This runs
 * with or without motion. ?area=<key> in the address opens the list already filtered. A format
 * matches only programmes with an upcoming run in that format; ?format=<key> preselects one.
 *
 * Training options: the four tabs show one panel at a time (click, arrow keys, Home/End).
 *
 * Learning journey (motion only): a lime line fills down the three stages as you scroll.
 */
(function () {
  'use strict';

  var pg = document.querySelector('[data-jd-tr-pg]');
  if (pg) {
    var items = [].slice.call(pg.querySelectorAll('[data-item]'));
    var label = pg.querySelector('[data-count-label]');
    var state = { area: 'all', format: 'all', q: '' };
    var deep = /[?&]area=([a-z]+)/.exec(location.search);
    if (deep && pg.querySelector('[data-f-area="' + deep[1] + '"]')) state.area = deep[1];
    var deepFormat = /[?&]format=([a-z-]+)/.exec(location.search);
    if (deepFormat && pg.querySelector('[data-f-format="' + deepFormat[1] + '"]')) state.format = deepFormat[1];

    var apply = function () {
      var n = 0;
      items.forEach(function (it) {
        var ok = (state.area === 'all' || it.dataset.area === state.area) &&
          (state.format === 'all' || it.dataset.format === state.format) &&
          (!state.q || it.dataset.title.indexOf(state.q) > -1);
        it.hidden = !ok;
        if (ok) n++;
      });
      pg.querySelectorAll('[data-f-area]').forEach(function (b) { b.setAttribute('aria-pressed', String(b.dataset.fArea === state.area)); });
      pg.querySelectorAll('[data-f-format]').forEach(function (b) { b.setAttribute('aria-pressed', String(b.dataset.fFormat === state.format)); });
      pg.querySelectorAll('[data-area-info]').forEach(function (b) { b.hidden = b.dataset.areaInfo !== state.area; });
      pg.querySelector('[data-count]').textContent = n;
      if (label) label.textContent = n === 1 ? label.dataset.one : label.dataset.many;
      pg.querySelector('[data-empty]').hidden = n > 0;
      // Everything below has moved, so scroll-driven sections need fresh positions
      if (window.ScrollTrigger) window.ScrollTrigger.refresh();
    };

    pg.addEventListener('click', function (e) {
      var b = e.target.closest('[data-f-area], [data-f-format]');
      if (!b) return;
      if (b.dataset.fArea) state.area = b.dataset.fArea;
      else state.format = b.dataset.fFormat;
      apply();
    });
    var q = pg.querySelector('[data-f-q]');
    if (q) q.addEventListener('input', function () { state.q = q.value.trim().toLowerCase(); apply(); });
    apply();

    // Links elsewhere on this page that pick a format ("Explore Online Programmes") filter the
    // list and scroll to it instead of reloading the page.
    document.querySelectorAll('[data-jd-tr-format]').forEach(function (a) {
      a.addEventListener('click', function (e) {
        e.preventDefault();
        state.area = 'all';
        state.format = a.dataset.jdTrFormat;
        apply();
        var lenis = window.JodynMotion && window.JodynMotion.lenis;
        if (lenis) {
          // The shorter list moved the page under Lenis, so resync it before the smooth scroll
          lenis.resize();
          lenis.scrollTo(window.scrollY, { immediate: true, force: true });
          lenis.scrollTo(pg, { offset: -72, force: true });
        } else {
          pg.scrollIntoView({ behavior: 'smooth' });
        }
      });
    });
  }

  // Training options: tabs down the left. Click or arrow keys; only the chosen panel shows.
  var motionOn = false;
  var tabsRoot = document.querySelector('[data-jd-tr-tabs]');
  if (tabsRoot) {
    var tabs = [].slice.call(tabsRoot.querySelectorAll('[role="tab"]'));
    var show = function (i, focus) {
      tabs.forEach(function (t, j) {
        t.setAttribute('aria-selected', String(j === i));
        t.tabIndex = j === i ? 0 : -1;
        document.getElementById(t.getAttribute('aria-controls')).hidden = j !== i;
      });
      if (focus) tabs[i].focus();
      if (window.ScrollTrigger) window.ScrollTrigger.refresh();
      if (!motionOn) return;
      var panel = document.getElementById(tabs[i].getAttribute('aria-controls'));
      gsap.fromTo(panel.querySelector('img'), { scale: 1.08 }, { scale: 1, duration: 1, ease: 'expo.out' });
      gsap.fromTo(panel.querySelector('.jd-tr-op__body').children, { y: 16, opacity: 0 }, { y: 0, opacity: 1, duration: 0.6, ease: 'expo.out', stagger: 0.05 });
    };
    tabs.forEach(function (t, i) {
      t.addEventListener('click', function () { show(i); });
      t.addEventListener('keydown', function (e) {
        var d = { ArrowDown: 1, ArrowRight: 1, ArrowUp: -1, ArrowLeft: -1 }[e.key];
        if (e.key === 'Home') d = -i;
        if (e.key === 'End') d = tabs.length - 1 - i;
        if (d === undefined) return;
        e.preventDefault();
        show((i + d + tabs.length) % tabs.length, true);
      });
    });
  }

  if (!window.JodynMotion) return;

  JodynMotion.ready(function () {
    motionOn = true;

    // Learning journey: the lime line fills down the stages and each number lights as it's reached
    var journey = document.querySelector('[data-jd-tr-lj]');
    if (journey) {
      journey.classList.add('is-live');
      gsap.fromTo('[data-jd-tr-lj-fill]', { scaleY: 0 }, { scaleY: 1, ease: 'none', scrollTrigger: { trigger: journey, start: 'top 55%', end: 'bottom 55%', scrub: true } });
      journey.querySelectorAll('[data-jd-tr-lj-stage]').forEach(function (stage) {
        ScrollTrigger.create({ trigger: stage, start: 'top 55%', onEnter: function () { stage.classList.add('is-on'); }, onLeaveBack: function () { stage.classList.remove('is-on'); } });
      });
    }

    var cards = document.querySelectorAll('[data-jd-tr-fan] .jd-tr-hero__card');
    if (cards.length === 3) {
      gsap.from(cards, { yPercent: 70, duration: 1.3, ease: 'expo.out', stagger: 0.12, delay: 0.4 });
      // GSAP folds the cards' CSS rotate/translate (the resting fan) into its own transform, so
      // these tweens run from the resting -6deg/6deg out to a wider fan.
      var open = { trigger: '[data-jd-tr-fan]', start: 'top 80%', end: 'bottom top', scrub: true };
      gsap.to(cards[0], { rotation: -11, xPercent: -8, ease: 'none', scrollTrigger: open });
      gsap.to(cards[2], { rotation: 11, xPercent: 8, ease: 'none', scrollTrigger: open });
    }
  });
})();
