/**
 * About page motion.
 *
 * Hero: the photo strip wipes open, one frame after another, as soon as the page loads.
 * (It sits at the fold, so per-item scroll triggers would leave the shorter frames empty
 * until the visitor scrolls.)
 *
 * Our story: each gap card starts "open" (✕) and closes (✓) as it scrolls into view.
 * The markup is the closed state, so without JS or with reduced motion every gap shows ✓.
 */
(function () {
  'use strict';

  if (!window.JodynMotion) return;

  JodynMotion.ready(function () {
    var strip = document.querySelector('[data-jd-about-strip]');
    if (strip) {
      var frames = Array.prototype.filter.call(strip.children, function (el) { return el.offsetParent; });
      gsap.timeline({ delay: 0.45 })
        .fromTo(frames, { clipPath: 'inset(100% 0% 0% 0%)' }, { clipPath: 'inset(0% 0% 0% 0%)', duration: 1.2, ease: 'expo.out', stagger: 0.1, clearProps: 'clipPath' })
        .from(strip.querySelectorAll('img'), { scale: 1.18, duration: 1.6, ease: 'expo.out', stagger: 0.1 }, 0);
    }

    document.querySelectorAll('[data-jd-story-gaps] li').forEach(function (li) {
      li.classList.add('is-open');
      ScrollTrigger.create({
        trigger: li,
        start: 'top 62%',
        onEnter: function () { li.classList.remove('is-open'); },
        onLeaveBack: function () { li.classList.add('is-open'); }
      });
    });
  });
})();
