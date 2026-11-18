<?php
require_once __DIR__ . '/database.php';

$pdo  = Database::getInstance();
$hash = password_hash('admin123', PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
$stmt->execute(['Admin', 'Super', 'admin@khotwa.com', $hash, 'admin']);

echo "✅ Utilisateur créé ! Email: admin@khotwa.com | Mot de passe: admin123";
