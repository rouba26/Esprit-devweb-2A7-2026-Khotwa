<?php
declare(strict_types=1);
session_start();

// PSR-4 style autoloader: App\Controllers\X -> app/Controllers/X.php
spl_autoload_register(function (string $class): void {
    if (strncmp($class, 'App\\', 4) !== 0) {
        return;
    }
    $file = __DIR__ . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require __DIR__ . '/app/helpers.php';
$routes = require __DIR__ . '/app/routes.php';

$page = $_GET['page'] ?? 'dashboard';
if (!is_string($page) || !isset($routes[$page])) {
    http_response_code(404);
    [$class, $method] = $routes['404'];
} else {
    [$class, $method] = $routes[$page];
}
(new $class())->$method();
