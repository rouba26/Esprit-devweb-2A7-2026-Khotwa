<div class="page-head">
    <h1>Plans de rétablissement</h1>
    <a class="btn" href="<?= url('plan-form') ?>">+ Nouveau plan</a>
</div>
<div class="card">
<?php if (!$plans): ?>
    <p class="muted">Aucun plan enregistré.</p>
<?php else: ?>
    <table>
        <tr><th>Titre</th><th>Début</th><th>Fin</th><th>Statut</th><th>Objectifs</th><th></th></tr>
        <?php foreach ($plans as $p): ?>
            <tr>
                <td><?= e($p['title']) ?></td>
                <td><?= e($p['start_date']) ?></td>
                <td><?= e($p['end_date'] ?? '—') ?></td>
                <td><span class="badge badge-<?= e($p['status']) ?>"><?= e(\App\Models\RecoveryPlan::STATUSES[$p['status']]) ?></span></td>
                <td><?= (int) $p['goals_count'] ?></td>
                <td class="actions">
                    <a class="btn btn-ghost" href="<?= url('plan-form', ['id' => $p['id']]) ?>">Modifier</a>
                    <form method="post" action="<?= url('plan-delete') ?>" data-confirm="Supprimer ce plan ?">
                        <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                        <button class="btn btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
</div>
