/**
 * Contact page.
 *
 * - The hero's Send a message links select their enquiry type in the form and glide down to it
 *   (the address keeps ?enquiry=, so a reload or shared link opens the same way).
 * - The form checks the required fields, posts to /wp-json/jodyn/v1/contact and the thank-you
 *   message replaces it.
 */
(function () {
  'use strict';

  var form = document.querySelector('[data-jd-ct-form]');
  if (!form || !window.jodynContact) return;
  var t = window.jodynContact.i18n;
  var status = form.querySelector('[role="status"]');
  var say = function (text, isError) { status.textContent = text; status.classList.toggle('is-error', !!isError); };

  var glide = function (el) {
    var y = el.getBoundingClientRect().top + window.scrollY - 110;
    var lenis = window.JodynMotion && window.JodynMotion.lenis;
    if (lenis) lenis.scrollTo(y, { force: true }); else window.scrollTo({ top: y, behavior: 'smooth' });
  };

  document.addEventListener('click', function (e) {
    var a = e.target.closest('[data-jd-enquiry]');
    if (!a) return;
    var key = a.dataset.jdEnquiry;
    if (!form.querySelector('option[value="' + key + '"]')) return;
    e.preventDefault();
    form.elements.enquiry.value = key;
    if (window.history.replaceState) window.history.replaceState(null, '', a.href);
    glide(form.closest('section'));
    form.elements.first_name.focus({ preventScroll: true });
  });

  form.addEventListener('input', function (e) {
    if (e.target.getAttribute('aria-invalid') === 'true') e.target.removeAttribute('aria-invalid');
    if (status.classList.contains('is-error') && !form.querySelector('[aria-invalid="true"]')) say('');
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var bad = [].filter.call(form.querySelectorAll('[required]'), function (i) {
      var ok = i.type === 'checkbox' ? i.checked : i.value.trim() !== '' && i.checkValidity();
      i.setAttribute('aria-invalid', ok ? 'false' : 'true');
      return !ok;
    });
    if (bad.length) { say(t.invalid, true); bad[0].focus(); return; }

    var f = form.elements, submit = form.querySelector('[type="submit"]'), body = {};
    ['first_name', 'last_name', 'email', 'phone', 'organisation', 'job_title', 'enquiry', 'message', 'website'].forEach(function (k) { body[k] = f[k].value.trim(); });
    if (f.programme) body.programme = f.programme.value;
    body.privacy = f.privacy.checked;
    submit.disabled = true;
    say(t.sending);
    fetch(window.jodynContact.endpoint, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) })
      .then(function (res) { return res.json().then(function (r) { return { ok: res.ok, body: r }; }); })
      .then(function (r) {
        if (!r.ok) { say((r.body && r.body.message) || t.error, true); return; }
        var done = document.querySelector('[data-jd-ct-done]');
        form.hidden = true;
        done.hidden = false;
        done.focus({ preventScroll: true });
        glide(done);
        if (window.ScrollTrigger) window.ScrollTrigger.refresh();
      })
      .catch(function () { say(t.error, true); })
      .then(function () { submit.disabled = false; });
  });
})();
