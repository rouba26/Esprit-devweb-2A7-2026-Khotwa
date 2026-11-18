<?php
require_once 'includes/auth_check.php';
require_once '../../database.php';

$pdo = Database::getInstance();
$userCount       = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$assessmentCount = $pdo->query("SELECT COUNT(*) FROM assessments")->fetchColumn();
$questionCount   = $pdo->query("SELECT COUNT(*) FROM questions")->fetchColumn();

$pageTitle = 'Dashboard';
require_once 'includes/dash_head.php';
require_once 'includes/sidebar.php';
?>
<div class="main-content">
    <div class="topbar">
        <span class="fw-semibold">👋 Bienvenue, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
        <span class="text-muted small"><?= date('l, d F Y') ?></span>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="fs-1 text-primary mb-2"><i class="bi bi-people-fill"></i></div>
                <h2 class="fw-bold"><?= $userCount ?></h2>
                <p class="text-muted mb-0">Utilisateurs</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="fs-1 text-success mb-2"><i class="bi bi-clipboard-data-fill"></i></div>
                <h2 class="fw-bold"><?= $assessmentCount ?></h2>
                <p class="text-muted mb-0">Évaluations</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="fs-1 text-info mb-2"><i class="bi bi-question-diamond-fill"></i></div>
                <h2 class="fw-bold"><?= $questionCount ?></h2>
                <p class="text-muted mb-0">Questions</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Accès rapide</h5>
            <div class="d-flex gap-2 flex-wrap">
                <a href="users.php"       class="btn btn-outline-primary"><i class="bi bi-people me-1"></i>Utilisateurs</a>
                <a href="assessments.php" class="btn btn-outline-success"><i class="bi bi-clipboard-data me-1"></i>Évaluations</a>
                <a href="questions.php"   class="btn btn-outline-info"><i class="bi bi-question-circle me-1"></i>Questions</a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
