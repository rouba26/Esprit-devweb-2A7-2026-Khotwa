<?php

session_start();

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../models/Question.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit;
}

$pdo    = Database::getInstance();
$model  = new Question($pdo);
$action = $_POST['_action'] ?? '';

switch ($action) {
    case 'store':
        $model->create(
            (int)($_POST['assessment_id'] ?? 0),
            trim($_POST['texte']          ?? ''),
            trim($_POST['type']           ?? ''),
            (int)($_POST['ordre']         ?? 0)
        );
        break;

    case 'update':
        $model->update(
            (int)($_POST['id']    ?? 0),
            trim($_POST['texte']  ?? ''),
            trim($_POST['type']   ?? ''),
            (int)($_POST['ordre'] ?? 0)
        );
        break;

    case 'delete':
        $model->delete((int)($_POST['id'] ?? 0));
        break;
}

header('Location: ../views/dashboard/questions.php');
exit;
