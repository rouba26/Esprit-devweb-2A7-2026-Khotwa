<?php $pageTitle = 'Page introuvable'; $currentPage = ''; ?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once FRONT_INC . 'head.php'; ?></head>
<body id="top" class="">
<?php require_once FRONT_INC . 'spinner.php'; require_once FRONT_INC . 'navbar.php'; ?>
<main class="page narrow"><div class="panel center">
  <h1>404 · Page introuvable</h1>
  <p>Cette page n'existe pas (ou pas encore).</p>
  <a class="btn btn-primary" href="index.php?action=home">Retour à l'accueil</a>
</div></main>
<?php require_once FRONT_INC . 'footer.php'; require_once FRONT_INC . 'scripts.php'; ?>
</body>
</html>
