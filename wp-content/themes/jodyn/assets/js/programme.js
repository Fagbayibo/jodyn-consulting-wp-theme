/**
 * Programme page (template-parts/programme/page.php).
 *
 * The modules and the FAQs are accordions: one answer open at a time in each list. The first
 * module starts open. Lenis is told the page height changed once an answer has opened or closed.
 */
(function () {
  'use strict';

  var lists = document.querySelectorAll('[data-jd-pg-acc]');
  if (!lists.length) return;

  var set = function (b, open) {
    b.setAttribute('aria-expanded', open ? 'true' : 'false');
    b.closest('.jd-pg-acc__item').classList.toggle('is-open', open);
  };
  var refresh = function () {
    var lenis = window.JodynMotion && window.JodynMotion.lenis;
    setTimeout(function () {
      if (lenis) lenis.resize();
      if (window.ScrollTrigger) window.ScrollTrigger.refresh();
    }, 480);
  };

  lists.forEach(function (list) {
    var btns = [].slice.call(list.querySelectorAll('[data-jd-pg-btn]'));
    btns.forEach(function (b) {
      b.addEventListener('click', function () {
        var open = b.getAttribute('aria-expanded') !== 'true';
        btns.forEach(function (x) { if (x !== b) set(x, false); });
        set(b, open);
        refresh();
      });
    });
  });

  var first = document.querySelector('.jd-pg-main [data-jd-pg-btn]');
  if (first) set(first, true);
})();
