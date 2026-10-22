/**
 * Home · How we work: expanding photo panels.
 *
 * - Each panel's button opens it (and closes the others).
 * - With motion on, the panels advance by themselves while on screen, one full loop and then
 *   they stop on the first step (WCAG 2.2.2). The lime bar shows the time to the next step.
 *   Hover or keyboard focus pauses it; a click or tap stops it for good.
 * - Mouse users open a panel just by hovering it.
 */
(function () {
  'use strict';

  var list = document.querySelector('[data-jd-work] .jd-work__panels');
  if (!list) return;

  var panels = Array.prototype.slice.call(list.children);
  var STEP = 5; // seconds per step
  var auto = !!(window.JodynMotion && JodynMotion.enabled && window.gsap);
  var stopped = !auto, paused = false, onScreen = false, shown = 1, tween = null;
  var hover = window.matchMedia('(hover: hover) and (pointer: fine)');

  function current() {
    for (var i = 0; i < panels.length; i++) if (panels[i].classList.contains('is-active')) return i;
    return 0;
  }

  function open(index) {
    panels.forEach(function (panel, i) {
      var on = i === index;
      panel.classList.toggle('is-active', on);
      panel.querySelector('.jd-work__hit').setAttribute('aria-expanded', on ? 'true' : 'false');
    });
  }

  function stop() {
    stopped = true;
    if (tween) tween.kill();
    list.classList.add('is-idle');
  }

  function run() {
    if (tween) tween.kill();
    if (stopped || paused || !onScreen) return;
    var bar = panels[current()].querySelector('.jd-work__progress i');
    tween = gsap.fromTo(bar, { scaleX: 0 }, {
      scaleX: 1, duration: STEP, ease: 'none',
      onComplete: function () {
        var next = (current() + 1) % panels.length;
        open(next);
        shown++;
        if (shown > panels.length) { stop(); return; } // one full loop, back on step 1
        run();
      }
    });
  }

  panels.forEach(function (panel, i) {
    var hit = panel.querySelector('.jd-work__hit');
    hit.addEventListener('click', function () { stop(); open(i); });
    panel.addEventListener('pointerenter', function () {
      if (hover.matches && !panel.classList.contains('is-active')) { stop(); open(i); }
    });
  });

  if (!auto) { list.classList.add('is-idle'); return; }

  list.addEventListener('pointerenter', function () { paused = true; run(); });
  list.addEventListener('pointerleave', function () { paused = false; run(); });
  list.addEventListener('focusin', function () { paused = true; run(); });
  list.addEventListener('focusout', function () { paused = false; run(); });

  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (entries) { onScreen = entries[0].isIntersecting; run(); }, { threshold: 0.4 }).observe(list);
  }

  // Entrance: the panels rise in one after another
  JodynMotion.ready(function () {
    gsap.from(panels, { y: 60, opacity: 0, duration: 1.1, ease: 'expo.out', stagger: 0.1, scrollTrigger: { trigger: list, start: 'top 85%', once: true } });
  });
})();
