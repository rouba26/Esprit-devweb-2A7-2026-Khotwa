<?php $pageTitle = 'Accès refusé'; $currentPage = ''; ?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once FRONT_INC . 'head.php'; ?></head>
<body id="top" class="">
<?php require_once FRONT_INC . 'spinner.php'; require_once FRONT_INC . 'navbar.php'; ?>
<main class="page narrow"><div class="panel center">
  <h1>403 · Accès refusé</h1>
  <p>Tu n'as pas les droits nécessaires pour voir cette page.</p>
  <a class="btn btn-primary" href="index.php?action=home">Retour à l'accueil</a>
</div></main>
<?php require_once FRONT_INC . 'footer.php'; require_once FRONT_INC . 'scripts.php'; ?>
</body>
</html>
