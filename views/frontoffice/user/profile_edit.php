<?php $pageTitle = 'Modifier mon profil'; $currentPage = 'profile'; ?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once FRONT_INC . 'head.php'; ?></head>
<body id="top" class="">
<?php require_once FRONT_INC . 'spinner.php'; require_once FRONT_INC . 'navbar.php'; ?>
<main class="page narrow-lg">
  <?php render_flashes(); ?>
  <div class="panel">
    <h1>Mes informations</h1>
    <?php render_errors($errors ?? []); ?>
    <form method="post" action="index.php?action=profile_edit" enctype="multipart/form-data" data-validate novalidate>
      <?= csrf_field() ?>
      <div class="welcome"><img class="avatar lg" src="<?= e(avatar_url($user->getPhoto())) ?>" alt="">
        <div><label for="photo">Photo de profil <small>(JPG, PNG, WEBP · 2 Mo max)</small></label>
          <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">
          <?php if ($user->getPhoto()): ?><label class="check"><input type="checkbox" name="remove_photo" value="1"> Supprimer ma photo</label><?php endif; ?></div></div>
      <label for="username">Pseudo</label>
      <input type="text" id="username" name="username" value="<?= old('username', $user->getUsername()) ?>" maxlength="50">
      <label for="email">Adresse e-mail</label>
      <input type="email" id="email" name="email" value="<?= old('email', $user->getEmail()) ?>" maxlength="150">
      <?php if ($user->isPro()): ?>
        <fieldset class="pro-fields"><legend>Profil professionnel</legend>
          <label for="specialite">Spécialité</label>
          <select id="specialite" name="specialite"><?php foreach (UserController::SPECIALITES as $s): ?>
            <option <?= ($_POST['specialite'] ?? $user->getSpecialite()) === $s ? 'selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?></select>
          <div class="row2">
            <div><label for="ville">Ville</label><input type="text" id="ville" name="ville" value="<?= old('ville', $user->getVille() ?? '') ?>" maxlength="100"></div>
            <div><label for="telephone">Téléphone</label><input type="tel" id="telephone" name="telephone" value="<?= old('telephone', $user->getTelephone() ?? '') ?>" maxlength="20"></div>
          </div>
          <label for="numero_ordre">Numéro d'ordre / diplôme</label>
          <input type="text" id="numero_ordre" name="numero_ordre" value="<?= old('numero_ordre', $user->getNumeroOrdre() ?? '') ?>" maxlength="50">
          <div class="row2">
            <div><label for="horaire_debut">Début de journée</label><input type="time" id="horaire_debut" name="horaire_debut" value="<?= old('horaire_debut', substr((string) $user->getHoraireDebut(), 0, 5)) ?>"></div>
            <div><label for="horaire_fin">Fin de journée</label><input type="time" id="horaire_fin" name="horaire_fin" value="<?= old('horaire_fin', substr((string) $user->getHoraireFin(), 0, 5)) ?>"></div>
          </div>
          <label for="description">Présentation</label>
          <textarea id="description" name="description" rows="4" maxlength="500"><?= old('description', $user->getDescription() ?? '') ?></textarea>
        </fieldset>
      <?php endif; ?>
      <button class="btn btn-primary" type="submit">Enregistrer</button>
    </form>
  </div>

  <div class="panel">
    <h2>Changer mon mot de passe</h2>
    <?php render_errors($pwdErrors ?? []); ?>
    <form method="post" action="index.php?action=password" data-validate novalidate>
      <?= csrf_field() ?>
      <label for="old_password">Ancien mot de passe</label>
      <input type="password" id="old_password" name="old_password" autocomplete="current-password">
      <label for="password">Nouveau mot de passe</label>
      <div class="pw"><input type="password" id="password" name="password" autocomplete="new-password" data-strength><button type="button" class="pw-toggle" data-pw-toggle>Voir</button></div>
      <div class="strength" id="strength"><i></i></div>
      <label for="password_confirm">Confirmer</label>
      <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password">
      <button class="btn btn-primary" type="submit">Changer</button>
    </form>
  </div>

  <div class="panel danger">
    <h2>Supprimer mon compte</h2>
    <p>Cette action est <b>définitive</b> : ton compte et tes données seront effacés.</p>
    <form method="post" action="index.php?action=delete_account" data-validate novalidate data-confirm="Supprimer définitivement ton compte ?">
      <?= csrf_field() ?>
      <label for="del_pw">Confirme avec ton mot de passe</label>
      <input type="password" id="del_pw" name="password" autocomplete="current-password">
      <button class="btn btn-danger" type="submit">Supprimer mon compte</button>
    </form>
  </div>
</main>
<?php require_once FRONT_INC . 'footer.php'; require_once FRONT_INC . 'scripts.php'; ?>
</body>
</html>
