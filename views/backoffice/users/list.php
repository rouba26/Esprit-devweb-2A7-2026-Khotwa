<?php $pageTitle = 'Utilisateurs'; $currentPage = 'users'; ?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once BACK_INC . 'head.php'; ?></head>
<body>
<?php require_once BACK_INC . 'sidebar.php'; ?>
<?php
$sortLink = function (string $col, string $label) use ($f): string {
    $dir = ($f['sort'] === $col && $f['dir'] === 'ASC') ? 'desc' : 'asc';
    $arrow = $f['sort'] === $col ? ($f['dir'] === 'ASC' ? ' ▲' : ' ▼') : '';
    return '<a href="' . qs(['sort' => $col, 'dir' => $dir, 'page' => 1]) . '">' . e($label) . $arrow . '</a>';
};
?>
<div class="card panel">
  <form class="filters" method="get" action="index.php">
    <input type="hidden" name="action" value="admin_users">
    <input type="search" name="q" value="<?= e($f['q']) ?>" placeholder="Rechercher un pseudo ou un e-mail…">
    <select name="role"><option value="">Tous les rôles</option>
      <?php foreach (['user', 'professional', 'admin'] as $r): ?><option value="<?= $r ?>" <?= $f['role'] === $r ? 'selected' : '' ?>><?= e(role_label($r)) ?></option><?php endforeach; ?></select>
    <select name="etat"><option value="">Tous les états</option>
      <option value="actif" <?= $f['etat'] === 'actif' ? 'selected' : '' ?>>Actifs</option>
      <option value="bloque" <?= $f['etat'] === 'bloque' ? 'selected' : '' ?>>Bloqués</option>
      <option value="attente" <?= $f['etat'] === 'attente' ? 'selected' : '' ?>>Pros en attente</option></select>
    <button class="btn btn-primary" type="submit">Filtrer</button>
    <a class="btn btn-ghost" href="index.php?action=admin_users">Réinitialiser</a>
    <span class="spacer"></span>
    <a class="btn btn-ghost" href="<?= qs(['action' => 'admin_export']) ?>">⬇ Export CSV</a>
    <a class="btn btn-primary" href="index.php?action=admin_user_create">+ Ajouter</a>
  </form>
  <p class="hint"><?= (int) $total ?> résultat(s)</p>
  <div class="table-wrap tbl-wrap"><table class="tbl">
    <thead><tr><th><?= $sortLink('id', 'ID') ?></th><th><?= $sortLink('username', 'Pseudo') ?></th><th><?= $sortLink('email', 'E-mail') ?></th>
      <th><?= $sortLink('role', 'Rôle') ?></th><th>État</th><th><?= $sortLink('derniere_connexion', 'Dernière connexion') ?></th><th><?= $sortLink('created_at', 'Inscrit le') ?></th><th>Actions</th></tr></thead>
    <tbody>
    <?php if (!$users): ?><tr><td colspan="8" class="empty">Aucun utilisateur ne correspond.</td></tr><?php endif; ?>
    <?php foreach ($users as $u): $self = $u->getId() === (int) $_SESSION['user_id']; ?>
      <tr>
        <td><?= $u->getId() ?></td>
        <td><img class="avatar sm" src="<?= e(avatar_url($u->getPhoto())) ?>" alt=""> <?= e($u->getUsername()) ?><?= $self ? ' <small>(toi)</small>' : '' ?></td>
        <td><?= e($u->getEmail()) ?></td>
        <td>
          <?php if ($self): ?><span class="badge badge-admin">Administrateur</span><?php else: ?>
          <form method="post" action="index.php?action=admin_user_role" class="inline">
            <?= csrf_field() ?><input type="hidden" name="id" value="<?= $u->getId() ?>">
            <select name="role" data-autosubmit><?php foreach (['user', 'professional', 'admin'] as $r): ?>
              <option value="<?= $r ?>" <?= $u->getRole() === $r ? 'selected' : '' ?>><?= e(role_label($r)) ?></option><?php endforeach; ?></select>
          </form><?php endif; ?>
        </td>
        <td><?php if (!$u->getActif()): ?><span class="badge badge-off">Bloqué</span>
            <?php elseif ($u->isPro() && !$u->getValide()): ?><span class="badge badge-wait">En attente</span>
            <?php else: ?><span class="badge badge-on">Actif</span><?php endif; ?></td>
        <td><?= e($u->getDerniereConnexion() ?? '—') ?></td>
        <td><?= e(substr($u->getCreatedAt(), 0, 10)) ?></td>
        <td class="actions">
          <a class="btn btn-sm" href="index.php?action=admin_user_edit&amp;id=<?= $u->getId() ?>">Modifier</a>
          <?php if (!$self): ?>
          <form method="post" action="index.php?action=admin_user_toggle" class="inline">
            <?= csrf_field() ?><input type="hidden" name="id" value="<?= $u->getId() ?>">
            <input type="hidden" name="q" value="<?= e($f['q']) ?>"><input type="hidden" name="role_f" value="<?= e($f['role']) ?>">
            <input type="hidden" name="etat" value="<?= e($f['etat']) ?>"><input type="hidden" name="page" value="<?= (int) $f['page'] ?>">
            <button class="btn btn-sm" type="submit"><?= $u->getActif() ? 'Bloquer' : 'Débloquer' ?></button>
          </form>
          <form method="post" action="index.php?action=admin_user_delete" class="inline" data-confirm="Supprimer définitivement <?= e($u->getUsername()) ?> ?">
            <?= csrf_field() ?><input type="hidden" name="id" value="<?= $u->getId() ?>">
            <button class="btn btn-sm btn-danger" type="submit">Supprimer</button>
          </form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody></table></div>
  <?php if ($pages > 1): ?>
  <div class="pager">
    <?php if ($f['page'] > 1): ?><a href="<?= qs(['page' => $f['page'] - 1]) ?>">← Précédent</a><?php endif; ?>
    <span>Page <?= (int) $f['page'] ?> / <?= (int) $pages ?></span>
    <?php if ($f['page'] < $pages): ?><a href="<?= qs(['page' => $f['page'] + 1]) ?>">Suivant →</a><?php endif; ?>
  </div>
  <?php endif; ?>
</div>
<?php require_once BACK_INC . 'footer.php'; require_once BACK_INC . 'scripts.php'; ?>
</body>
</html>
