<?php

class Assessment {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        return $this->pdo->query(
            "SELECT a.*, u.nom, u.prenom FROM assessments a
             LEFT JOIN users u ON a.user_id = u.id
             ORDER BY a.id DESC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $s = $this->pdo->prepare("SELECT * FROM assessments WHERE id=?");
        $s->execute([$id]);
        return $s->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUser($user_id) {
        $s = $this->pdo->prepare("SELECT * FROM assessments WHERE user_id=? ORDER BY id DESC");
        $s->execute([$user_id]);
        return $s->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($user_id, $date_passage, $score, $recommandation) {
        $s = $this->pdo->prepare("INSERT INTO assessments (user_id, date_passage, score, recommandation) VALUES (?, ?, ?, ?)");
        return $s->execute([$user_id, $date_passage, $score, $recommandation]);
    }

    public function update($id, $date_passage, $score, $recommandation) {
        $s = $this->pdo->prepare("UPDATE assessments SET date_passage=?, score=?, recommandation=? WHERE id=?");
        return $s->execute([$date_passage, $score, $recommandation, $id]);
    }

    public function delete($id) {
        $s = $this->pdo->prepare("DELETE FROM assessments WHERE id=?");
        return $s->execute([$id]);
    }
}
