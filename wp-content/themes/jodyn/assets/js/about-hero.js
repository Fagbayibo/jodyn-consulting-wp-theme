/**
 * About · Hero: the photo strip wipes open, one frame after another, as soon as the page loads.
 * (It sits at the fold, so per-item scroll triggers would leave the shorter frames empty until
 * the visitor scrolls.)
 */
(function () {
  'use strict';

  var strip = document.querySelector('[data-jd-about-strip]');
  if (!strip || !window.JodynMotion) return;

  JodynMotion.ready(function () {
    var frames = Array.prototype.filter.call(strip.children, function (el) { return el.offsetParent; });
    var tl = gsap.timeline({ delay: 0.45 });
    tl.fromTo(frames, { clipPath: 'inset(100% 0% 0% 0%)' }, { clipPath: 'inset(0% 0% 0% 0%)', duration: 1.2, ease: 'expo.out', stagger: 0.1, clearProps: 'clipPath' });
    tl.from(strip.querySelectorAll('img'), { scale: 1.18, duration: 1.6, ease: 'expo.out', stagger: 0.1 }, 0);
  });
})();
