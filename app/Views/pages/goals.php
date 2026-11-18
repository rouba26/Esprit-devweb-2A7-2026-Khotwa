<div class="page-head">
    <h1>Objectifs personnels</h1>
    <a class="btn" href="<?= url('goal-form') ?>">+ Nouvel objectif</a>
</div>
<div class="card">
<?php if (!$goals): ?>
    <p class="muted">Aucun objectif enregistré.</p>
<?php else: ?>
    <table>
        <tr><th>Titre</th><th>Catégorie</th><th>Date cible</th><th>Statut</th><th></th></tr>
        <?php foreach ($goals as $g): ?>
            <tr>
                <td><?= e($g['title']) ?></td>
                <td><?= e(\App\Models\Goal::CATEGORIES[$g['category']]) ?></td>
                <td><?= e($g['target_date'] ?? '—') ?></td>
                <td><span class="badge badge-<?= e($g['status']) ?>"><?= e(\App\Models\Goal::STATUSES[$g['status']]) ?></span></td>
                <td class="actions">
                    <a class="btn btn-ghost" href="<?= url('goal-form', ['id' => $g['id']]) ?>">Modifier</a>
                    <form method="post" action="<?= url('goal-delete') ?>" data-confirm="Supprimer cet objectif ?">
                        <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $g['id'] ?>">
                        <button class="btn btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
</div>
