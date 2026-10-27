/**
 * Legal pages (Privacy Policy, Terms of Use, Cookie Policy).
 *
 * - The contents list highlights the section being read.
 * - Contents links glide to their section (Lenis has no anchor handling of its own) and keep the
 *   address in step, so a shared link such as /terms/#cancellations opens at the same place.
 */
(function () {
  'use strict';

  var links = [].slice.call(document.querySelectorAll('[data-jd-lg-toc] a'));
  var secs = links.map(function (a) { return document.getElementById(a.hash.slice(1)); });
  if (!links.length) return;

  var spy = function () {
    var line = window.innerHeight * 0.3, cur = 0;
    secs.forEach(function (s, i) { if (s && s.getBoundingClientRect().top < line) cur = i; });
    links.forEach(function (a, i) {
      if (i === cur) a.setAttribute('aria-current', 'true'); else a.removeAttribute('aria-current');
    });
  };
  window.addEventListener('scroll', spy, { passive: true });
  spy();

  document.addEventListener('click', function (e) {
    var a = e.target.closest('a[href^="#"]');
    if (!a || a.hash.length < 2) return;
    var target = document.getElementById(a.hash.slice(1));
    if (!target) return;
    e.preventDefault();
    var d = a.closest('details');
    if (d) d.open = false;
    if (window.history.replaceState) window.history.replaceState(null, '', a.hash);
    var y = target.getBoundingClientRect().top + window.scrollY - 112;
    var lenis = window.JodynMotion && window.JodynMotion.lenis;
    if (lenis) lenis.scrollTo(y, { force: true }); else window.scrollTo({ top: y, behavior: 'smooth' });
    target.setAttribute('tabindex', '-1');
    target.focus({ preventScroll: true });
  });
})();
