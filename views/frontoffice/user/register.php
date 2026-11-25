<?php $pageTitle = 'Inscription'; $currentPage = 'register'; ?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once FRONT_INC . 'head.php'; ?></head>
<body id="top" class="">
<?php require_once FRONT_INC . 'spinner.php'; require_once FRONT_INC . 'navbar.php'; ?>
<?php
$type = $_POST['type'] ?? (($_GET['type'] ?? '') === 'professional' ? 'professional' : 'user');
$type = $type === 'professional' ? 'professional' : 'user';
?>
<main class="page narrow">
  <div class="panel">
    <h1>Créer mon compte</h1>
    <?php render_errors($errors ?? []); ?>
    <form method="post" action="index.php?action=register" data-validate novalidate>
      <?= csrf_field() ?>
      <div class="seg">
        <label><input type="radio" name="type" value="user" <?= $type === 'user' ? 'checked' : '' ?> data-type-switch> Je cherche de l'aide</label>
        <label><input type="radio" name="type" value="professional" <?= $type === 'professional' ? 'checked' : '' ?> data-type-switch> Je suis professionnel(le)</label>
      </div>

      <label for="username">Pseudo <small>(pas besoin de ton vrai nom)</small></label>
      <input type="text" id="username" name="username" value="<?= old('username') ?>" autocomplete="username" maxlength="50">
      <label for="email">Adresse e-mail</label>
      <input type="email" id="email" name="email" value="<?= old('email') ?>" autocomplete="email" maxlength="150">
      <label for="password">Mot de passe <small>(8 caractères min., une lettre et un chiffre)</small></label>
      <div class="pw"><input type="password" id="password" name="password" autocomplete="new-password" data-strength><button type="button" class="pw-toggle" data-pw-toggle>Voir</button></div>
      <div class="strength" id="strength"><i></i></div>
      <label for="password_confirm">Confirmer le mot de passe</label>
      <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password">

      <fieldset class="pro-fields" data-pro-fields <?= $type === 'professional' ? '' : 'hidden' ?>>
        <legend>Ton profil professionnel</legend>
        <p class="hint">Ces informations sont vérifiées par l'équipe avant la validation de ton compte.</p>
        <label for="specialite">Spécialité</label>
        <select id="specialite" name="specialite">
          <option value="">Choisir…</option>
          <?php foreach (UserController::SPECIALITES as $s): ?>
            <option <?= ($_POST['specialite'] ?? '') === $s ? 'selected' : '' ?>><?= e($s) ?></option>
          <?php endforeach; ?>
        </select>
        <div class="row2">
          <div><label for="ville">Ville</label><input type="text" id="ville" name="ville" value="<?= old('ville') ?>" maxlength="100"></div>
          <div><label for="telephone">Téléphone</label><input type="tel" id="telephone" name="telephone" value="<?= old('telephone') ?>" maxlength="20"></div>
        </div>
        <label for="numero_ordre">Numéro d'ordre / diplôme</label>
        <input type="text" id="numero_ordre" name="numero_ordre" value="<?= old('numero_ordre') ?>" maxlength="50">
        <label for="description">Présentation courte <small>(500 caractères max.)</small></label>
        <textarea id="description" name="description" rows="3" maxlength="500"><?= old('description') ?></textarea>
      </fieldset>

      <label for="captcha">Anti-robot : combien font <b><?= e($captcha) ?></b> ?</label>
      <input type="text" id="captcha" name="captcha" inputmode="numeric" autocomplete="off" maxlength="3">

      <button class="btn btn-primary btn-block" type="submit">Créer mon compte</button>
    </form>
    <p class="links"><span>Déjà inscrit(e) ? <a href="index.php?action=login">Se connecter</a></span></p>
  </div>
</main>
<?php require_once FRONT_INC . 'footer.php'; require_once FRONT_INC . 'scripts.php'; ?>
</body>
</html>
