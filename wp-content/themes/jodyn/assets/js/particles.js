/**
 * Brand particles: forest and lime squares and dots drifting up behind a section.
 *
 *   <section data-jd-particles="light" data-jd-keep-out=".title, .card">
 *     <div class="jd-particles" aria-hidden="true"><canvas></canvas></div>
 *
 * Palettes: "light" for white sections, "forest" for forest/black ones.
 * - The canvas is viewport-sized and sticky, so the cost doesn't grow with the section's height.
 *   Particles live in section coordinates and are drawn shifted by how far the canvas has stuck.
 * - Particles fade out near anything matched by data-jd-keep-out, so they never sit on text.
 * - They step aside from the cursor, animate only while the section is on screen,
 *   and stay still under reduced motion.
 */
(function () {
  'use strict';

  var PALETTES = {
    light: [{ c: '35,56,49', a: [0.22, 0.55], w: 5 }, { c: '229,253,74', a: [0.85, 1], w: 3 }, { c: '23,38,31', a: [0.12, 0.25], w: 1 }],
    forest: [{ c: '229,253,74', a: [0.5, 0.95], w: 5 }, { c: '255,255,255', a: [0.12, 0.3], w: 4 }]
  };
  var FADE = 36;        // px over which a particle fades out as it nears content
  var PAD = 8;          // extra px around each keep-out box
  var REPEL = 140;      // cursor radius
  var DENSITY = 20000;  // one particle per this many px² of section
  var MAX = 110;
  var still = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function field(host) {
    var canvas = host.querySelector('.jd-particles canvas');
    if (!canvas || !canvas.getContext) return;
    var ctx = canvas.getContext('2d');
    var palette = PALETTES[host.dataset.jdParticles] || PALETTES.light;
    var keepOut = host.dataset.jdKeepOut || '';
    var weight = palette.reduce(function (sum, p) { return sum + p.w; }, 0);
    var W = 0, H = 0, VW = 0, VH = 0, parts = [], zones = [];
    var running = false, queued = false, mouse = { x: -9999, y: -9999 };

    function pick() {
      var r = Math.random() * weight;
      for (var i = 0; i < palette.length; i++) { if (r < palette[i].w) return palette[i]; r -= palette[i].w; }
      return palette[0];
    }

    function make(y) {
      var p = pick(), depth = 0.5 + Math.random() * 0.5;
      return {
        x: Math.random() * W, y: y === undefined ? Math.random() * H : y,
        size: (6 + Math.random() * 16) * depth, square: Math.random() < 0.6, outline: Math.random() < 0.18,
        rot: Math.random() * 6.28, vr: (Math.random() - 0.5) * 0.01, vy: -(0.08 + Math.random() * 0.22) * depth,
        sway: Math.random() * 6.28, swayAmp: 0.15 + Math.random() * 0.35,
        color: p.c, alpha: p.a[0] + Math.random() * (p.a[1] - p.a[0]), depth: depth, ox: 0, oy: 0
      };
    }

    function measureZones() {
      if (!keepOut) return;
      var hr = host.getBoundingClientRect();
      zones = Array.prototype.map.call(host.querySelectorAll(keepOut), function (el) {
        var r = el.getBoundingClientRect();
        return { l: r.left - hr.left - PAD, t: r.top - hr.top - PAD, r: r.right - hr.left + PAD, b: r.bottom - hr.top + PAD };
      });
    }

    function visibility(x, y) {
      var v = 1;
      for (var i = 0; i < zones.length; i++) {
        var z = zones[i];
        var dx = Math.max(z.l - x, 0, x - z.r), dy = Math.max(z.t - y, 0, y - z.b);
        var d = Math.sqrt(dx * dx + dy * dy);
        if (d < FADE) v = Math.min(v, d / FADE);
      }
      return v;
    }

    function draw() {
      queued = false;
      var offset = canvas.getBoundingClientRect().top - host.getBoundingClientRect().top;
      ctx.clearRect(0, 0, VW, VH);
      for (var i = 0; i < parts.length; i++) {
        var p = parts[i], x = p.x + p.ox, y = p.y + p.oy;
        if (y < offset - 40 || y > offset + VH + 40) continue;
        var vis = visibility(x, y);
        if (vis <= 0.01) continue;
        ctx.save();
        ctx.translate(x, y - offset);
        ctx.rotate(p.rot);
        ctx.fillStyle = 'rgba(' + p.color + ',' + (p.alpha * vis) + ')';
        if (p.outline) {
          ctx.strokeStyle = ctx.fillStyle; ctx.lineWidth = 1.5;
          ctx.strokeRect(-p.size * 0.75, -p.size * 0.75, p.size * 1.5, p.size * 1.5);
        } else if (p.square) {
          ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size);
        } else {
          ctx.beginPath(); ctx.arc(0, 0, p.size / 2, 0, 6.2832); ctx.fill();
        }
        ctx.restore();
      }
    }

    function redraw() {
      if (queued) return;
      queued = true;
      requestAnimationFrame(draw);
    }

    function resize() {
      var dpr = Math.min(window.devicePixelRatio || 1, 2);
      W = host.clientWidth; H = host.clientHeight;
      VW = canvas.clientWidth; VH = canvas.clientHeight;
      canvas.width = Math.round(VW * dpr); canvas.height = Math.round(VH * dpr);
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      var target = Math.min(MAX, Math.round(W * H / DENSITY));
      while (parts.length < target) parts.push(make());
      parts.length = target;
      parts.forEach(function (p) { if (p.x > W) p.x = Math.random() * W; });
      measureZones();
      draw();
    }

    function step() {
      if (!running) return;
      for (var i = 0; i < parts.length; i++) {
        var p = parts[i];
        p.sway += 0.01; p.x += Math.sin(p.sway) * p.swayAmp * 0.3; p.y += p.vy; p.rot += p.vr;
        var dx = p.x - mouse.x, dy = p.y - mouse.y, d2 = dx * dx + dy * dy;
        if (d2 < REPEL * REPEL) {
          var d = Math.sqrt(d2) || 1, f = (1 - d / REPEL) * 22 * p.depth;
          p.ox += (dx / d * f - p.ox) * 0.12; p.oy += (dy / d * f - p.oy) * 0.12;
        } else { p.ox *= 0.94; p.oy *= 0.94; }
        if (p.y < -20) parts[i] = make(H + 20);
      }
      draw();
      requestAnimationFrame(step);
    }

    resize();
    window.addEventListener('resize', resize);
    window.addEventListener('load', function () { measureZones(); draw(); });
    if ('ResizeObserver' in window) {
      new ResizeObserver(function () { H = host.clientHeight; measureZones(); if (!running) redraw(); }).observe(host);
    }

    if (still) {
      // No drift: just keep the still field in place as the sticky canvas moves.
      window.addEventListener('scroll', redraw, { passive: true });
      return;
    }

    host.addEventListener('pointermove', function (e) {
      var r = host.getBoundingClientRect();
      mouse.x = e.clientX - r.left; mouse.y = e.clientY - r.top;
    });
    host.addEventListener('pointerleave', function () { mouse.x = mouse.y = -9999; });

    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) {
        var was = running;
        running = entries[0].isIntersecting;
        if (running && !was) { measureZones(); requestAnimationFrame(step); }
      }).observe(host);
    }
  }

  Array.prototype.forEach.call(document.querySelectorAll('[data-jd-particles]'), field);
})();
