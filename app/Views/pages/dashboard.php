<h1>Tableau de bord</h1>
<div class="stats">
    <div class="card stat"><b><?= $stats['plans'] ?></b><span>Plans</span></div>
    <div class="card stat"><b><?= $stats['plans_active'] ?></b><span>Plans actifs</span></div>
    <div class="card stat"><b><?= $stats['goals'] ?></b><span>Objectifs</span></div>
    <div class="card stat"><b><?= $stats['goals_done'] ?></b><span>Objectifs atteints</span></div>
</div>
<div class="card">
    <h2>Derniers plans</h2>
    <?php if (!$recent): ?>
        <p class="muted">Aucun plan pour le moment. <a href="<?= url('plan-form') ?>">Créer un plan</a></p>
    <?php else: ?>
        <table>
            <tr><th>Titre</th><th>Statut</th><th>Objectifs</th></tr>
            <?php foreach ($recent as $p): ?>
                <tr>
                    <td><a href="<?= url('plan-form', ['id' => $p['id']]) ?>"><?= e($p['title']) ?></a></td>
                    <td><span class="badge badge-<?= e($p['status']) ?>"><?= e(\App\Models\RecoveryPlan::STATUSES[$p['status']]) ?></span></td>
                    <td><?= (int) $p['goals_count'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
