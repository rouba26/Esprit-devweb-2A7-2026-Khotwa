<?php
require_once 'includes/auth_check.php';
require_once '../../database.php';
require_once '../../models/User.php';

$pdo   = Database::getInstance();
$model = new User($pdo);

$editId  = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$editRow = $editId ? $model->getById($editId) : null;
$users   = $model->getAll();

$pageTitle = 'Utilisateurs';
require_once 'includes/dash_head.php';
require_once 'includes/sidebar.php';
?>
<div class="main-content">
    <div class="topbar">
        <span class="fw-semibold">Gestion des Utilisateurs</span>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-lg me-1"></i>Ajouter
        </button>
    </div>

    <?php if ($editRow): ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-warning-subtle fw-semibold">Modifier l'utilisateur #<?= $editRow['id'] ?></div>
        <div class="card-body">
            <form action="../../controllers/UserController.php" method="POST">
                <input type="hidden" name="_action" value="update">
                <input type="hidden" name="id" value="<?= $editRow['id'] ?>">
                <div class="row g-3">
                    <div class="col-md-2"><input name="nom"    class="form-control" value="<?= htmlspecialchars($editRow['nom'])    ?>" placeholder="Nom"    required></div>
                    <div class="col-md-2"><input name="prenom" class="form-control" value="<?= htmlspecialchars($editRow['prenom']) ?>" placeholder="Prénom" required></div>
                    <div class="col-md-3"><input name="email"  class="form-control" value="<?= htmlspecialchars($editRow['email'])  ?>" placeholder="Email" type="email" required></div>
                    <div class="col-md-2">
                        <select name="role" class="form-select">
                            <option <?= $editRow['role']==='admin' ?'selected':'' ?>>admin</option>
                            <option <?= $editRow['role']==='user'  ?'selected':'' ?>>user</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-warning flex-fill">Enregistrer</button>
                        <a href="users.php" class="btn btn-secondary flex-fill">Annuler</a>
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
                    <tr><th>#</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Rôle</th><th>Actions</th></tr>
                </thead>
                <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Aucun utilisateur.</td></tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['nom']) ?></td>
                        <td><?= htmlspecialchars($u['prenom']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><span class="badge <?= $u['role']==='admin'?'bg-danger':'bg-secondary' ?>"><?= $u['role'] ?></span></td>
                        <td>
                            <a href="?edit=<?= $u['id'] ?>" class="btn btn-sm btn-outline-warning me-1"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="../../controllers/UserController.php" class="d-inline" onsubmit="return confirm('Supprimer ?')">
                                <input type="hidden" name="_action" value="delete">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
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
            <div class="modal-header"><h5 class="modal-title">Nouvel utilisateur</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="../../controllers/UserController.php" method="POST">
                <input type="hidden" name="_action" value="store">
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nom</label><input name="nom" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Prénom</label><input name="prenom" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Email</label><input name="email" type="email" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Mot de passe</label><input name="mot_de_passe" type="password" class="form-control" required></div>
                    <div class="mb-3">
                        <label class="form-label">Rôle</label>
                        <select name="role" class="form-select">
                            <option value="user">user</option>
                            <option value="admin">admin</option>
                        </select>
                    </div>
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
