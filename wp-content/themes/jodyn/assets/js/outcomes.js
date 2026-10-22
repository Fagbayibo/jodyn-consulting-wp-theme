/**
 * Home · Outcomes: the steps grow up from the baseline one after another (desktop),
 * or slide in from the left as an offset stack (smaller screens).
 */
(function () {
  'use strict';

  var section = document.querySelector('[data-jd-outcomes]');
  if (!section || !window.JodynMotion) return;

  JodynMotion.ready(function () {
    var stairs = section.querySelector('.jd-outcomes__stairs');
    var blocks = stairs.querySelectorAll('.jd-outcomes__block');

    if (!window.matchMedia('(min-width: 1024px)').matches) {
      gsap.from(blocks, { x: -30, opacity: 0, duration: 0.9, ease: 'expo.out', stagger: 0.08, scrollTrigger: { trigger: stairs, start: 'top 80%', once: true } });
      return;
    }
    gsap.timeline({ scrollTrigger: { trigger: stairs, start: 'top 75%', once: true } })
      .from(blocks, { scaleY: 0, duration: 1.1, ease: 'expo.out', stagger: 0.12 })
      .from(stairs.querySelectorAll('.jd-outcomes__block > *'), { y: 16, opacity: 0, duration: 0.6, ease: 'expo.out', stagger: 0.03 }, 0.5);
  });
})();
