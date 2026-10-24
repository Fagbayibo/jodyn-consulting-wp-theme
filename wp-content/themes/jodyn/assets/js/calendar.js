/**
 * Training calendar page.
 *
 * Filters and programme list (with or without motion): the month tabs, search, category and
 * format filter the rows; 8 show per page with page numbers, and any filter change goes back
 * to page 1. Month tabs keep their counts in step with the other filters. ?month=YYYY-MM,
 * ?area=<key> and ?format=<key> in the address open the list already filtered.
 *
 * Hero (motion only): the photo settles in on load and drifts as you scroll; the ribbon of
 * upcoming dates slides up into place. The ribbon's scroll itself is CSS (calendar.css).
 *
 * Download (motion only): the PDF preview's sheets fan out. The form itself is
 * assets/js/calendar-download.js, shared with the pop-up on the Home and Training pages.
 */
(function () {
  'use strict';

  var list = document.querySelector('[data-jd-cal-list]');
  if (list) {
    var items = [].slice.call(list.querySelectorAll('[data-item]'));
    var per = +list.dataset.per || 8;
    var pager = list.querySelector('[data-pager]');
    var label = list.querySelector('[data-count-label]');
    var st = { q: '', area: 'all', format: 'all', month: 'all', page: 1 };

    // Deep links, only for values the page actually offers
    ['month', 'area', 'format'].forEach(function (k) {
      var m = new RegExp('[?&]' + k + '=([a-z0-9-]+)').exec(location.search);
      if (m && list.querySelector('[data-f="' + k + '"][data-v="' + m[1] + '"], [data-f="' + k + '"] option[value="' + m[1] + '"]')) st[k] = m[1];
    });

    var match = function (it, skip) {
      return (st.area === 'all' || it.dataset.area === st.area) &&
        (st.format === 'all' || it.dataset.format === st.format) &&
        (skip === 'month' || st.month === 'all' || it.dataset.month === st.month) &&
        (!st.q || it.dataset.title.indexOf(st.q) > -1);
    };

    // Pager labels come from translatable data attributes, so escape them before building HTML
    var esc = function (t) { var d = document.createElement('i'); d.textContent = t; return d.innerHTML.replace(/"/g, '&quot;'); };
    var txt = { prev: esc(pager.dataset.prev), next: esc(pager.dataset.next), page: esc(pager.dataset.pageLabel) };

    var button = function (page, text, cls, attrs) {
      return '<button type="button" class="jd-cal-list__pg ' + (cls || '') + '" data-page="' + page + '"' + (attrs || '') + '>' + text + '</button>';
    };

    var renderPager = function (pages) {
      if (pages < 2) { pager.innerHTML = ''; pager.hidden = true; return; }
      var h = button(st.page - 1, '<span aria-hidden="true">&larr;</span> ' + txt.prev, 'jd-cal-list__pg--step', st.page === 1 ? ' disabled' : '');
      h += '<ol class="jd-cal-list__pages">';
      for (var i = 1; i <= pages; i++) {
        h += '<li>' + button(i, i, '', ' aria-label="' + txt.page.replace('%d', i) + '"' + (i === st.page ? ' aria-current="page"' : '')) + '</li>';
      }
      h += '</ol>' + button(st.page + 1, txt.next + ' <span aria-hidden="true">&rarr;</span>', 'jd-cal-list__pg--step', st.page === pages ? ' disabled' : '');
      pager.innerHTML = h;
      pager.hidden = false;
    };

    var apply = function (keepPage) {
      var found = items.filter(function (it) { return match(it); });
      var pages = Math.max(1, Math.ceil(found.length / per));
      if (!keepPage) st.page = 1;
      st.page = Math.min(st.page, pages);
      items.forEach(function (it) { it.hidden = true; });
      found.slice((st.page - 1) * per, st.page * per).forEach(function (it) { it.hidden = false; });

      list.querySelector('[data-count]').textContent = found.length;
      if (label) label.textContent = found.length === 1 ? label.dataset.one : label.dataset.many;
      list.querySelector('[data-empty]').hidden = found.length > 0;
      list.querySelectorAll('[data-month-count]').forEach(function (c) {
        var k = c.dataset.monthCount;
        var n = items.filter(function (it) { return match(it, 'month') && (k === 'all' || it.dataset.month === k); }).length;
        c.textContent = n;
        c.closest('button').classList.toggle('is-zero', n === 0);
      });
      list.querySelector('[data-f-clear]').disabled = !st.q && st.area === 'all' && st.format === 'all' && st.month === 'all';
      list.querySelectorAll('[data-f]').forEach(function (c) {
        var k = c.dataset.f;
        if (c.tagName === 'BUTTON') c.setAttribute('aria-pressed', String(st[k] === c.dataset.v));
        else if (k !== 'q') c.value = st[k];
      });
      renderPager(pages);
      // Everything below has moved, so scroll-driven sections need fresh positions
      if (window.ScrollTrigger) window.ScrollTrigger.refresh();
    };

    // After a page change, bring the top of the results back into view
    var toResults = function () {
      var y = list.querySelector('[data-results]').getBoundingClientRect().top + window.scrollY - 120;
      var lenis = window.JodynMotion && window.JodynMotion.lenis;
      if (lenis) {
        lenis.resize();
        lenis.scrollTo(window.scrollY, { immediate: true, force: true });
        lenis.scrollTo(y, { force: true });
      } else {
        window.scrollTo({ top: y, behavior: 'smooth' });
      }
    };

    list.addEventListener('click', function (e) {
      var p = e.target.closest('[data-page]');
      if (p && !p.disabled) {
        st.page = +p.dataset.page;
        apply(true);
        toResults();
        var current = pager.querySelector('[aria-current]');
        if (current) current.focus({ preventScroll: true });
        return;
      }
      var b = e.target.closest('button[data-f]');
      if (b) { st[b.dataset.f] = b.dataset.v; apply(); return; }
      if (e.target.closest('[data-f-clear]')) {
        st.q = ''; st.area = 'all'; st.format = 'all'; st.month = 'all';
        list.querySelector('[data-f="q"]').value = '';
        apply();
      }
    });
    list.addEventListener('input', function (e) {
      var c = e.target.closest('input[data-f], select[data-f]');
      if (!c) return;
      st[c.dataset.f] = c.dataset.f === 'q' ? c.value.trim().toLowerCase() : c.value;
      apply();
    });
    apply();
  }

  if (!window.JodynMotion) return;

  JodynMotion.ready(function () {
    var hero = document.querySelector('[data-jd-cal-hero]');
    if (hero) {
      var img = hero.querySelector('[data-jd-cal-hero-img]');
      gsap.fromTo(img, { scale: 1.15 }, { scale: 1, duration: 2.2, ease: 'expo.out' });
      gsap.to(img, { yPercent: 10, ease: 'none', scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: true } });
      var ribbon = hero.querySelector('[data-jd-cal-ribbon]');
      if (ribbon) gsap.from(ribbon, { yPercent: 100, duration: 1, ease: 'expo.out', delay: 0.8 });
    }

    // Download: the PDF preview's sheets fan out from a neat stack as the section comes in
    var pdf = document.querySelector('[data-jd-cal-pdf]');
    if (pdf) {
      gsap.from(pdf.children, { y: 60, opacity: 0, duration: 1.2, ease: 'expo.out', stagger: 0.12, scrollTrigger: { trigger: pdf, start: 'top 80%' } });
    }
  });
})();
