<?php $pageTitle = ucwords(str_replace("-", " ", $infoPage)); ?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= e($pageTitle) ?> — KHOTWA</title><link rel="stylesheet" href="css/home-template.css"></head><body id="top"><?php require FRONT_INC . 'public_nav.php'; ?>
<?php require __DIR__ . '/info/' . $infoPage . '.php'; ?>
<?php require FRONT_INC . 'public_footer.php'; ?><script src="js/home-template.js"></script></body></html>