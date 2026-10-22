/**
 * Home · Who we are.
 *
 * The three circles (Direction, Systems, People) start pushed apart and turned, their outlines
 * draw in, and scrolling pulls them together until the overlaps fill and the lime Growth core
 * pops in. Once settled, the core breathes and sends out ripples while the diagram is on screen.
 * The diagram tilts toward a mouse cursor. The colour blocks wipe open from their corner.
 */
(function () {
  'use strict';

  var section = document.querySelector('[data-jd-who]');
  if (!section || !window.JodynMotion) return;

  JodynMotion.ready(function () {
    var stage = section.querySelector('.jd-venn');
    var svg = section.querySelector('.jd-venn__svg');
    if (!stage || !svg) return;

    var q = gsap.utils.selector(section);
    var geo = [1, 2, 3].map(function (n) { return document.getElementById('jd-vc' + n); });
    var labels = q('.jd-venn__labels');
    var ORIGIN = '280 277'; // where the three circles meet
    // Each circle's resting centre, and the direction it is pushed out along before it converges
    var HOME = [{ x: 280, y: 175, dx: 0, dy: -1 }, { x: 192, y: 327, dx: -0.87, dy: 0.5 }, { x: 368, y: 327, dx: 0.87, dy: 0.5 }];
    var SPREAD = 170;
    var state = { spread: 1 };

    function apply() {
      HOME.forEach(function (h, i) {
        var ox = h.dx * SPREAD * state.spread, oy = h.dy * SPREAD * state.spread;
        geo[i].setAttribute('cx', h.x + ox);
        geo[i].setAttribute('cy', h.y + oy);
        labels[i].setAttribute('transform', 'translate(' + ox + ' ' + oy + ')');
      });
    }

    // Idle loop: runs only after the circles have met, and only while the diagram is on screen
    var idle = gsap.timeline({ paused: true });
    q('.jd-venn__ripple').forEach(function (r, i) {
      idle.fromTo(r, { attr: { r: 64 }, opacity: 0.6 }, { attr: { r: 170 }, opacity: 0, duration: 3, ease: 'power1.out', repeat: -1 }, i * 1.5);
    });
    idle.to(q('.jd-venn__core'), { attr: { r: 69 }, duration: 1.8, ease: 'sine.inOut', yoyo: true, repeat: -1 }, 0);
    var settled = false, onScreen = false;
    function syncIdle() { if (settled && onScreen) idle.play(); else idle.pause(); }
    ScrollTrigger.create({ trigger: stage, start: 'top bottom', end: 'bottom top', onToggle: function (s) { onScreen = s.isActive; syncIdle(); } });

    gsap.timeline({
      scrollTrigger: {
        trigger: stage, start: 'top 88%', end: 'center 45%', scrub: 1.2,
        onUpdate: function (s) { if (!settled && s.progress > 0.97) { settled = true; syncIdle(); } }
      }
    })
      .fromTo(q('.jd-venn__rig'), { rotation: -35, svgOrigin: ORIGIN }, { rotation: 0, duration: 1.3, ease: 'power3.out' }, 0)
      // The attribute, not the CSS property: GSAP rounds px CSS values, which would make 0→1 jump instead of draw
      .fromTo(q('.jd-venn__stroke'), { attr: { 'stroke-dashoffset': 1 } }, { attr: { 'stroke-dashoffset': 0 }, duration: 0.7, stagger: 0.12, ease: 'power2.inOut' }, 0)
      .fromTo(q('.jd-venn__fill'), { opacity: 0 }, { opacity: 1, duration: 0.4, stagger: 0.1 }, 0.25)
      .fromTo(state, { spread: 1 }, { spread: 0, duration: 1, ease: 'power3.inOut', onUpdate: apply }, 0.3)
      .fromTo(q('.jd-venn__lens'), { opacity: 0 }, { opacity: 1, duration: 0.3, stagger: 0.08 }, 0.95)
      .fromTo(q('.jd-venn__triple'), { opacity: 0 }, { opacity: 1, duration: 0.25 }, 1.15)
      .fromTo(q('.jd-venn__labels text'), { opacity: 0, y: 14 }, { opacity: 1, y: 0, duration: 0.35, stagger: 0.05 }, 0.95)
      .fromTo(q('.jd-venn__core-group'), { scale: 0, svgOrigin: ORIGIN }, { scale: 1, duration: 0.45, ease: 'back.out(2.6)' }, 1.2);

    // 3D tilt toward the cursor (mouse and trackpad only)
    if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
      var rx = gsap.quickTo(svg, 'rotationX', { duration: 0.8, ease: 'power3.out' });
      var ry = gsap.quickTo(svg, 'rotationY', { duration: 0.8, ease: 'power3.out' });
      stage.addEventListener('pointermove', function (e) {
        var r = stage.getBoundingClientRect();
        ry(((e.clientX - r.left) / r.width - 0.5) * 18);
        rx(-((e.clientY - r.top) / r.height - 0.5) * 18);
      });
      stage.addEventListener('pointerleave', function () { rx(0); ry(0); });
    }

    // Colour blocks grow open from their top-left corner
    gsap.fromTo(q('.jd-tile--lime, .jd-tile--dark'),
      { clipPath: 'inset(0% 100% 100% 0% round 28px)' },
      { clipPath: 'inset(0% 0% 0% 0% round 28px)', duration: 1.2, ease: 'expo.out', stagger: 0.15, clearProps: 'clipPath',
        scrollTrigger: { trigger: q('.jd-who__bento')[0], start: 'top 80%', once: true } });
  });
})();
