/**
 * About page motion.
 *
 * Hero: the photo strip wipes open, one frame after another, as soon as the page loads.
 * (It sits at the fold, so per-item scroll triggers would leave the shorter frames empty
 * until the visitor scrolls.)
 *
 * Our story: each gap card starts "open" (✕) and closes (✓) as it scrolls into view.
 * The markup is the closed state, so without JS or with reduced motion every gap shows ✓.
 *
 * What we do: the lime line joining the four services draws itself as you scroll
 * (left to right on desktop, top to bottom on phones). At rest it is fully drawn.
 *
 * Our approach (desktop only): the five belief cards arrive fanned out like a hand of cards
 * and straighten as you scroll in. At rest they sit in a straight row.
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

    var chain = document.querySelector('[data-jd-wwd-chain]');
    if (chain) {
      var line = chain.querySelector('[data-jd-wwd-line]');
      ScrollTrigger.matchMedia({
        '(min-width: 1024px)': function () {
          gsap.fromTo(line, { scaleX: 0 }, { scaleX: 1, ease: 'none', scrollTrigger: { trigger: chain, start: 'top 80%', end: 'top 35%', scrub: true } });
        },
        '(max-width: 1023px)': function () {
          gsap.fromTo(line, { scaleY: 0 }, { scaleY: 1, ease: 'none', scrollTrigger: { trigger: chain, start: 'top 70%', end: 'bottom 60%', scrub: true } });
        }
      });
    }

    var fan = document.querySelector('[data-jd-approach-fan]');
    if (fan) {
      ScrollTrigger.matchMedia({
        '(min-width: 1024px)': function () {
          var rot = [-14, -7, 0, 7, 14], dx = [180, 90, 0, -90, -180], dy = [70, 18, 0, 18, 70];
          gsap.fromTo(fan.children,
            { rotation: function (i) { return rot[i]; }, x: function (i) { return dx[i]; }, y: function (i) { return dy[i]; } },
            { rotation: 0, x: 0, y: 0, ease: 'none', scrollTrigger: { trigger: fan, start: 'top bottom', end: 'top 25%', scrub: true } });
        }
      });
    }
  });
})();
