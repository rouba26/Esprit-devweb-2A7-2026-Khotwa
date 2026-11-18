<?php

class Question {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        return $this->pdo->query(
            "SELECT q.*, a.id AS assessment_ref FROM questions q
             LEFT JOIN assessments a ON q.assessment_id = a.id
             ORDER BY q.assessment_id, q.ordre"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $s = $this->pdo->prepare("SELECT * FROM questions WHERE id=?");
        $s->execute([$id]);
        return $s->fetch(PDO::FETCH_ASSOC);
    }

    public function getByAssessment($assessment_id) {
        $s = $this->pdo->prepare("SELECT * FROM questions WHERE assessment_id=? ORDER BY ordre");
        $s->execute([$assessment_id]);
        return $s->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($assessment_id, $texte, $type, $ordre) {
        $s = $this->pdo->prepare("INSERT INTO questions (assessment_id, texte, type, ordre) VALUES (?, ?, ?, ?)");
        return $s->execute([$assessment_id, $texte, $type, $ordre]);
    }

    public function update($id, $texte, $type, $ordre) {
        $s = $this->pdo->prepare("UPDATE questions SET texte=?, type=?, ordre=? WHERE id=?");
        return $s->execute([$texte, $type, $ordre, $id]);
    }

    public function delete($id) {
        $s = $this->pdo->prepare("DELETE FROM questions WHERE id=?");
        return $s->execute([$id]);
    }
}
