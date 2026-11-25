<?php
 
$currentPage = $currentPage ?? '';
$role = $_SESSION['role'] ?? null;
$logged = !empty($_SESSION['user_id']);
$active = fn(string $p): string => $currentPage === $p ? 'active' : '';
?>
<header class="header">
  <div class="container nav">
    <a class="logo" href="index.php?action=home"><img src="img/logo.svg" alt="" width="32" height="32"><span>Khotwa</span></a>
    <button class="burger" type="button" aria-label="Menu" data-nav-toggle>☰</button>
    <nav class="nav-links" id="nav-links">
      <a class="nav-link <?= $active('home') ?>" href="index.php?action=home">Accueil</a>
      <?php if ($logged && $role !== 'admin'): ?>
        <a class="nav-link <?= $active('dashboard') ?>" href="index.php?action=dashboard">Mon espace</a>
        <a class="nav-link soon" href="#" title="Bientôt">Autoévaluation</a>
        <a class="nav-link soon" href="#" title="Bientôt">Professionnels</a>
        <a class="nav-link soon" href="#" title="Bientôt">Mon parcours</a>
        <a class="nav-link soon" href="#" title="Bientôt">Communauté</a>
      <?php elseif (!$logged): ?>
        <a class="nav-link soon" href="#" title="Bientôt">Professionnels</a>
        <a class="nav-link soon" href="#" title="Bientôt">Academy</a>
        <a class="nav-link soon" href="#" title="Bientôt">Événements</a>
        <a class="nav-link <?= $active('register') ?>" href="index.php?action=register&amp;type=professional">Devenir partenaire</a>
      <?php endif; ?>
    </nav>
    <div class="nav-cta">
      <?php if ($logged): ?>
        <?php if ($role === 'admin'): ?><a class="btn btn-ghost" href="index.php?action=admin">Back Office</a><?php endif; ?>
        <a class="nav-link <?= $active('profile') ?> nav-user" href="index.php?action=profile"><?= e($_SESSION['username'] ?? '') ?></a>
        <a class="btn btn-primary" href="index.php?action=logout">Déconnexion</a>
      <?php else: ?>
        <a class="btn btn-ghost <?= $active('login') ?>" href="index.php?action=login">Connexion</a>
        <a class="btn btn-primary" href="index.php?action=register">S'inscrire</a>
      <?php endif; ?>
    </div>
  </div>
</header>
