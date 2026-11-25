<?php
 
define('FRONT_INC', dirname(__DIR__) . '/views/frontoffice/includes/');
define('BACK_INC',  dirname(__DIR__) . '/views/backoffice/includes/');

function e($v): string { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }

function redirect(string $action, array $params = []): void
{
    header('Location: index.php?action=' . $action . ($params ? '&' . http_build_query($params) : ''));
    exit;
}

function flash(string $type, string $msg): void { $_SESSION['flash'][] = ['type' => $type, 'msg' => $msg]; }

function render_flashes(): void
{
    $items = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    foreach ($items as $f) {
        echo '<div class="alert alert-' . e($f['type']) . '">' . e($f['msg']) . '</div>';
    }
}

function render_errors(array $errors): void
{
    if ($errors) {
        echo '<div class="alert alert-error">';
        foreach ($errors as $er) { echo '<div>' . e($er) . '</div>'; }
        echo '</div>';
    }
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(UserController::getCsrfToken()) . '">';
}

function old(string $key, $default = ''): string { return e($_POST[$key] ?? $default); }

 
function qs(array $override = []): string { return 'index.php?' . e(http_build_query(array_merge($_GET, $override))); }

function role_label(string $r): string { return ['user' => 'Utilisateur', 'professional' => 'Professionnel', 'admin' => 'Administrateur'][$r] ?? $r; }

function avatar_url(?string $photo): string { return $photo ? 'img/uploads/' . rawurlencode(basename($photo)) : 'img/avatar.svg'; }
