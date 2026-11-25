<?php $pageTitle = 'Mon profil'; $currentPage = 'profile'; ?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once FRONT_INC . 'head.php'; ?></head>
<body id="top" class="">
<?php require_once FRONT_INC . 'spinner.php'; require_once FRONT_INC . 'navbar.php'; ?>
<main class="page narrow-lg">
  <?php render_flashes(); ?>
  <div class="panel">
    <div class="welcome">
      <img class="avatar lg" src="<?= e(avatar_url($user->getPhoto())) ?>" alt="">
      <div><h1><?= e($user->getUsername()) ?></h1><span class="badge badge-<?= e($user->getRole()) ?>"><?= e(role_label($user->getRole())) ?></span></div>
    </div>
    <table class="info">
      <tr><th>E-mail</th><td><?= e($user->getEmail()) ?></td></tr>
      <tr><th>Membre depuis</th><td><?= e($user->getCreatedAt()) ?></td></tr>
      <tr><th>Dernière connexion</th><td><?= e($user->getDerniereConnexion() ?? '—') ?></td></tr>
      <?php if ($user->isPro()): ?>
        <tr><th>Spécialité</th><td><?= e($user->getSpecialite()) ?></td></tr>
        <tr><th>Ville</th><td><?= e($user->getVille()) ?></td></tr>
        <tr><th>Téléphone</th><td><?= e($user->getTelephone()) ?></td></tr>
        <tr><th>Horaires</th><td><?= e(substr((string) $user->getHoraireDebut(), 0, 5)) ?> – <?= e(substr((string) $user->getHoraireFin(), 0, 5)) ?></td></tr>
        <tr><th>Présentation</th><td><?= nl2br(e($user->getDescription() ?? '—')) ?></td></tr>
      <?php endif; ?>
    </table>
    <a class="btn btn-primary" href="index.php?action=profile_edit">Modifier mon profil</a>
  </div>
  <div class="panel">
    <h2>Historique de mes connexions</h2>
    <?php if (!$history): ?><p class="hint">Aucune connexion enregistrée.</p><?php else: ?>
      <ul class="plain"><?php foreach ($history as $h): ?><li><?= e($h['created_at']) ?> <small>· IP <?= e($h['ip']) ?></small></li><?php endforeach; ?></ul>
    <?php endif; ?>
  </div>
</main>
<?php require_once FRONT_INC . 'footer.php'; require_once FRONT_INC . 'scripts.php'; ?>
</body>
</html>
