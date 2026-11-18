<?php

session_start();

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../models/User.php';

$pdo  = Database::getInstance();
$user = new User($pdo);

$action = $_GET['action'] ?? '';

if ($action === 'logout') {
    session_destroy();
    header('Location: ../views/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    $row = $user->login($email, $password);

    if ($row) {
        $_SESSION['user_id']   = $row['id'];
        $_SESSION['user_name'] = $row['prenom'] . ' ' . $row['nom'];
        $_SESSION['user_role'] = $row['role'];
        header('Location: ../views/dashboard/index.php');
        exit;
    }

    header('Location: ../views/login.php?error=1');
    exit;
}

header('Location: ../views/login.php');
exit;
