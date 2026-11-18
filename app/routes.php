<?php
use App\Controllers\DashboardController;
use App\Controllers\RecoveryPlanController;
use App\Controllers\GoalController;

// page key => [Controller, method]
return [
    'dashboard'   => [DashboardController::class, 'index'],
    '404'         => [DashboardController::class, 'notFound'],

    'plans'       => [RecoveryPlanController::class, 'index'],
    'plan-form'   => [RecoveryPlanController::class, 'form'],
    'plan-save'   => [RecoveryPlanController::class, 'save'],
    'plan-delete' => [RecoveryPlanController::class, 'delete'],

    'goals'       => [GoalController::class, 'index'],
    'goal-form'   => [GoalController::class, 'form'],
    'goal-save'   => [GoalController::class, 'save'],
    'goal-delete' => [GoalController::class, 'delete'],
];
