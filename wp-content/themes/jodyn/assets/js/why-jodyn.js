/**
 * Home · Why JoDyn: connector lines from each reason's icon to the logo circle.
 *
 * The lines are drawn from measured positions (wide screens only) and redrawn on resize.
 * With motion on, the circle grows in, the lines draw themselves, their end dots pop,
 * the reasons slide in from each side, and the rings around the circle breathe.
 */
(function () {
  'use strict';

  var section = document.querySelector('[data-jd-why]');
  if (!section) return;

  var hub = section.querySelector('.jd-why__hub');
  var svg = hub.querySelector('.jd-why__lines');
  var core = hub.querySelector('.jd-why__core');
  var icons = Array.prototype.slice.call(hub.querySelectorAll('.jd-why__icon'));
  var wide = window.matchMedia('(min-width: 1024px)');
  var NS = 'http://www.w3.org/2000/svg';
  // How far the lines have drawn (0 to 1). Redraws on resize re-apply it, so they never lose their place.
  var state = { p: window.JodynMotion && JodynMotion.enabled ? 0 : 1 };

  function apply() {
    var paths = svg.querySelectorAll('path'), dots = svg.querySelectorAll('circle');
    for (var i = 0; i < paths.length; i++) {
      var t = Math.max(0, Math.min(1, (state.p - i * 0.06) / 0.7));
      paths[i].setAttribute('stroke-dashoffset', String(1 - t));
      dots[i].setAttribute('r', String(t < 0.9 ? 0 : 4));
    }
  }

  function draw() {
    while (svg.firstChild) svg.removeChild(svg.firstChild);
    if (!wide.matches) return;
    var hr = hub.getBoundingClientRect(), cr = core.getBoundingClientRect();
    var cx = cr.left + cr.width / 2 - hr.left, cy = cr.top + cr.height / 2 - hr.top, reach = cr.width / 2 + 34;
    svg.setAttribute('viewBox', '0 0 ' + hr.width + ' ' + hr.height);
    icons.forEach(function (icon) {
      var r = icon.getBoundingClientRect(), before = r.left + r.width / 2 - hr.left < cx;
      var x = (before ? r.right : r.left) - hr.left + (before ? 10 : -10), y = r.top + r.height / 2 - hr.top;
      var angle = Math.atan2(y - cy, x - cx), ex = cx + Math.cos(angle) * reach, ey = cy + Math.sin(angle) * reach, mx = (x + ex) / 2;
      var path = document.createElementNS(NS, 'path');
      path.setAttribute('d', 'M' + x + ' ' + y + ' C' + mx + ' ' + y + ' ' + mx + ' ' + ey + ' ' + ex + ' ' + ey);
      path.setAttribute('pathLength', '1');
      path.setAttribute('stroke-dasharray', '1');
      var dot = document.createElementNS(NS, 'circle');
      dot.setAttribute('cx', ex); dot.setAttribute('cy', ey); dot.setAttribute('r', '4');
      svg.appendChild(path); svg.appendChild(dot);
    });
    apply();
  }

  draw();
  window.addEventListener('resize', draw);
  window.addEventListener('load', draw);
  if (document.fonts && document.fonts.ready) document.fonts.ready.then(draw);

  if (!window.JodynMotion) return;
  JodynMotion.ready(function () {
    draw();
    var items = gsap.utils.toArray(hub.querySelectorAll('.jd-why__item'));
    var half = items.length / 2;
    var tl = gsap.timeline({ scrollTrigger: { trigger: hub, start: 'top 75%', once: true } });
    tl.from(core, { scale: 0.4, opacity: 0, duration: 1, ease: 'expo.out' })
      .from(hub.querySelectorAll('.jd-why__ring'), { scale: 0.6, opacity: 0, duration: 1, ease: 'expo.out', stagger: 0.1 }, 0.2)
      // Lines draw through the progress value (set as SVG attributes, which GSAP doesn't round)
      .to(state, { p: 1, duration: 1.3, ease: 'power2.inOut', onUpdate: apply }, 0.5)
      .from(items, { opacity: 0, x: function (i) { return i < half ? -30 : 30; }, duration: 0.8, ease: 'expo.out', stagger: 0.08 }, 0.6);
    gsap.to(hub.querySelectorAll('.jd-why__ring'), { scale: 1.06, opacity: 0.05, duration: 2.4, ease: 'sine.inOut', yoyo: true, repeat: -1, stagger: 0.6, delay: 2 });
  });
})();
