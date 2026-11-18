<?php $editing = !empty($plan['id']); $v = fn($k, $d = '') => e($plan[$k] ?? $d); ?>
<h1><?= $editing ? 'Modifier le plan' : 'Nouveau plan' ?></h1>
<form class="card form" method="post" action="<?= url('plan-save') ?>" novalidate>
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $v('id', 0) ?>">

    <label>Titre *
        <input type="text" name="title" maxlength="150" value="<?= $v('title') ?>">
        <?php if (isset($errors['title'])): ?><small class="error"><?= e($errors['title']) ?></small><?php endif; ?>
    </label>
    <label>Description
        <textarea name="description" rows="3"><?= $v('description') ?></textarea>
    </label>
    <div class="row">
        <label>Date de début *
            <input type="date" name="start_date" value="<?= $v('start_date', date('Y-m-d')) ?>">
            <?php if (isset($errors['start_date'])): ?><small class="error"><?= e($errors['start_date']) ?></small><?php endif; ?>
        </label>
        <label>Date de fin
            <input type="date" name="end_date" value="<?= $v('end_date') ?>">
            <?php if (isset($errors['end_date'])): ?><small class="error"><?= e($errors['end_date']) ?></small><?php endif; ?>
        </label>
        <label>Statut
            <select name="status">
                <?php foreach (\App\Models\RecoveryPlan::STATUSES as $k => $label): ?>
                    <option value="<?= $k ?>" <?= ($plan['status'] ?? 'active') === $k ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <fieldset>
        <legend>Objectifs du plan</legend>
        <?php if (!$goals): ?>
            <p class="muted">Aucun objectif disponible. <a href="<?= url('goal-form') ?>">Créer un objectif</a></p>
        <?php endif; ?>
        <?php foreach ($goals as $g): ?>
            <label class="check">
                <input type="checkbox" name="goal_ids[]" value="<?= (int) $g['id'] ?>" <?= in_array((int) $g['id'], $selected, true) ? 'checked' : '' ?>>
                <?= e($g['title']) ?> <small class="muted">(<?= e(\App\Models\Goal::CATEGORIES[$g['category']]) ?>)</small>
            </label>
        <?php endforeach; ?>
    </fieldset>

    <div class="actions">
        <button class="btn">Enregistrer</button>
        <a class="btn btn-ghost" href="<?= url('plans') ?>">Annuler</a>
    </div>
</form>
