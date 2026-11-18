<?php $editing = !empty($goal['id']); $v = fn($k, $d = '') => e($goal[$k] ?? $d); ?>
<h1><?= $editing ? "Modifier l'objectif" : 'Nouvel objectif' ?></h1>
<form class="card form" method="post" action="<?= url('goal-save') ?>" novalidate>
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
        <label>Catégorie
            <select name="category">
                <?php foreach (\App\Models\Goal::CATEGORIES as $k => $label): ?>
                    <option value="<?= $k ?>" <?= ($goal['category'] ?? 'other') === $k ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Date cible
            <input type="date" name="target_date" value="<?= $v('target_date') ?>">
            <?php if (isset($errors['target_date'])): ?><small class="error"><?= e($errors['target_date']) ?></small><?php endif; ?>
        </label>
        <label>Statut
            <select name="status">
                <?php foreach (\App\Models\Goal::STATUSES as $k => $label): ?>
                    <option value="<?= $k ?>" <?= ($goal['status'] ?? 'todo') === $k ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <div class="actions">
        <button class="btn">Enregistrer</button>
        <a class="btn btn-ghost" href="<?= url('goals') ?>">Annuler</a>
    </div>
</form>
