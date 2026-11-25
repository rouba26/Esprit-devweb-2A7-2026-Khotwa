<?php $pageTitle = "Journal d'activité"; $currentPage = 'logs'; ?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once BACK_INC . 'head.php'; ?></head>
<body>
<?php require_once BACK_INC . 'sidebar.php'; ?>
<div class="card panel">
  <form class="filters" method="get" action="index.php">
    <input type="hidden" name="action" value="admin_logs">
    <select name="type"><option value="">Toutes les actions</option>
      <?php foreach ($types as $t): ?><option <?= $action === $t ? 'selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?></select>
    <button class="btn btn-primary" type="submit">Filtrer</button>
    <span class="hint"><?= (int) $total ?> entrée(s)</span>
  </form>
  <div class="table-wrap tbl-wrap"><table class="tbl">
    <thead><tr><th>Date</th><th>Utilisateur</th><th>Action</th><th>Détails</th><th>IP</th></tr></thead>
    <tbody>
    <?php if (!$logs): ?><tr><td colspan="5" class="empty">Aucune entrée.</td></tr><?php endif; ?>
    <?php foreach ($logs as $l):
      $cls = str_contains($l['action'], 'failed') || str_contains($l['action'], 'locked') || str_contains($l['action'], 'delete') ? 'badge-off' : (str_starts_with($l['action'], 'admin_') ? 'badge-wait' : 'badge-on'); ?>
      <tr><td><?= e($l['created_at']) ?></td><td><?= e($l['username'] ?? '—') ?></td>
        <td><span class="badge <?= $cls ?>"><?= e($l['action']) ?></span></td><td><?= e($l['details']) ?></td><td><?= e($l['ip']) ?></td></tr>
    <?php endforeach; ?>
    </tbody></table></div>
  <?php if ($pages > 1): ?><div class="pager">
    <?php if ($page > 1): ?><a href="<?= qs(['page' => $page - 1]) ?>">← Précédent</a><?php endif; ?>
    <span>Page <?= (int) $page ?> / <?= (int) $pages ?></span>
    <?php if ($page < $pages): ?><a href="<?= qs(['page' => $page + 1]) ?>">Suivant →</a><?php endif; ?>
  </div><?php endif; ?>
</div>
<?php require_once BACK_INC . 'footer.php'; require_once BACK_INC . 'scripts.php'; ?>
</body>
</html>
