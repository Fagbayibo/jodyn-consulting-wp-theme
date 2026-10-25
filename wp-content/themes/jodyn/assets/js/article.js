/**
 * Insights article.
 *
 * - On this page: highlights the section being read (the last heading above the top third of
 *   the screen), and glides to a section when one is clicked.
 * - Copy link: copies the article's address and says so.
 */
(function () {
  'use strict';

  var links = [].slice.call(document.querySelectorAll('.jd-art__toc a'));
  if (links.length) {
    var heads = links.map(function (a) { return document.getElementById(a.hash.slice(1)); });
    var mark = function () {
      var on = -1;
      heads.forEach(function (h, i) { if (h && h.getBoundingClientRect().top < window.innerHeight * 0.35) on = i; });
      links.forEach(function (a, i) {
        if (i === on) a.setAttribute('aria-current', 'location'); else a.removeAttribute('aria-current');
      });
    };
    window.addEventListener('scroll', mark, { passive: true });
    mark();

    links.forEach(function (a, i) {
      a.addEventListener('click', function (e) {
        var lenis = window.JodynMotion && window.JodynMotion.lenis;
        if (!lenis || !heads[i]) return;
        e.preventDefault();
        lenis.scrollTo(heads[i], { offset: -110 });
        if (window.history.replaceState) window.history.replaceState(null, '', a.hash);
      });
    });
  }

  var copy = document.querySelector('[data-jd-copy]');
  if (copy) {
    var note = document.querySelector('.jd-art__copied');
    var t = window.jodynArticle || { copied: 'Link copied', failed: 'Copy failed' };
    copy.addEventListener('click', function () {
      var say = function (text) { note.textContent = text; setTimeout(function () { note.textContent = ''; }, 2500); };
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(copy.dataset.jdCopy).then(function () { say(t.copied); }, function () { say(t.failed); });
      } else {
        // Fallback for plain http (local testing): a hidden text box and the copy command.
        var box = document.createElement('textarea');
        box.value = copy.dataset.jdCopy; box.setAttribute('readonly', ''); box.style.position = 'fixed'; box.style.opacity = '0';
        document.body.appendChild(box); box.select();
        try { say(document.execCommand('copy') ? t.copied : t.failed); } catch (err) { say(t.failed); }
        box.remove();
      }
    });
  }
})();
