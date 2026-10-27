/**
 * Cookie consent (inc/consent.php).
 *
 * - The banner shows until the visitor chooses. The choice is kept in the jodyn_consent cookie
 *   for 12 months: "all" (Accept) or "essential" (Essential only).
 * - Google Analytics loads only with "all", on that click and on every later page view.
 * - Any [data-jd-cookie-settings] link (the footer's Cookie settings, the Cookie Policy's
 *   section 4) opens the banner again. Choosing Essential only after accepting switches Google
 *   Analytics off and deletes its cookies; any page without consent also clears leftovers.
 */
(function () {
  'use strict';

  var banner = document.querySelector('[data-jd-consent]');
  if (!banner) return;
  var gaId = banner.getAttribute('data-ga');
  var NAME = 'jodyn_consent';
  var YEAR = 60 * 60 * 24 * 365;

  var read = function () {
    var m = document.cookie.match(/(?:^|;\s*)jodyn_consent=(all|essential)/);
    return m ? m[1] : null;
  };
  var write = function (value) {
    document.cookie = NAME + '=' + value + '; Max-Age=' + YEAR + '; Path=/; SameSite=Lax' + (location.protocol === 'https:' ? '; Secure' : '');
  };

  var loaded = false;
  var loadGa = function () {
    if (loaded || !gaId) return;
    loaded = true;
    window.dataLayer = window.dataLayer || [];
    window.gtag = function () { window.dataLayer.push(arguments); };
    window.gtag('js', new Date());
    window.gtag('config', gaId);
    var s = document.createElement('script');
    s.async = true;
    s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(gaId);
    document.head.appendChild(s);
  };

  // Withdrawn consent: stop GA writing again (it saves _ga_<id> as the page unloads), then remove
  // _ga and _ga_<id> on this host and its parent domain.
  var clearGa = function () {
    if (gaId) window['ga-disable-' + gaId] = true;
    var host = location.hostname, parts = host.split('.'), domains = ['', host];
    if (parts.length > 1) domains.push('.' + parts.slice(-2).join('.'));
    document.cookie.split(';').forEach(function (c) {
      var name = c.split('=')[0].trim();
      if (!/^_ga(_|$)/.test(name)) return;
      domains.forEach(function (d) {
        document.cookie = name + '=; Max-Age=0; Path=/' + (d ? '; Domain=' + d : '');
      });
    });
  };

  var show = function () {
    banner.hidden = false;
    requestAnimationFrame(function () { requestAnimationFrame(function () { banner.classList.add('is-in'); }); });
  };
  var hide = function () {
    banner.classList.remove('is-in');
    setTimeout(function () { banner.hidden = true; }, 500);
  };

  banner.addEventListener('click', function (e) {
    var b = e.target.closest('[data-jd-consent-choice]');
    if (!b) return;
    var value = b.getAttribute('data-jd-consent-choice');
    var before = read();
    write(value);
    hide();
    if (value === 'all') loadGa();
    else if (before === 'all') { clearGa(); if (loaded) location.reload(); }
  });

  document.addEventListener('click', function (e) {
    var a = e.target.closest('[data-jd-cookie-settings]');
    if (!a) return;
    e.preventDefault();
    show();
    banner.querySelector('[data-jd-consent-choice]').focus({ preventScroll: true });
  });

  var choice = read();
  if (choice === 'all') loadGa();
  else {
    clearGa();
    if (!choice) setTimeout(show, 800);
  }
})();
