/**
 * Advisory page motion.
 *
 * Our advisory services (desktop, with motion): the section pins and scrolling down slides the
 * five service panels sideways, with a lime progress line. Links to a panel (the hero's index)
 * scroll to the point where that panel sits at the left edge. At rest (no JS, reduced motion,
 * below 1024px) the row is a native swipe row and the anchors work on their own; the progress
 * line then follows the swipe.
 *
 * How we work: each deliverable document slides out from behind its step as it scrolls in.
 * Advisory and training together: the "+" joining the two photos spins in.
 */
(function () {
  'use strict';

  // Swipe row (phones, tablets, reduced motion): the progress line follows the swipe.
  var viewport = document.querySelector('[data-jd-adv-viewport]');
  var line = document.querySelector('[data-jd-adv-progress]');
  if (viewport && line) {
    var follow = function () {
      if (viewport.parentElement.classList.contains('is-pinned')) return;
      var max = viewport.scrollWidth - viewport.clientWidth;
      line.style.transform = 'scaleX(' + (max > 0 ? 0.2 + 0.8 * viewport.scrollLeft / max : 1) + ')';
    };
    viewport.addEventListener('scroll', follow, { passive: true });
    follow();
  }

  if (!window.JodynMotion) return;

  JodynMotion.ready(function () {
    var pin = document.querySelector('[data-jd-adv-pin]');
    if (!pin) return;
    var track = pin.querySelector('[data-jd-adv-track]');
    var bar = pin.querySelector('[data-jd-adv-progress]');
    var trigger = null;

    function distance() { return Math.max(0, track.scrollWidth - document.documentElement.clientWidth); }

    ScrollTrigger.matchMedia({
      '(min-width: 1024px)': function () {
        pin.classList.add('is-pinned');
        // On short screens (e.g. 1366x768) the block is taller than the window: pin it by its
        // bottom edge so the panels and their buttons stay fully in view while they slide.
        var tl = gsap.timeline({ scrollTrigger: {
          trigger: pin, pin: true, scrub: 0.6, invalidateOnRefresh: true,
          start: function () { return pin.offsetHeight > window.innerHeight ? 'bottom bottom' : 'top top'; },
          end: function () { return '+=' + distance(); }
        } });
        tl.fromTo(track, { x: 0 }, { x: function () { return -distance(); }, ease: 'none' }, 0)
          .fromTo(bar, { scaleX: 0.2 }, { scaleX: 1, ease: 'none' }, 0);
        trigger = tl.scrollTrigger;
        return function () { pin.classList.remove('is-pinned'); trigger = null; };
      }
    });

    // How we work: each deliverable document slides out from behind its step, settling at its tilt.
    document.querySelectorAll('[data-jd-adv-doc]').forEach(function (doc, i) {
      gsap.from(doc, { xPercent: -30, opacity: 0, rotation: i % 2 ? 8 : -8, duration: 1, ease: 'expo.out', scrollTrigger: { trigger: doc, start: 'top 85%', once: true } });
    });

    // Advisory and training together: the "+" between the two photos spins in.
    var plus = document.querySelector('[data-jd-adv-plus]');
    if (plus) {
      gsap.from(plus, { scale: 0, rotation: -90, duration: 0.9, ease: 'back.out(2)', scrollTrigger: { trigger: '[data-jd-adv-photos]', start: 'top 70%', once: true } });
    }

    // While pinned, a panel's vertical position never changes, so map it to the scroll progress instead.
    document.addEventListener('click', function (e) {
      var link = e.target.closest('a[href^="#advisory-"]');
      if (!link || !trigger) return;
      var panel = document.getElementById(link.getAttribute('href').slice(1));
      if (!panel || !track.contains(panel)) return;
      e.preventDefault();
      var pad = parseFloat(getComputedStyle(track).paddingLeft) || 0;
      var progress = Math.min(1, Math.max(0, (panel.offsetLeft - pad) / Math.max(1, distance())));
      var y = trigger.start + progress * (trigger.end - trigger.start);
      if (JodynMotion.lenis) JodynMotion.lenis.scrollTo(y); else window.scrollTo({ top: y, behavior: 'smooth' });
      history.replaceState(null, '', link.getAttribute('href'));
    });
  });
})();
