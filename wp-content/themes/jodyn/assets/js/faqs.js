/**
 * FAQs page.
 *
 * - Accordion: one answer open at a time (the first starts open).
 * - Search: hides questions that don't match as you type, and topics left empty; says so when
 *   nothing matches. A link such as /faqs/#training lands on that topic.
 */
(function () {
  'use strict';

  var btns = [].slice.call(document.querySelectorAll('[data-jd-fq-btn]'));
  if (!btns.length) return;

  var set = function (b, open) {
    b.setAttribute('aria-expanded', open ? 'true' : 'false');
    b.closest('[data-jd-fq-item]').classList.toggle('is-open', open);
  };
  var refresh = function () {
    var lenis = window.JodynMotion && window.JodynMotion.lenis;
    if (lenis) setTimeout(function () { lenis.resize(); if (window.ScrollTrigger) window.ScrollTrigger.refresh(); }, 480);
  };
  btns.forEach(function (b) {
    b.addEventListener('click', function () {
      var open = b.getAttribute('aria-expanded') !== 'true';
      btns.forEach(function (x) { if (x !== b) set(x, false); });
      set(b, open);
      refresh();
    });
  });
  set(btns[0], true);

  var input = document.querySelector('[data-jd-fq-search]');
  var form = document.querySelector('[data-jd-fq-form]');
  var empty = document.querySelector('[data-jd-fq-empty]');
  var status = document.querySelector('[data-jd-fq-status]');
  var items = [].slice.call(document.querySelectorAll('[data-jd-fq-item]'));
  var groups = [].slice.call(document.querySelectorAll('[data-jd-fq-group]'));
  var timer;
  if (form) form.addEventListener('submit', function (e) { e.preventDefault(); });
  if (input) {
    input.addEventListener('input', function () {
      var words = input.value.trim().toLowerCase().split(/\s+/).filter(Boolean), shown = 0;
      items.forEach(function (it) {
        var text = it.textContent.toLowerCase();
        var hit = words.every(function (w) { return text.indexOf(w) > -1; });
        it.hidden = !hit;
        if (hit) shown++;
      });
      groups.forEach(function (g) { g.hidden = !g.querySelector('[data-jd-fq-item]:not([hidden])'); });
      empty.hidden = shown > 0;
      clearTimeout(timer);
      timer = setTimeout(function () {
        status.textContent = words.length ? (shown === 1 ? '1 question found' : shown + ' questions found') : '';
      }, 400);
      refresh();
    });
  }
})();
