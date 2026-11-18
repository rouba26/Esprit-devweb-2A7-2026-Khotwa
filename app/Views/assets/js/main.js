window.addEventListener('load', function () {
    var p = document.getElementById('preloader');
    if (p) { p.style.display = 'none'; }
});
document.getElementById('menu-toggle')?.addEventListener('click', function () {
    document.getElementById('sidebar')?.classList.toggle('open');
});
document.querySelectorAll('form[data-confirm]').forEach(function (f) {
    f.addEventListener('submit', function (e) {
        if (!confirm(f.dataset.confirm)) { e.preventDefault(); }
    });
});
