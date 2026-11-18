<header class="topbar">
    <button type="button" id="menu-toggle" class="btn btn-ghost">☰ Menu</button>
    <span class="who">M3 · Recovery Journey</span>
</header>
<?php if ($msg = flash()): ?>
    <div class="flash"><?= e($msg) ?></div>
<?php endif; ?>
