<?php $pageTitle = 'Dashboard'; $currentPage = 'dashboard'; ?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once BACK_INC . 'head.php'; ?></head>
<body>
<?php require_once BACK_INC . 'sidebar.php'; ?>
<?php $maxM = max(1, max($stats['months'])); $maxD = max(1, max($stats['days'])); ?>
<div class="stats-grid">
  <div class="stat-card stat"><div class="label">Comptes</div><div class="value"><?= (int) $stats['total'] ?></div></div>
  <div class="stat-card stat"><div class="label">Utilisateurs</div><div class="value accent"><?= (int) $stats['byRole']['user'] ?></div></div>
  <div class="stat-card stat"><div class="label">Professionnels</div><div class="value green"><?= (int) $stats['byRole']['professional'] ?></div></div>
  <div class="stat-card stat"><div class="label">Administrateurs</div><div class="value red"><?= (int) $stats['byRole']['admin'] ?></div></div>
  <a class="stat-card stat link" href="index.php?action=admin_pros"><div class="label">Pros à valider</div><div class="value gold"><?= (int) $stats['pending'] ?></div></a>
  <a class="stat-card stat link" href="index.php?action=admin_users&amp;etat=bloque"><div class="label">Comptes bloqués</div><div class="value"><?= (int) $stats['bloques'] ?></div></a>
  <a class="stat-card stat link" href="index.php?action=admin_logs&amp;type=login_failed"><div class="label">Échecs de connexion (24 h)</div><div class="value"><?= (int) $stats['echecs24'] ?></div></a>
</div>
<div class="two-col">
  <div class="card panel"><h2>Inscriptions · 6 derniers mois</h2>
    <div class="bars"><?php foreach ($stats['months'] as $m => $c): ?>
      <div class="bar"><span class="num"><?= $c ?></span><i style="height:<?= round($c / $maxM * 100) ?>%"></i><span class="lbl"><?= e(substr($m, 5)) ?>/<?= e(substr($m, 2, 2)) ?></span></div>
    <?php endforeach; ?></div></div>
  <div class="card panel"><h2>Connexions · 7 derniers jours</h2>
    <div class="bars alt"><?php foreach ($stats['days'] as $d => $c): ?>
      <div class="bar"><span class="num"><?= $c ?></span><i style="height:<?= round($c / $maxD * 100) ?>%"></i><span class="lbl"><?= e(substr($d, 8)) ?>/<?= e(substr($d, 5, 2)) ?></span></div>
    <?php endforeach; ?></div></div>
</div>
<p class="hint">Les statistiques sont agrégées et anonymisées : aucune donnée de santé personnelle n'est affichée ici.</p>
<?php require_once BACK_INC . 'footer.php'; require_once BACK_INC . 'scripts.php'; ?>
</body>
</html>
