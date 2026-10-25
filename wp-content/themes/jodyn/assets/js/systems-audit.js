/**
 * Systems Audit page.
 *
 * - Hero: the sample findings card rises in, then its rows slide in and the risk ratings pop.
 * - Services: the lime index highlights the service being read (the last one whose top has
 *   passed the middle of the screen) and keeps it in view on phones, where the bar scrolls
 *   sideways. Its links, and the hero's See What We Review, glide to their target.
 * - Findings to fixes: the three links of the example slide in one after another.
 */
(function () {
  'use strict';

  var card = document.querySelector('[data-jd-sa-card]');
  if (card && window.JodynMotion) {
    JodynMotion.ready(function () {
      gsap.timeline({ delay: 0.6 })
        .from(card, { y: 40, opacity: 0, duration: 1, ease: 'expo.out' })
        .from(card.querySelectorAll('li'), { x: -16, opacity: 0, duration: 0.6, ease: 'expo.out', stagger: 0.12 }, 0.3)
        .from(card.querySelectorAll('b'), { scale: 0, duration: 0.5, ease: 'back.out(2)', stagger: 0.12 }, 0.5);
    });
  }

  var chain = document.querySelector('[data-jd-sa-chain]');
  if (chain && window.JodynMotion) {
    JodynMotion.ready(function () {
      gsap.from(chain.querySelectorAll('[data-jd-sa-link-step]'), { x: 24, opacity: 0, duration: 0.9, ease: 'expo.out', stagger: 0.25, scrollTrigger: { trigger: chain, start: 'top 75%' } });
    });
  }

  var links = [].slice.call(document.querySelectorAll('[data-jd-sa-link]'));
  var blocks = [].slice.call(document.querySelectorAll('[data-jd-sa-block]'));
  if (links.length) {
    var on = -2;
    var mark = function () {
      var n = -1;
      blocks.forEach(function (b, i) { if (b.getBoundingClientRect().top < window.innerHeight * 0.5) n = i; });
      if (n === on) return;
      on = n;
      links.forEach(function (a, i) {
        a.classList.toggle('is-on', i === n);
        if (i === n) a.setAttribute('aria-current', 'location'); else a.removeAttribute('aria-current');
      });
      if (n >= 0) {
        var bar = links[n].parentNode;
        if (bar.scrollWidth > bar.clientWidth) bar.scrollTo({ left: links[n].offsetLeft - 16, behavior: 'smooth' });
      }
    };
    window.addEventListener('scroll', mark, { passive: true });
    mark();
  }

  // Glide to a service (or to the section) instead of jumping; the offset clears the header and the bar.
  [].slice.call(document.querySelectorAll('[data-jd-sa-link], .jd-sa-hero__jump')).forEach(function (a) {
    a.addEventListener('click', function (e) {
      var to = document.getElementById(a.hash.slice(1));
      var lenis = window.JodynMotion && window.JodynMotion.lenis;
      if (!to || !lenis) return;
      e.preventDefault();
      lenis.scrollTo(to, { offset: a.classList.contains('jd-sa-hero__jump') ? -24 : -170 });
      if (window.history.replaceState) window.history.replaceState(null, '', a.hash);
    });
  });
})();
