<?php $current = basename($_SERVER['PHP_SELF']); ?>
<div class="sidebar d-flex flex-column">
    <div class="brand"><i class="bi bi-layers-half"></i> Khotwa</div>
    <nav class="nav flex-column mt-3">
        <a href="index.php"       class="nav-link <?= $current==='index.php'       ? 'active':'' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="users.php"       class="nav-link <?= $current==='users.php'       ? 'active':'' ?>"><i class="bi bi-people"></i> Users</a>
        <a href="assessments.php" class="nav-link <?= $current==='assessments.php' ? 'active':'' ?>"><i class="bi bi-clipboard-data"></i> Assessments</a>
        <a href="questions.php"   class="nav-link <?= $current==='questions.php'   ? 'active':'' ?>"><i class="bi bi-question-circle"></i> Questions</a>
        <hr class="border-secondary mx-3">
        <a href="../../controllers/AuthController.php?action=logout" class="nav-link text-danger"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </nav>
</div>
