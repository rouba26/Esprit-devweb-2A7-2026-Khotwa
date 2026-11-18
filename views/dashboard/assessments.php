<?php
require_once 'includes/auth_check.php';
require_once '../../database.php';
require_once '../../models/Assessment.php';

$pdo   = Database::getInstance();
$model = new Assessment($pdo);

$editId  = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$editRow = $editId ? $model->getById($editId) : null;
$rows    = $model->getAll();
$users   = $pdo->query("SELECT id, nom, prenom FROM users ORDER BY nom")->fetchAll();

$pageTitle = 'Évaluations';
require_once 'includes/dash_head.php';
require_once 'includes/sidebar.php';
?>
<div class="main-content">
    <div class="topbar">
        <span class="fw-semibold">Gestion des Évaluations</span>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-lg me-1"></i>Ajouter
        </button>
    </div>

    <?php if ($editRow): ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-warning-subtle fw-semibold">Modifier l'évaluation #<?= $editRow['id'] ?></div>
        <div class="card-body">
            <form action="../../controllers/AssessmentController.php" method="POST">
                <input type="hidden" name="_action" value="update">
                <input type="hidden" name="id" value="<?= $editRow['id'] ?>">
                <div class="row g-3">
                    <div class="col-md-3"><input name="date_passage" type="date" class="form-control" value="<?= $editRow['date_passage'] ?>" required></div>
                    <div class="col-md-2"><input name="score" type="number" class="form-control" value="<?= $editRow['score'] ?>" placeholder="Score"></div>
                    <div class="col-md-4"><textarea name="recommandation" class="form-control" placeholder="Recommandation"><?= htmlspecialchars($editRow['recommandation'] ?? '') ?></textarea></div>
                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-warning flex-fill">Enregistrer</button>
                        <a href="assessments.php" class="btn btn-secondary flex-fill">Annuler</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Utilisateur</th><th>Date</th><th>Score</th><th>Recommandation</th><th>Actions</th></tr>
                </thead>
                <tbody>
                <?php if (empty($rows)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Aucune évaluation.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $a): ?>
                    <tr>
                        <td><?= $a['id'] ?></td>
                        <td><?= htmlspecialchars($a['prenom'] . ' ' . $a['nom']) ?></td>
                        <td><?= $a['date_passage'] ?? '—' ?></td>
                        <td><?= $a['score'] ?? '—' ?></td>
                        <td><?= htmlspecialchars(mb_strimwidth($a['recommandation'] ?? '', 0, 50, '…')) ?></td>
                        <td>
                            <a href="?edit=<?= $a['id'] ?>" class="btn btn-sm btn-outline-warning me-1"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="../../controllers/AssessmentController.php" class="d-inline" onsubmit="return confirm('Supprimer ?')">
                                <input type="hidden" name="_action" value="delete">
                                <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ajout -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Nouvelle évaluation</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="../../controllers/AssessmentController.php" method="POST">
                <input type="hidden" name="_action" value="store">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Utilisateur</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">— Sélectionner —</option>
                            <?php foreach ($users as $u): ?>
                            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Date de passage</label><input name="date_passage" type="date" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">Score</label><input name="score" type="number" class="form-control" value="0"></div>
                    <div class="mb-3"><label class="form-label">Recommandation</label><textarea name="recommandation" class="form-control" rows="3"></textarea></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
