<?php $pageTitle = 'Mot de passe oublié'; $currentPage = 'login'; ?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once FRONT_INC . 'head.php'; ?></head>
<body id="top" class="">
<?php require_once FRONT_INC . 'spinner.php'; require_once FRONT_INC . 'navbar.php'; ?>
<main class="page narrow">
  <div class="panel">
    <h1>Mot de passe oublié</h1>
    <?php render_errors($errors ?? []); ?>
    <?php if (!empty($sent)): ?>
      <div class="alert alert-success">Si cette adresse correspond à un compte, un lien de réinitialisation vient d'être envoyé (valable 1 heure).</div>
      <?php if (!empty($devLink)): ?>
        <div class="alert alert-warning"><b>Mode développement :</b> pas d'envoi réel de mail en local. Utilise ce lien (copié aussi dans <code>storage/mails.log</code>) :<br><a href="<?= e($devLink) ?>"><?= e($devLink) ?></a></div>
      <?php endif; ?>
    <?php else: ?>
      <p>Saisis ton adresse e-mail, nous t'enverrons un lien pour choisir un nouveau mot de passe.</p>
      <form method="post" action="index.php?action=forgot" data-validate novalidate>
        <?= csrf_field() ?>
        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email" value="<?= old('email') ?>">
        <button class="btn btn-primary btn-block" type="submit">Envoyer le lien</button>
      </form>
    <?php endif; ?>
    <p class="links"><a href="index.php?action=login">← Retour à la connexion</a></p>
  </div>
</main>
<?php require_once FRONT_INC . 'footer.php'; require_once FRONT_INC . 'scripts.php'; ?>
</body>
</html>
