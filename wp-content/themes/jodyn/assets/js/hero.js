/**
 * Home hero film.
 *
 * - Starts the film only after the page has loaded and the browser is idle; the poster image is
 *   the first paint.
 * - Phones get the mobile file when one is set (Customize → Home hero).
 * - Data Saver and reduced-motion visitors keep the still poster and download no video.
 * - Pauses while the hero is off screen; the pause button (WCAG 2.2.2) always wins.
 */
(function () {
  'use strict';

  var hero = document.querySelector('[data-jd-hero]');
  if (!hero) return;

  var video = hero.querySelector('.jd-hero__video');
  var button = hero.querySelector('[data-jd-hero-pause]');
  var label = button && button.querySelector('.screen-reader-text');
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var saveData = !!(navigator.connection && navigator.connection.saveData);
  var small = window.matchMedia('(max-width: 767px)').matches;

  var userPaused = false;
  var onScreen = true;
  var ready = false; // becomes true on window load

  function setButton(paused) {
    hero.classList.toggle('is-paused', paused);
    if (!button) return;
    button.setAttribute('aria-pressed', paused ? 'true' : 'false');
    if (label) label.textContent = paused ? label.dataset.labelPlay : label.dataset.labelPause;
  }

  function load() {
    if (!video || video.getAttribute('src')) return;
    var src = (small && video.dataset.srcMobile) || video.dataset.src;
    if (!src) return;
    video.addEventListener('playing', function () { hero.classList.add('is-playing'); }, { once: true });
    video.src = src;
  }

  function sync() {
    var shouldPlay = !userPaused && onScreen;
    if (!video || !ready) return;
    if (shouldPlay) {
      load();
      var p = video.play();
      if (p && p.catch) p.catch(function () { /* autoplay refused: the poster stays */ });
    } else if (video.getAttribute('src')) {
      video.pause();
    }
  }

  if (button) {
    button.addEventListener('click', function () {
      userPaused = !userPaused;
      setButton(userPaused);
      sync();
    });
  }

  if (reduceMotion || saveData) {
    userPaused = true;
    setButton(true);
    return;
  }

  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (entries) {
      onScreen = entries[0].isIntersecting;
      sync();
    }, { threshold: 0.05 }).observe(hero);
  }

  function start() { ready = true; sync(); }
  // After load, once the browser is idle: the film is the heaviest file on the site, so the page,
  // its fonts and images all finish first (the poster covers the wait).
  function whenIdle() {
    if ('requestIdleCallback' in window) requestIdleCallback(start, { timeout: 3000 });
    else setTimeout(start, 1000);
  }
  if (document.readyState === 'complete') whenIdle();
  else window.addEventListener('load', whenIdle, { once: true });
})();
