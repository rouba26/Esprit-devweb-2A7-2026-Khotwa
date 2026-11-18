<?php
/**
 * Main layout - includes all common blocks and injects the page content.
 * Variables (provided by Controller::render()):
 *   $title, $currentPage, $content, $extraScripts (optional)
 */
require_once __DIR__ . '/partials/head.php';
?>
<div class="app">
    <?php require_once __DIR__ . '/partials/sidebar.php'; ?>
    <main class="main-wrapper">
        <?php require_once __DIR__ . '/partials/header.php'; ?>
        <section class="content"><?= $content ?></section>
        <?php require_once __DIR__ . '/partials/footer.php'; ?>
    </main>
</div>
<?php require_once __DIR__ . '/partials/scripts.php'; ?>
