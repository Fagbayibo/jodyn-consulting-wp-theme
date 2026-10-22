/**
 * Shared scroll motion: GSAP + ScrollTrigger + SplitText, with Lenis smooth scroll.
 *
 * Markup hooks (the markup is always the finished, at-rest state; motion only adds the entrance):
 *   data-jd-lines          heading splits into masked lines that rise in
 *   data-jd-rise="0.1"     fades up, with an optional delay in seconds
 *   data-jd-reveal="0.1"   frame wipes open upward while its image settles from a zoom
 *   data-jd-parallax="10"  image drifts inside its frame (percent of its own height)
 *   data-jd-stagger        its children pop in one after another (chips, small lists)
 *
 * Section scripts register with JodynMotion.ready(fn). fn runs once the fonts are in,
 * and never under reduced motion, so those visitors see every section at rest.
 */
(function () {
  'use strict';

  var queue = [];
  var api = window.JodynMotion = {
    enabled: !window.matchMedia('(prefers-reduced-motion: reduce)').matches && !!window.gsap,
    started: false,
    lenis: null,
    ready: function (fn) {
      if (!api.enabled) return;
      if (api.started) fn(); else queue.push(fn);
    }
  };
  if (!api.enabled) return;

  gsap.registerPlugin(ScrollTrigger, SplitText);

  // Smooth wheel scrolling, driven by GSAP's ticker so ScrollTrigger stays in step. Touch scrolling stays native.
  if (window.Lenis) {
    var lenis = api.lenis = new Lenis({ lerp: 0.1 });
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add(function (time) { lenis.raf(time * 1000); });
    gsap.ticker.lagSmoothing(0);

    // The mobile menu locks the page with body.is-locked; hold smooth scroll while it does.
    new MutationObserver(function () {
      if (document.body.classList.contains('is-locked')) lenis.stop(); else lenis.start();
    }).observe(document.body, { attributes: true, attributeFilter: ['class'] });
  }

  function lines(el) {
    SplitText.create(el, {
      type: 'lines',
      mask: 'lines',
      linesClass: 'jd-line',
      autoSplit: true, // re-splits when fonts load or the width changes, keeping the animation's progress
      onSplit: function (self) {
        // Room for descenders: tight display line-heights would otherwise clip "p", "y" and "g" in the mask
        (self.masks || []).forEach(function (m) { m.style.paddingBottom = '0.12em'; m.style.marginBottom = '-0.12em'; });
        return gsap.from(self.lines, {
          yPercent: 115, duration: 1.1, ease: 'expo.out', stagger: 0.09,
          scrollTrigger: { trigger: el, start: 'top 85%', once: true }
        });
      }
    });
  }

  function rise(el) {
    gsap.from(el, {
      y: 28, opacity: 0, duration: 1, ease: 'expo.out', delay: parseFloat(el.dataset.jdRise) || 0,
      scrollTrigger: { trigger: el, start: 'top 88%', once: true }
    });
  }

  function reveal(el) {
    var img = el.querySelector('img');
    var tl = gsap.timeline({ delay: parseFloat(el.dataset.jdReveal) || 0, scrollTrigger: { trigger: el, start: 'top 85%', once: true } });
    tl.fromTo(el, { clipPath: 'inset(100% 0% 0% 0%)' }, { clipPath: 'inset(0% 0% 0% 0%)', duration: 1.2, ease: 'expo.out', clearProps: 'clipPath' });
    if (img) tl.from(img, { scale: 1.18, duration: 1.6, ease: 'expo.out' }, 0);
  }

  function parallax(el) {
    var amount = parseFloat(el.dataset.jdParallax) || 12;
    gsap.fromTo(el, { yPercent: -amount / 2 }, {
      yPercent: amount / 2, ease: 'none',
      scrollTrigger: { trigger: el.parentElement, start: 'top bottom', end: 'bottom top', scrub: true }
    });
  }

  function stagger(el) {
    gsap.from(el.children, {
      y: 16, opacity: 0, scale: 0.92, duration: 0.6, ease: 'back.out(2)', stagger: 0.05,
      scrollTrigger: { trigger: el, start: 'top 88%', once: true }
    });
  }

  function start() {
    api.started = true;
    gsap.utils.toArray('[data-jd-lines]').forEach(lines);
    gsap.utils.toArray('[data-jd-rise]').forEach(rise);
    gsap.utils.toArray('[data-jd-reveal]').forEach(reveal);
    gsap.utils.toArray('[data-jd-parallax]').forEach(parallax);
    gsap.utils.toArray('[data-jd-stagger]').forEach(stagger);
    queue.splice(0).forEach(function (fn) { fn(); });
    ScrollTrigger.refresh();
  }

  (document.fonts && document.fonts.ready ? document.fonts.ready : Promise.resolve()).then(start);
})();
