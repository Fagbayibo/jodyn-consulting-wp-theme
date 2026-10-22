/**
 * Training calendar download dialog.
 *
 * The button opens a modal <dialog> (focus is trapped and Escape closes it natively).
 * The form posts name and email to /wp-json/jodyn/v1/calendar-download, which saves the
 * request and returns the PDF's URL; the download then starts and the dialog closes.
 * While open, the page is locked (body.is-locked also pauses smooth scroll).
 */
(function () {
  'use strict';

  var dialog = document.querySelector('[data-jd-download]');
  var opener = document.querySelector('[data-jd-download-open]');
  if (!dialog || !opener || typeof dialog.showModal !== 'function' || !window.jodynDownload) return;

  var form = dialog.querySelector('form');
  var status = dialog.querySelector('.jd-dl__status');
  var submit = dialog.querySelector('.jd-dl__submit');
  var t = window.jodynDownload.i18n;

  function say(text, isError) {
    status.textContent = text;
    status.classList.toggle('is-error', !!isError);
  }

  function open() {
    say('');
    dialog.showModal();
    document.body.classList.add('is-locked');
  }

  opener.addEventListener('click', open);
  dialog.querySelector('[data-jd-download-close]').addEventListener('click', function () { dialog.close(); });
  dialog.addEventListener('close', function () { document.body.classList.remove('is-locked'); opener.focus(); });
  // Click on the backdrop (outside the form) closes
  dialog.addEventListener('click', function (e) { if (e.target === dialog) dialog.close(); });

  // Clear a field's error as soon as it is edited
  form.addEventListener('input', function (e) {
    if (e.target.getAttribute('aria-invalid') === 'true') e.target.removeAttribute('aria-invalid');
    if (status.classList.contains('is-error') && !form.querySelector('[aria-invalid="true"]')) say('');
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var name = form.elements.name, email = form.elements.email;
    var okName = name.value.trim() !== '', okEmail = email.checkValidity() && email.value.trim() !== '';
    name.setAttribute('aria-invalid', okName ? 'false' : 'true');
    email.setAttribute('aria-invalid', okEmail ? 'false' : 'true');
    if (!okName || !okEmail) { say(t.invalid, true); (okName ? email : name).focus(); return; }

    submit.disabled = true;
    say(t.sending);
    fetch(window.jodynDownload.endpoint, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ name: name.value.trim(), email: email.value.trim(), website: form.elements.website.value })
    })
      .then(function (res) { return res.json().then(function (body) { return { ok: res.ok, body: body }; }); })
      .then(function (r) {
        if (!r.ok || !r.body.url) { say((r.body && r.body.message) || t.error, true); return; }
        say(t.success);
        var a = document.createElement('a');
        a.href = r.body.url; a.download = ''; a.rel = 'noopener';
        document.body.appendChild(a); a.click(); a.remove();
        form.reset();
        setTimeout(function () { if (dialog.open) dialog.close(); }, 1600);
      })
      .catch(function () { say(t.error, true); })
      .then(function () { submit.disabled = false; });
  });
})();
