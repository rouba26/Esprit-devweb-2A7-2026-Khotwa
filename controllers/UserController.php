<?php

session_start();

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../models/User.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit;
}

$pdo    = Database::getInstance();
$model  = new User($pdo);
$action = $_POST['_action'] ?? '';

switch ($action) {
    case 'store':
        $model->create(
            trim($_POST['nom']          ?? ''),
            trim($_POST['prenom']       ?? ''),
            trim($_POST['email']        ?? ''),
            trim($_POST['mot_de_passe'] ?? ''),
            trim($_POST['role']         ?? 'user')
        );
        break;

    case 'update':
        $model->update(
            (int)($_POST['id']    ?? 0),
            trim($_POST['nom']    ?? ''),
            trim($_POST['prenom'] ?? ''),
            trim($_POST['email']  ?? ''),
            trim($_POST['role']   ?? 'user')
        );
        break;

    case 'delete':
        $model->delete((int)($_POST['id'] ?? 0));
        break;
}

header('Location: ../views/dashboard/users.php');
exit;
