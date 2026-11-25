<?php $pageTitle = 'Connexion'; $currentPage = 'login'; ?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once FRONT_INC . 'head.php'; ?></head>
<body id="top" class="">
<?php require_once FRONT_INC . 'spinner.php'; require_once FRONT_INC . 'navbar.php'; ?>
<main class="page narrow">
  <div class="panel">
    <h1>Content de te revoir</h1>
    <?php render_flashes(); render_errors($errors ?? []); ?>
    <form method="post" action="index.php?action=login" data-validate novalidate>
      <?= csrf_field() ?>
      <label for="email">Adresse e-mail</label>
      <input type="email" id="email" name="email" value="<?= old('email') ?>" autocomplete="email">
      <label for="password">Mot de passe</label>
      <div class="pw"><input type="password" id="password" name="password" autocomplete="current-password"><button type="button" class="pw-toggle" data-pw-toggle>Voir</button></div>
      <button class="btn btn-primary btn-block" type="submit">Se connecter</button>
    </form>
    <p class="links"><a href="index.php?action=forgot">Mot de passe oublié ?</a><span>Pas encore de compte ? <a href="index.php?action=register">S'inscrire</a></span></p>
  </div>
</main>
<?php require_once FRONT_INC . 'footer.php'; require_once FRONT_INC . 'scripts.php'; ?>
</body>
</html>
