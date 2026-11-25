<?php $pageTitle = 'Mon espace'; $currentPage = 'dashboard'; ?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once FRONT_INC . 'head.php'; ?></head>
<body id="top" class="">
<?php require_once FRONT_INC . 'spinner.php'; require_once FRONT_INC . 'navbar.php'; ?>
<main class="page">
  <?php render_flashes(); ?>
  <div class="welcome">
    <img class="avatar lg" src="<?= e(avatar_url($user->getPhoto())) ?>" alt="">
    <div>
      <h1>Bonjour, <?= e($user->getUsername()) ?></h1>
      <span class="badge badge-<?= e($user->getRole()) ?>"><?= e(role_label($user->getRole())) ?></span>
    </div>
  </div>

  <?php if ($user->isPro()): ?>
    <div class="grid-3">
      <a class="card link" href="index.php?action=profile_edit"><h3>Mon profil professionnel</h3><p>Spécialité, ville, horaires, photo.</p></a>
      <div class="card soon-card"><h3>Mes demandes</h3><p>Rendez-vous reçus (module M2, bientôt).</p></div>
      <div class="card soon-card"><h3>Mon agenda</h3><p>Rendez-vous confirmés (module M2, bientôt).</p></div>
    </div>
 <?php else: ?>
    <div class="grid-3">
      <a class="card link" href="index.php?action=profile">
        <h3>Mon profil personnel</h3>
        <p>Consulter mes informations et gérer mon compte.</p>
      </a>
      <div class="card soon-card"><h3>Autoévaluation</h3><p>Fais le point sur ta situation (M1, bientôt).</p></div>
      <div class="card soon-card"><h3>Trouver un professionnel</h3><p>Annuaire et rendez-vous (M2, bientôt).</p></div>
      <div class="card soon-card"><h3>Mon parcours</h3><p>Objectifs et progression (M3, bientôt).</p></div>
    </div>
  <?php endif; ?>

  <section class="section-sm">
    <h2>Dernières connexions</h2>
    <?php if (!$history): ?><p class="hint">Aucune connexion enregistrée.</p><?php else: ?>
      <ul class="plain"><?php foreach ($history as $h): ?><li><?= e($h['created_at']) ?> <small>· IP <?= e($h['ip']) ?></small></li><?php endforeach; ?></ul>
    <?php endif; ?>
    <p class="hint">Tu ne reconnais pas une connexion ? <a href="index.php?action=profile_edit">Change ton mot de passe</a>.</p>
  </section>
</main>
<?php require_once FRONT_INC . 'footer.php'; require_once FRONT_INC . 'scripts.php'; ?>
</body>
</html>
