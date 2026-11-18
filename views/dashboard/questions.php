<?php
require_once 'includes/auth_check.php';
require_once '../../database.php';
require_once '../../models/Question.php';

$pdo   = Database::getInstance();
$model = new Question($pdo);

$editId  = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$editRow = $editId ? $model->getById($editId) : null;
$rows    = $model->getAll();
$assessments = $pdo->query("SELECT id FROM assessments ORDER BY id")->fetchAll();

$pageTitle = 'Questions';
require_once 'includes/dash_head.php';
require_once 'includes/sidebar.php';
?>
<div class="main-content">
    <div class="topbar">
        <span class="fw-semibold">Gestion des Questions</span>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-lg me-1"></i>Ajouter
        </button>
    </div>

    <?php if ($editRow): ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-warning-subtle fw-semibold">Modifier la question #<?= $editRow['id'] ?></div>
        <div class="card-body">
            <form action="../../controllers/QuestionController.php" method="POST">
                <input type="hidden" name="_action" value="update">
                <input type="hidden" name="id" value="<?= $editRow['id'] ?>">
                <div class="row g-3">
                    <div class="col-md-5"><input name="texte" class="form-control" value="<?= htmlspecialchars($editRow['texte']) ?>" placeholder="Texte" required></div>
                    <div class="col-md-2"><input name="type"  class="form-control" value="<?= htmlspecialchars($editRow['type'])  ?>" placeholder="Type"  required></div>
                    <div class="col-md-2"><input name="ordre" type="number" class="form-control" value="<?= $editRow['ordre'] ?>" placeholder="Ordre" required></div>
                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-warning flex-fill">Enregistrer</button>
                        <a href="questions.php" class="btn btn-secondary flex-fill">Annuler</a>
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
                    <tr><th>#</th><th>Évaluation</th><th>Texte</th><th>Type</th><th>Ordre</th><th>Actions</th></tr>
                </thead>
                <tbody>
                <?php if (empty($rows)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Aucune question.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $q): ?>
                    <tr>
                        <td><?= $q['id'] ?></td>
                        <td>#<?= $q['assessment_id'] ?></td>
                        <td><?= htmlspecialchars(mb_strimwidth($q['texte'], 0, 60, '…')) ?></td>
                        <td><?= htmlspecialchars($q['type']) ?></td>
                        <td><?= $q['ordre'] ?></td>
                        <td>
                            <a href="?edit=<?= $q['id'] ?>" class="btn btn-sm btn-outline-warning me-1"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="../../controllers/QuestionController.php" class="d-inline" onsubmit="return confirm('Supprimer ?')">
                                <input type="hidden" name="_action" value="delete">
                                <input type="hidden" name="id" value="<?= $q['id'] ?>">
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
            <div class="modal-header"><h5 class="modal-title">Nouvelle question</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="../../controllers/QuestionController.php" method="POST">
                <input type="hidden" name="_action" value="store">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Évaluation (ID)</label>
                        <select name="assessment_id" class="form-select" required>
                            <option value="">— Sélectionner —</option>
                            <?php foreach ($assessments as $a): ?>
                            <option value="<?= $a['id'] ?>">Évaluation #<?= $a['id'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Texte</label><textarea name="texte" class="form-control" rows="3" required></textarea></div>
                    <div class="mb-3"><label class="form-label">Type</label><input name="type" class="form-control" placeholder="ex: qcm, vrai/faux" required></div>
                    <div class="mb-3"><label class="form-label">Ordre</label><input name="ordre" type="number" class="form-control" value="1" required></div>
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
