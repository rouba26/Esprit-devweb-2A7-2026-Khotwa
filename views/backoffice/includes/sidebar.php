<?php
 
$currentPage = $currentPage ?? 'dashboard';
$on = fn(string $p): string => $currentPage === $p ? 'active' : '';
?>
<div class="admin-wrapper">
  <aside class="sidebar sb" id="sidebar">
    <a class="brand" href="index.php?action=admin"><img src="img/logo.svg" alt="" width="28" height="28"> Khotwa <small>Admin</small></a>
    <nav>
      <a class="<?= $on('dashboard') ?>" href="index.php?action=admin">▦ Dashboard</a>
      <a class="<?= $on('users') ?>" href="index.php?action=admin_users">☺ Utilisateurs</a>
      <a class="<?= $on('pros') ?>" href="index.php?action=admin_pros">✚ Professionnels
        <?php if (!empty($pendingPros)): ?><span class="pill"><?= (int) $pendingPros ?></span><?php endif; ?></a>
      <a class="<?= $on('logs') ?>" href="index.php?action=admin_logs">☰ Journal d'activité</a>
      <div class="nav-title">Modules à venir</div>
      <a class="soon" href="#">M1 · Smart Orientation</a>
      <a class="soon" href="#">M2 · Medical Network</a>
      <a class="soon" href="#">M3 · Recovery Journey</a>
      <a class="soon" href="#">M4 · Emotional Support</a>
      <a class="soon" href="#">M5 · Prevention &amp; Edu</a>
      <a class="soon" href="#">M6 · Awareness</a>
      <div class="nav-title">Navigation</div>
      <a href="index.php?action=home">↗ Voir le site</a>
    </nav>
    <div class="sidebar-footer">
      <div class="who"><?= e($_SESSION['username'] ?? '') ?></div>
      <div class="role-tag">Administrateur</div>
      <a class="logout" href="index.php?action=logout">Déconnexion</a>
    </div>
  </aside>
  <div class="main adm-main">
    <div class="topbar top">
      <button class="menu-btn tog" type="button" data-sidebar-toggle aria-label="Menu">☰</button>
      <h1><?= e($pageTitle ?? 'Dashboard') ?></h1>
      <span class="env-tag">BACKOFFICE</span>
    </div>
    <div class="content adm-content">
      <?php render_flashes(); ?>
