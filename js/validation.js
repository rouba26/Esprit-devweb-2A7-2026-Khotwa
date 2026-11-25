
(function () {
  'use strict';
  var RE = {
    username: /^[a-zA-Z0-9_\-]{3,50}$/,
    email: /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/,
    phone: /^[0-9 +]{8,20}$/,
    time: /^([01]\d|2[0-3]):[0-5]\d$/
  };

  function setError(input, msg) {
    var old = input.parentNode.querySelector(':scope > .field-error') || (input.closest('.pw') && input.closest('.pw').parentNode.querySelector(':scope > .field-error'));
    if (old) { old.remove(); }
    input.classList.toggle('invalid', !!msg);
    if (msg) {
      var d = document.createElement('div');
      d.className = 'field-error';
      d.textContent = msg;
      var host = input.closest('.pw') || input;
      host.insertAdjacentElement('afterend', d);
    }
    return !msg;
  }

  function checkField(form, input) {
    if (input.closest('[hidden]')) { return true; }            
    var n = input.name, v = input.value.trim(), msg = '';
    if (n === 'password') {
      var optional = input.getAttribute('data-optional') === '1' && v === '';
      if (!optional) {
        if (v === '') { msg = 'Champ obligatoire.'; }
        else if (input.autocomplete === 'new-password' && v !== '') {
          if (input.value.length < 8 || input.value.length > 72) { msg = '8 à 72 caractères.'; }
          else if (!/[A-Za-z]/.test(input.value) || !/\d/.test(input.value)) { msg = 'Au moins une lettre et un chiffre.'; }
        }
      }
    } else if (n === 'password_confirm') {
      var p = form.querySelector('[name=password]');
      if (v === '' || (p && p.value !== input.value)) { msg = 'Les mots de passe ne correspondent pas.'; }
    } else if (n === 'username') { if (!RE.username.test(v)) { msg = '3 à 50 caractères : lettres, chiffres, _ ou -.'; } }
    else if (n === 'email') { if (v.length > 150 || !RE.email.test(v)) { msg = 'Adresse e-mail invalide.'; } }
    else if (n === 'telephone') { if (!RE.phone.test(v)) { msg = 'Numéro invalide.'; } }
    else if (n === 'captcha') { if (!/^\d{1,3}$/.test(v)) { msg = 'Réponds avec un nombre.'; } }
    else if (n === 'horaire_debut' || n === 'horaire_fin') { if (v && !RE.time.test(v)) { msg = 'Format HH:MM.'; } }
    else if (n === 'ville') { if (v.length < 2 || v.length > 100) { msg = 'Ville obligatoire.'; } }
    else if (n === 'specialite') { if (v === '') { msg = 'Choisis une spécialité.'; } }
    else if (n === 'numero_ordre') { if (v.length < 3 || v.length > 50) { msg = '3 caractères minimum.'; } }
    else if (n === 'description' && v.length > 500) { msg = '500 caractères maximum.'; }
    else if (n === 'photo' && input.files.length) { var file = input.files[0]; if (file.size > 2 * 1024 * 1024 || !['image/jpeg','image/png','image/webp'].includes(file.type)) { msg = 'Photo JPG, PNG ou WEBP, 2 Mo maximum.'; } }
    else if (n === 'old_password' && v === '') { msg = 'Champ obligatoire.'; }
    return setError(input, msg);
  }

  Array.prototype.forEach.call(document.querySelectorAll('form[data-validate]'), function (form) {
    var inputs = function () { return Array.prototype.slice.call(form.querySelectorAll('input:not([type=hidden]):not([type=checkbox]):not([type=radio]), select, textarea')); };
    inputs().forEach(function (i) { i.addEventListener('blur', function () { checkField(form, i); }); });
    form.addEventListener('submit', function (e) {
      var ok = true, first = null;
      inputs().forEach(function (i) { if (!checkField(form, i)) { ok = false; first = first || i; } });
      var hd = form.querySelector('[name=horaire_debut]'), hf = form.querySelector('[name=horaire_fin]');
      if (ok && hd && hf && !hd.closest('[hidden]') && hd.value && hf.value && hd.value >= hf.value) { setError(hf, 'La fin doit être après le début.'); ok = false; first = hf; }
      if (!ok) { e.preventDefault(); if (first) { first.focus(); } }
    });
  });
})();
