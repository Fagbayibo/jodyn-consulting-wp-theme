/**
 * Home · How we help: service-line tabs (WAI-ARIA tabs pattern).
 *
 * Click or arrow keys switch panels; Home and End jump to the ends. The tabs work without
 * the motion stack; when motion is on, the new panel's photo wipes in and its text rises.
 * On phones the selected pill scrolls into view.
 */
(function () {
  'use strict';

  var section = document.querySelector('[data-jd-help]');
  if (!section) return;

  var tabs = Array.prototype.slice.call(section.querySelectorAll('[role="tab"]'));
  var panels = tabs.map(function (t) { return document.getElementById(t.getAttribute('aria-controls')); });

  function animate(panel) {
    if (!window.JodynMotion || !JodynMotion.enabled || !window.gsap) return;
    var media = panel.querySelector('.jd-help__media');
    gsap.fromTo(media, { clipPath: 'inset(0% 0% 0% 100%)' }, { clipPath: 'inset(0% 0% 0% 0%)', duration: 0.9, ease: 'expo.out', clearProps: 'clipPath' });
    gsap.fromTo(media.querySelector('img'), { scale: 1.15 }, { scale: 1, duration: 1.2, ease: 'expo.out' });
    gsap.fromTo(panel.querySelectorAll('.jd-help__body > *, .jd-help__items li'), { y: 18, opacity: 0 }, { y: 0, opacity: 1, duration: 0.7, ease: 'expo.out', stagger: 0.04 });
  }

  function select(index, focus) {
    if (tabs[index].getAttribute('aria-selected') === 'true') return;
    tabs.forEach(function (tab, i) {
      var on = i === index;
      tab.setAttribute('aria-selected', on ? 'true' : 'false');
      tab.tabIndex = on ? 0 : -1;
      panels[i].hidden = !on;
    });
    if (focus) tabs[index].focus();
    // Keep the chosen pill in view on phones without moving the page vertically
    var list = tabs[index].parentElement;
    if (list.scrollWidth > list.clientWidth) {
      list.scrollTo({ left: tabs[index].offsetLeft - list.clientWidth / 2 + tabs[index].offsetWidth / 2, behavior: 'smooth' });
    }
    animate(panels[index]);
    if (window.ScrollTrigger) ScrollTrigger.refresh();
  }

  var KEYS = { ArrowDown: 1, ArrowRight: 1, ArrowUp: -1, ArrowLeft: -1 };
  tabs.forEach(function (tab, i) {
    tab.addEventListener('click', function () { select(i); });
    tab.addEventListener('keydown', function (e) {
      var target = null;
      if (KEYS[e.key]) target = (i + KEYS[e.key] + tabs.length) % tabs.length;
      else if (e.key === 'Home') target = 0;
      else if (e.key === 'End') target = tabs.length - 1;
      if (target === null) return;
      e.preventDefault();
      select(target, true);
    });
  });
})();
