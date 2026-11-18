<?php $currentPage = $currentPage ?? 'dashboard'; ?>
<aside class="sidebar" id="sidebar">
    <a class="brand" href="<?= url('dashboard') ?>">Khotwa<span>Recovery Journey</span></a>
    <nav>
        <a class="<?= isActive('dashboard', $currentPage) ?>" href="<?= url('dashboard') ?>">Tableau de bord</a>
        <a class="<?= isActive('plans', $currentPage) ?>" href="<?= url('plans') ?>">Plans de rétablissement</a>
        <a class="<?= isActive('goals', $currentPage) ?>" href="<?= url('goals') ?>">Objectifs personnels</a>
    </nav>
</aside>
