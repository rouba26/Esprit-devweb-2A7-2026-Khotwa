<?php
namespace App\Controllers;

use App\Models\Goal;
use App\Models\RecoveryPlan;

class DashboardController extends Controller
{
    protected string $page  = 'dashboard';
    protected string $title = 'Tableau de bord | Khotwa';

    public function index(): void
    {
        $stats = [
            'plans'        => RecoveryPlan::count(),
            'plans_active' => RecoveryPlan::count('active'),
            'goals'        => Goal::count(),
            'goals_done'   => Goal::count('done'),
        ];
        $recent = array_slice(RecoveryPlan::all(), 0, 5);
        $this->render('dashboard', compact('stats', 'recent'));
    }

    public function notFound(): void
    {
        $this->title = 'Page introuvable | Khotwa';
        $this->render('404');
    }
}
