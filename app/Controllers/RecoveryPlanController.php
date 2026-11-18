<?php
namespace App\Controllers;

use App\Models\Goal;
use App\Models\PlanGoal;
use App\Models\RecoveryPlan;

class RecoveryPlanController extends Controller
{
    protected string $page  = 'plans';
    protected string $title = 'Plans de rétablissement | Khotwa';

    public function index(): void
    {
        $this->render('plans', ['plans' => RecoveryPlan::all()]);
    }

    public function form(): void
    {
        $id   = (int) ($_GET['id'] ?? 0);
        $plan = $id ? RecoveryPlan::find($id) : null;
        if ($id && !$plan) {
            flash('Plan introuvable.');
            $this->redirect('plans');
        }
        $this->renderForm($plan, $id ? PlanGoal::goalIdsOf($id) : [], []);
    }

    public function save(): void
    {
        csrf_check();
        $id = (int) ($_POST['id'] ?? 0) ?: null;

        $d = [
            'title'       => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'start_date'  => $_POST['start_date'] ?? '',
            'end_date'    => $_POST['end_date'] ?? '',
            'status'      => $_POST['status'] ?? 'active',
        ];
        $goalIds = array_values(array_intersect(array_map('intval', (array) ($_POST['goal_ids'] ?? [])), Goal::ids()));

        $errors = [];
        if ($d['title'] === '' || mb_strlen($d['title']) > 150) {
            $errors['title'] = 'Le titre est obligatoire (150 caractères max).';
        }
        if (!isset(RecoveryPlan::STATUSES[$d['status']])) {
            $errors['status'] = 'Statut invalide.';
        }
        if (!$this->validDate($d['start_date'])) {
            $errors['start_date'] = 'Date de début invalide.';
        }
        if ($d['end_date'] !== '') {
            if (!$this->validDate($d['end_date'])) {
                $errors['end_date'] = 'Date de fin invalide.';
            } elseif (!isset($errors['start_date']) && $d['end_date'] < $d['start_date']) {
                $errors['end_date'] = 'La date de fin doit suivre la date de début.';
            }
        }

        if ($errors) {
            $this->renderForm($d + ['id' => $id], $goalIds, $errors);
            return;
        }

        $d['end_date'] = $d['end_date'] ?: null;
        $d['description'] = $d['description'] ?: null;
        $planId = RecoveryPlan::save($id, $d);
        PlanGoal::sync($planId, $goalIds);
        flash($id ? 'Plan mis à jour.' : 'Plan créé.');
        $this->redirect('plans');
    }

    public function delete(): void
    {
        csrf_check();
        RecoveryPlan::delete((int) ($_POST['id'] ?? 0));
        flash('Plan supprimé.');
        $this->redirect('plans');
    }

    private function renderForm(?array $plan, array $selected, array $errors): void
    {
        $this->render('plan_form', [
            'plan' => $plan, 'goals' => Goal::all(), 'selected' => $selected, 'errors' => $errors,
        ]);
    }
}
