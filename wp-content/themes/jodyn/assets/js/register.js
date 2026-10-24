/**
 * Registration page.
 *
 * Form (with or without motion): three steps (Programme · Your details · Finish), each checked
 * before Continue. The summary follows the chosen programme: Preferred format shows only for
 * hybrid runs; "On behalf of an organisation" opens the organisation fields, makes Organisation
 * required and adds one row per extra participant; the summary then shows the total and, from
 * the group size, "Group discount applies". Complete Registration posts to
 * /wp-json/jodyn/v1/register and the thank-you message replaces the form.
 *
 * Hero (motion only): the ticket of four steps rises into place after the heading.
 */
(function () {
  'use strict';

  var root = document.querySelector('[data-jd-reg]');
  var form = root && root.querySelector('[data-jd-reg-form]');
  if (form && window.jodynRegister) {
    var DATA = JSON.parse(form.querySelector('[data-jd-reg-data]').textContent);
    var t = window.jodynRegister.i18n;
    var $ = function (s) { return root.querySelector(s); };
    var panes = [].slice.call(form.querySelectorAll('[data-pane]'));
    var prog = form.querySelectorAll('[data-prog]');
    var status = form.querySelector('[role="status"]');
    var people = $('[data-reg-people]');
    var count = $('[data-reg-count]');
    var at = 0;
    var naira = function (n) { return '₦' + n.toLocaleString('en-NG'); };
    var say = function (text, isError) { status.textContent = text; status.classList.toggle('is-error', !!isError); };
    var isOrg = function () { return form.elements.type.value === 'organisation'; };
    var size = function () { return Math.max(2, Math.min(30, parseInt(count.value, 10) || 2)); };

    // One row (name, email, job title) for each participant after the first
    var rows = function () {
      var want = size() - 1, d = people.dataset;
      while (people.children.length < want) {
        var n = people.children.length + 2, li = document.createElement('li');
        var cell = function (key, type, label) {
          var l = document.createElement('label'), s = document.createElement('span'), i = document.createElement('input');
          l.className = 'jd-reg-form__field'; s.textContent = label; i.type = type; i.dataset.other = key;
          l.appendChild(s); l.appendChild(i); return l;
        };
        var head = document.createElement('span');
        head.className = 'jd-reg-form__person'; head.textContent = d.label.replace('%d', n);
        li.appendChild(head);
        li.appendChild(cell('name', 'text', d.name));
        li.appendChild(cell('email', 'email', d.email));
        li.appendChild(cell('job_title', 'text', d.job));
        people.appendChild(li);
      }
      while (people.children.length > want) people.lastElementChild.remove();
    };

    var sync = function () {
      var p = DATA[form.elements.programme.value];
      $('[data-sum-empty]').hidden = !!p;
      $('[data-sum-body]').hidden = !p;
      $('[data-reg-format]').hidden = !(p && p.format === 'hybrid');
      var org = isOrg();
      $('[data-reg-org]').hidden = !org;
      $('[data-reg-org-star]').hidden = !org;
      form.elements.organisation.required = org;
      if (org) rows();
      if (!p) return;
      var fmt = p.formatLabel;
      if (p.format === 'hybrid') {
        var pf = form.querySelector('[name="preferred_format"]:checked');
        if (pf) fmt += ' (' + pf.nextElementSibling.textContent.toLowerCase() + ')';
      }
      $('[data-sum-title]').textContent = p.title;
      $('[data-sum-when]').textContent = p.when;
      $('[data-sum-format]').textContent = fmt;
      $('[data-sum-venue]').textContent = p.venue;
      $('[data-sum-fee]').textContent = p.fee ? naira(p.fee) : p.feeText;
      $('[data-sum-total]').hidden = !org || !p.fee;
      $('[data-sum-n]').textContent = size();
      $('[data-sum-sum]').textContent = naira(p.fee * size());
      var deal = $('[data-sum-deal]');
      deal.hidden = !(org && size() >= +deal.dataset.size);
    };

    // Required fields in what is showing; marks the empty ones and focuses the first
    var valid = function (scope) {
      var bad = [].filter.call(scope.querySelectorAll('input[required], select[required]'), function (i) {
        if (i.closest('[hidden]')) return false;
        var ok = i.type === 'checkbox' ? i.checked : i.value.trim() !== '' && i.checkValidity();
        i.setAttribute('aria-invalid', ok ? 'false' : 'true');
        return !ok;
      });
      if (bad.length) { say(t.invalid, true); bad[0].focus(); }
      return !bad.length;
    };

    var show = function (i) {
      at = i;
      panes.forEach(function (p, k) { p.hidden = k !== i; });
      prog.forEach(function (p, k) {
        p.classList.toggle('is-done', k < i);
        if (k === i) p.setAttribute('aria-current', 'step'); else p.removeAttribute('aria-current');
      });
      form.querySelector('[data-go="-1"]').hidden = i === 0;
      form.querySelector('[data-go="1"]').hidden = i === panes.length - 1;
      form.querySelector('[data-reg-submit]').hidden = i !== panes.length - 1;
      say('');
      // Back to the top of the form, and focus its first field for keyboard and screen reader users
      var y = form.getBoundingClientRect().top + window.scrollY - 120;
      if (window.scrollY > y) {
        var lenis = window.JodynMotion && window.JodynMotion.lenis;
        if (lenis) lenis.scrollTo(y, { force: true }); else window.scrollTo({ top: y, behavior: 'smooth' });
      }
      var first = panes[i].querySelector('input:not([type=radio]):not([type=checkbox]), select');
      if (first) first.focus({ preventScroll: true });
      if (window.ScrollTrigger) window.ScrollTrigger.refresh();
    };

    form.addEventListener('click', function (e) {
      var b = e.target.closest('[data-go]');
      if (!b) return;
      var d = +b.dataset.go;
      if (d > 0 && !valid(panes[at])) return;
      show(at + d);
    });
    form.addEventListener('input', function (e) {
      if (e.target.getAttribute('aria-invalid') === 'true') e.target.removeAttribute('aria-invalid');
      if (status.classList.contains('is-error') && !form.querySelector('[aria-invalid="true"]')) say('');
      sync();
    });
    form.addEventListener('change', sync);

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!valid(panes[at])) return;
      var f = form.elements, submit = form.querySelector('[data-reg-submit]');
      var body = {};
      ['programme', 'type', 'name', 'email', 'phone', 'job_title', 'organisation', 'participants', 'invoice_name', 'invoice_email', 'invoice_phone', 'source', 'requirements', 'website'].forEach(function (k) { body[k] = f[k].value.trim(); });
      body.preferred_format = (form.querySelector('[name="preferred_format"]:checked') || {}).value || '';
      body.terms = f.terms.checked;
      body.updates = f.updates.checked;
      body.others = [].map.call(people.children, function (li) {
        var o = {};
        li.querySelectorAll('[data-other]').forEach(function (i) { o[i.dataset.other] = i.value.trim(); });
        return o;
      });
      submit.disabled = true;
      say(t.sending);
      fetch(window.jodynRegister.endpoint, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) })
        .then(function (res) { return res.json().then(function (r) { return { ok: res.ok, body: r }; }); })
        .then(function (r) {
          if (!r.ok) { say((r.body && r.body.message) || t.error, true); return; }
          var done = $('[data-jd-reg-done]');
          done.querySelector('[data-done-title]').textContent = r.body.title || DATA[f.programme.value].title;
          done.querySelector('[data-done-email]').textContent = r.body.email || f.email.value;
          $('[data-jd-reg-main]').hidden = true;
          done.hidden = false;
          done.focus({ preventScroll: true });
          var y = done.getBoundingClientRect().top + window.scrollY - 140;
          var lenis = window.JodynMotion && window.JodynMotion.lenis;
          if (lenis) { lenis.resize(); lenis.scrollTo(y, { immediate: true, force: true }); } else window.scrollTo(0, y);
          if (window.ScrollTrigger) window.ScrollTrigger.refresh();
        })
        .catch(function () { say(t.error, true); })
        .then(function () { submit.disabled = false; });
    });

    sync();
  }

  if (!window.JodynMotion) return;

  JodynMotion.ready(function () {
    var pass = document.querySelector('[data-jd-reg-pass]');
    if (pass) gsap.from(pass, { y: 60, rotation: -2, opacity: 0, duration: 1.2, ease: 'expo.out', delay: 0.5 });
  });
})();
