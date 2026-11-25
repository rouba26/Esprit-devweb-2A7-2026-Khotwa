<?php $pageTitle = 'Validation des professionnels'; $currentPage = 'pros'; ?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once BACK_INC . 'head.php'; ?></head>
<body>
<?php require_once BACK_INC . 'sidebar.php'; ?>
<p class="hint"><?= count($pending) ?> demande(s) en attente · <?= (int) $validated ?> professionnel(s) déjà validé(s).
Vérifie le numéro d'ordre / diplôme avant de valider : seuls les profils validés pourront apparaître dans l'annuaire (module M2).</p>
<?php if (!$pending): ?><div class="card panel empty">Aucune demande en attente 🎉</div><?php endif; ?>
<div class="card panels">
<?php foreach ($pending as $p): ?>
  <div class="card panel pro-card">
    <h2><?= e($p->getUsername()) ?> <small><?= e($p->getEmail()) ?></small></h2>
    <table class="info">
      <tr><th>Spécialité</th><td><?= e($p->getSpecialite()) ?></td></tr>
      <tr><th>Ville</th><td><?= e($p->getVille()) ?></td></tr>
      <tr><th>Téléphone</th><td><?= e($p->getTelephone()) ?></td></tr>
      <tr><th>N° d'ordre</th><td><b><?= e($p->getNumeroOrdre()) ?></b></td></tr>
      <tr><th>Présentation</th><td><?= nl2br(e($p->getDescription() ?? '—')) ?></td></tr>
      <tr><th>Demande du</th><td><?= e($p->getCreatedAt()) ?></td></tr>
    </table>
    <form method="post" action="index.php?action=admin_pro_decision" class="decision">
      <?= csrf_field() ?><input type="hidden" name="id" value="<?= $p->getId() ?>">
      <button class="btn btn-primary" name="decision" value="valider" type="submit">✓ Valider</button>
      <button class="btn btn-danger" name="decision" value="refuser" type="submit" data-confirm-btn="Refuser et supprimer cette demande ?">✕ Refuser</button>
    </form>
  </div>
<?php endforeach; ?>
</div>
<?php require_once BACK_INC . 'footer.php'; require_once BACK_INC . 'scripts.php'; ?>
</body>
</html>
