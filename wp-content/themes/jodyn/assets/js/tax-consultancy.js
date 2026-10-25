/**
 * Tax Consultancy page.
 *
 * - Hero: the photo settles in from a slight zoom, then drifts down as the page scrolls.
 * - Services: the four photo panels, one open at a time (below).
 * - Bigger picture: the three links of the example slide in one after another.
 */
(function () {
  'use strict';

  var img = document.querySelector('[data-jd-tx-img]');
  if (img && window.JodynMotion) {
    JodynMotion.ready(function () {
      gsap.fromTo(img, { scale: 1.15 }, { scale: 1, duration: 2.2, ease: 'expo.out' });
      gsap.to(img, { yPercent: 10, ease: 'none', scrollTrigger: { trigger: img.closest('section'), start: 'top top', end: 'bottom top', scrub: true } });
    });
  }

  var chain = document.querySelector('[data-jd-tx-chain]');
  if (chain && window.JodynMotion) {
    JodynMotion.ready(function () {
      gsap.from(chain.querySelectorAll('[data-jd-tx-chain-step]'), { x: 24, opacity: 0, duration: 0.9, ease: 'expo.out', stagger: 0.25, scrollTrigger: { trigger: chain, start: 'top 75%' } });
    });
  }
})();

/*
 * Services: one photo panel open at a time. Hover (wide screens with a mouse), click, tap,
 * Enter or Space opens a panel; tabbing to a button inside a closed panel opens it too.
 */
(function () {
  'use strict';

  var panels = [].slice.call(document.querySelectorAll('[data-jd-tx-panel]'));
  if (!panels.length) return;
  var open = function (p) { panels.forEach(function (x) { x.classList.toggle('is-open', x === p); }); };
  var hover = window.matchMedia('(hover: hover) and (min-width: 1024px)');
  panels.forEach(function (p) {
    p.addEventListener('click', function () { open(p); });
    p.addEventListener('focusin', function () { open(p); });
    p.addEventListener('keydown', function (e) {
      if (e.target === p && (e.key === 'Enter' || e.key === ' ')) { e.preventDefault(); open(p); }
    });
    p.addEventListener('mouseenter', function () { if (hover.matches) open(p); });
  });
})();
