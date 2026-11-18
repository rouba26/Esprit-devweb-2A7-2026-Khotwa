<?php

session_start();

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../models/Assessment.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit;
}

$pdo    = Database::getInstance();
$model  = new Assessment($pdo);
$action = $_POST['_action'] ?? '';

switch ($action) {
    case 'store':
        $model->create(
            (int)($_POST['user_id']        ?? 0),
            trim($_POST['date_passage']    ?? ''),
            (int)($_POST['score']          ?? 0),
            trim($_POST['recommandation']  ?? '')
        );
        break;

    case 'update':
        $model->update(
            (int)($_POST['id']             ?? 0),
            trim($_POST['date_passage']    ?? ''),
            (int)($_POST['score']          ?? 0),
            trim($_POST['recommandation']  ?? '')
        );
        break;

    case 'delete':
        $model->delete((int)($_POST['id'] ?? 0));
        break;
}

header('Location: ../views/dashboard/assessments.php');
exit;
