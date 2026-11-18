<?php
namespace App\Controllers;

use App\Models\Goal;

class GoalController extends Controller
{
    protected string $page  = 'goals';
    protected string $title = 'Objectifs personnels | Khotwa';

    public function index(): void
    {
        $this->render('goals', ['goals' => Goal::all()]);
    }

    public function form(): void
    {
        $id   = (int) ($_GET['id'] ?? 0);
        $goal = $id ? Goal::find($id) : null;
        if ($id && !$goal) {
            flash('Objectif introuvable.');
            $this->redirect('goals');
        }
        $this->render('goal_form', ['goal' => $goal, 'errors' => []]);
    }

    public function save(): void
    {
        csrf_check();
        $id = (int) ($_POST['id'] ?? 0) ?: null;

        $d = [
            'title'       => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'category'    => $_POST['category'] ?? 'other',
            'target_date' => $_POST['target_date'] ?? '',
            'status'      => $_POST['status'] ?? 'todo',
        ];

        $errors = [];
        if ($d['title'] === '' || mb_strlen($d['title']) > 150) {
            $errors['title'] = 'Le titre est obligatoire (150 caractères max).';
        }
        if (!isset(Goal::CATEGORIES[$d['category']])) {
            $errors['category'] = 'Catégorie invalide.';
        }
        if (!isset(Goal::STATUSES[$d['status']])) {
            $errors['status'] = 'Statut invalide.';
        }
        if ($d['target_date'] !== '' && !$this->validDate($d['target_date'])) {
            $errors['target_date'] = 'Date cible invalide.';
        }

        if ($errors) {
            $this->render('goal_form', ['goal' => $d + ['id' => $id], 'errors' => $errors]);
            return;
        }

        $d['target_date'] = $d['target_date'] ?: null;
        $d['description'] = $d['description'] ?: null;
        Goal::save($id, $d);
        flash($id ? 'Objectif mis à jour.' : 'Objectif créé.');
        $this->redirect('goals');
    }

    public function delete(): void
    {
        csrf_check();
        Goal::delete((int) ($_POST['id'] ?? 0));
        flash('Objectif supprimé.');
        $this->redirect('goals');
    }
}
