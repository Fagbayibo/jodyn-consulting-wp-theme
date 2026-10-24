/**
 * Training calendar download forms.
 *
 * Every form with data-jd-download-form works the same way: the pop-up on the Home and Training
 * pages and the form on the calendar page. It posts name, email, organisation, job title and the
 * updates box to /wp-json/jodyn/v1/calendar-download, which saves the request and returns the
 * PDF's URL; the download then starts.
 *
 * The pop-up: the button opens a modal <dialog> (focus is trapped and Escape closes it natively),
 * and it closes itself after a successful download. While open, the page is locked
 * (body.is-locked also pauses smooth scroll).
 */
(function () {
  'use strict';

  if (!window.jodynDownload) return;
  var t = window.jodynDownload.i18n;

  function wire(form, onDone) {
    var status = form.querySelector('[role="status"]');
    var submit = form.querySelector('[type="submit"]');
    var say = function (text, isError) {
      status.textContent = text;
      status.classList.toggle('is-error', !!isError);
    };
    form.jdSay = say;

    // Clear a field's error as soon as it is edited
    form.addEventListener('input', function (e) {
      if (e.target.getAttribute('aria-invalid') === 'true') e.target.removeAttribute('aria-invalid');
      if (status.classList.contains('is-error') && !form.querySelector('[aria-invalid="true"]')) say('');
    });

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var f = form.elements;
      var okName = f.name.value.trim() !== '', okEmail = f.email.checkValidity() && f.email.value.trim() !== '';
      f.name.setAttribute('aria-invalid', okName ? 'false' : 'true');
      f.email.setAttribute('aria-invalid', okEmail ? 'false' : 'true');
      if (!okName || !okEmail) { say(t.invalid, true); (okName ? f.email : f.name).focus(); return; }

      submit.disabled = true;
      say(t.sending);
      fetch(window.jodynDownload.endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          name: f.name.value.trim(),
          email: f.email.value.trim(),
          organisation: f.organisation.value.trim(),
          job_title: f.job_title.value.trim(),
          updates: f.updates.checked,
          website: f.website.value
        })
      })
        .then(function (res) { return res.json().then(function (body) { return { ok: res.ok, body: body }; }); })
        .then(function (r) {
          if (!r.ok || !r.body.url) { say((r.body && r.body.message) || t.error, true); return; }
          say(t.success);
          var a = document.createElement('a');
          a.href = r.body.url; a.download = ''; a.rel = 'noopener';
          document.body.appendChild(a); a.click(); a.remove();
          form.reset();
          if (onDone) onDone();
        })
        .catch(function () { say(t.error, true); })
        .then(function () { submit.disabled = false; });
    });
  }

  document.querySelectorAll('[data-jd-download-form]').forEach(function (form) {
    if (!form.closest('[data-jd-download]')) wire(form);
  });

  var dialog = document.querySelector('[data-jd-download]');
  var opener = document.querySelector('[data-jd-download-open]');
  if (!dialog || !opener || typeof dialog.showModal !== 'function') return;

  var form = dialog.querySelector('form');
  wire(form, function () { setTimeout(function () { if (dialog.open) dialog.close(); }, 1600); });

  opener.addEventListener('click', function () {
    form.jdSay('');
    dialog.showModal();
    document.body.classList.add('is-locked');
  });
  dialog.querySelector('[data-jd-download-close]').addEventListener('click', function () { dialog.close(); });
  dialog.addEventListener('close', function () { document.body.classList.remove('is-locked'); opener.focus(); });
  // Click on the backdrop (outside the form) closes
  dialog.addEventListener('click', function (e) { if (e.target === dialog) dialog.close(); });
})();
