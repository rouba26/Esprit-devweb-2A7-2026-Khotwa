
(function () {
  'use strict';
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  window.addEventListener('load', function () {
    var sp = $('#spinner');
    if (sp) { sp.classList.add('done'); setTimeout(function () { sp.remove(); }, 400); }
  });

  var nt = $('[data-nav-toggle]');
  if (nt) { nt.addEventListener('click', function () { $('.header').classList.toggle('open'); }); }
  var st = $('[data-sidebar-toggle]');
  if (st) { st.addEventListener('click', function () { $('#sidebar').classList.toggle('open'); }); }

  $$('a.soon').forEach(function (a) { a.addEventListener('click', function (e) { e.preventDefault(); }); });

  $$('form[data-confirm]').forEach(function (f) {
    f.addEventListener('submit', function (e) { if (!window.confirm(f.getAttribute('data-confirm'))) { e.preventDefault(); } });
  });
  $$('[data-confirm-btn]').forEach(function (b) {
    b.addEventListener('click', function (e) { if (!window.confirm(b.getAttribute('data-confirm-btn'))) { e.preventDefault(); } });
  });

  $$('select[data-autosubmit]').forEach(function (s) {
    s.addEventListener('change', function () {
      if (window.confirm('Changer le rôle de cet utilisateur ?')) { s.form.requestSubmit(); } else { window.location.reload(); }
    });
  });

  $$('[data-pw-toggle]').forEach(function (b) {
    b.addEventListener('click', function () {
      var i = b.parentNode.querySelector('input');
      var show = i.type === 'password';
      i.type = show ? 'text' : 'password';
      b.textContent = show ? 'Cacher' : 'Voir';
    });
  });

  var pw = $('input[data-strength]'), bar = $('#strength i');
  if (pw && bar) {
    pw.addEventListener('input', function () {
      var v = pw.value, s = 0;
      if (v.length >= 8) s++;
      if (/[a-z]/.test(v) && /[A-Z]/.test(v)) s++;
      if (/\d/.test(v)) s++;
      if (/[^A-Za-z0-9]/.test(v) || v.length >= 12) s++;
      bar.style.width = (s * 25) + '%';
      bar.style.background = ['#b3392f', '#b3392f', '#D79B69', '#8EB7A0', '#2e7d4f'][s];
    });
  }

  var fields = $('[data-pro-fields]');
  if (fields) {
    var update = function () {
      var radio = $('input[data-type-switch]:checked');
      var sel = $('[data-role-select]');
      var isPro = radio ? radio.value === 'professional' : (sel ? sel.value === 'professional' : false);
      fields.hidden = !isPro;
    };
    $$('input[data-type-switch]').forEach(function (r) { r.addEventListener('change', update); });
    var rs = $('[data-role-select]');
    if (rs) { rs.addEventListener('change', update); }
    update();
  }
})();
