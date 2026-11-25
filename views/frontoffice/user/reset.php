<?php $pageTitle = 'Nouveau mot de passe'; $currentPage = 'login'; ?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once FRONT_INC . 'head.php'; ?></head>
<body id="top" class="">
<?php require_once FRONT_INC . 'spinner.php'; require_once FRONT_INC . 'navbar.php'; ?>
<main class="page narrow">
  <div class="panel">
    <h1>Nouveau mot de passe</h1>
    <?php if (empty($valid)): ?>
      <div class="alert alert-error">Ce lien est invalide ou a expiré. Refais une demande.</div>
      <p class="links"><a href="index.php?action=forgot">Demander un nouveau lien</a></p>
    <?php else: ?>
      <?php render_errors($errors ?? []); ?>
      <form method="post" action="index.php?action=reset&amp;token=<?= e($token) ?>" data-validate novalidate>
        <?= csrf_field() ?>
        <label for="password">Nouveau mot de passe</label>
        <div class="pw"><input type="password" id="password" name="password" autocomplete="new-password" data-strength><button type="button" class="pw-toggle" data-pw-toggle>Voir</button></div>
        <div class="strength" id="strength"><i></i></div>
        <label for="password_confirm">Confirmer</label>
        <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password">
        <button class="btn btn-primary btn-block" type="submit">Enregistrer</button>
      </form>
    <?php endif; ?>
  </div>
</main>
<?php require_once FRONT_INC . 'footer.php'; require_once FRONT_INC . 'scripts.php'; ?>
</body>
</html>
