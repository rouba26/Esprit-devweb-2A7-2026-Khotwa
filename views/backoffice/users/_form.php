<?php
 
$editing = $user !== null;
$v = fn(string $k, string $getter) => e($_POST[$k] ?? ($user ? ($user->$getter() ?? '') : ''));
$role = $_POST['role'] ?? ($user ? $user->getRole() : 'user');
$checked = $_POST ? !empty($_POST['actif']) : ($user ? (bool) $user->getActif() : true);
$self = $editing && $user->getId() === (int) $_SESSION['user_id'];
?>
<div class="card panel form-card">
  <?php render_errors($errors ?? []); ?>
  <form method="post" action="index.php?action=<?= $editing ? 'admin_user_edit&amp;id=' . $user->getId() : 'admin_user_create' ?>" data-validate novalidate>
    <?= csrf_field() ?>
    <div class="row2">
      <div><label for="username">Pseudo</label><input type="text" id="username" name="username" value="<?= $v('username', 'getUsername') ?>" maxlength="50"></div>
      <div><label for="email">E-mail</label><input type="email" id="email" name="email" value="<?= $v('email', 'getEmail') ?>" maxlength="150"></div>
    </div>
    <div class="row2">
      <div><label for="password">Mot de passe <?= $editing ? '<small>(laisser vide pour ne pas changer)</small>' : '' ?></label>
        <input type="password" id="password" name="password" autocomplete="new-password"  data-optional="<?= $editing ? '1' : '0' ?>"></div>
      <div><label for="role">Rôle</label>
        <select id="role" name="role" data-role-select <?= $self ? 'data-locked' : '' ?>>
          <?php foreach (['user', 'professional', 'admin'] as $r): ?><option value="<?= $r ?>" <?= $role === $r ? 'selected' : '' ?>><?= e(role_label($r)) ?></option><?php endforeach; ?></select>
        <?php if ($self): ?><small>Tu ne peux pas modifier ton propre rôle.</small><?php endif; ?></div>
    </div>
    <label class="check"><input type="checkbox" name="actif" value="1" <?= $checked ? 'checked' : '' ?>> Compte actif (décoche pour bloquer)</label>

    <fieldset class="pro-fields" data-pro-fields <?= $role === 'professional' ? '' : 'hidden' ?>>
      <legend>Profil professionnel</legend>
      <div class="row2">
        <div><label for="specialite">Spécialité</label><select id="specialite" name="specialite"><option value="">Choisir…</option>
          <?php foreach (UserController::SPECIALITES as $s): ?><option <?= ($_POST['specialite'] ?? ($user ? $user->getSpecialite() : '')) === $s ? 'selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?></select></div>
        <div><label for="ville">Ville</label><input type="text" id="ville" name="ville" value="<?= $v('ville', 'getVille') ?>" maxlength="100"></div>
      </div>
      <div class="row2">
        <div><label for="telephone">Téléphone</label><input type="tel" id="telephone" name="telephone" value="<?= $v('telephone', 'getTelephone') ?>" maxlength="20"></div>
        <div><label for="numero_ordre">N° d'ordre / diplôme</label><input type="text" id="numero_ordre" name="numero_ordre" value="<?= $v('numero_ordre', 'getNumeroOrdre') ?>" maxlength="50"></div>
      </div>
      <?php if ($editing): ?>
      <div class="row2">
        <div><label for="horaire_debut">Début de journée</label><input type="time" id="horaire_debut" name="horaire_debut" value="<?= e($_POST['horaire_debut'] ?? substr((string) $user->getHoraireDebut(), 0, 5)) ?>"></div>
        <div><label for="horaire_fin">Fin de journée</label><input type="time" id="horaire_fin" name="horaire_fin" value="<?= e($_POST['horaire_fin'] ?? substr((string) $user->getHoraireFin(), 0, 5)) ?>"></div>
      </div>
      <?php endif; ?>
      <label for="description">Présentation</label><textarea id="description" name="description" rows="3" maxlength="500"><?= $v('description', 'getDescription') ?></textarea>
    </fieldset>

    <div class="form-actions">
      <button class="btn btn-primary" type="submit"><?= $editing ? 'Enregistrer' : 'Créer le compte' ?></button>
      <a class="btn btn-ghost" href="index.php?action=admin_users">Annuler</a>
    </div>
  </form>
</div>
