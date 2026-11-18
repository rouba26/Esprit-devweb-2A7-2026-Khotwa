<?php

class User {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        return $this->pdo->query("SELECT id, nom, prenom, email, role FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $s = $this->pdo->prepare("SELECT id, nom, prenom, email, role FROM users WHERE id = ?");
        $s->execute([$id]);
        return $s->fetch(PDO::FETCH_ASSOC);
    }

    public function login($email, $password) {
        $s = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $s->execute([$email]);
        $user = $s->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            return $user;
        }
        return false;
    }

    public function create($nom, $prenom, $email, $mot_de_passe, $role) {
        $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $s = $this->pdo->prepare("INSERT INTO users (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
        return $s->execute([$nom, $prenom, $email, $hash, $role]);
    }

    public function update($id, $nom, $prenom, $email, $role) {
        $s = $this->pdo->prepare("UPDATE users SET nom=?, prenom=?, email=?, role=? WHERE id=?");
        return $s->execute([$nom, $prenom, $email, $role, $id]);
    }

    public function delete($id) {
        $s = $this->pdo->prepare("DELETE FROM users WHERE id=?");
        return $s->execute([$id]);
    }
}
