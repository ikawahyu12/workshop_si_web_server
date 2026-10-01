<?php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Hilangkan base path jika project di subfolder
$base = '/si-akademik/public';

if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base)) ?: '/';
}

$method = $_SERVER['REQUEST_METHOD'];

$uri = rtrim($uri, '/') ?: '/';

// Parameter URL /mahasiswa/{id}
$segments = explode('/', trim($uri, '/'));

if (
    $method === 'GET' &&
    count($segments) === 2 &&
    $segments[0] === 'mahasiswa' &&
    is_numeric($segments[1])
) {
    require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';

    $controller = new \App\Controllers\MahasiswaController();

    $controller->show($segments[1]);

    exit;
}

if (isset($routes[$method][$uri])) {
    [$controllerName, $action] = $routes[$method][$uri];

    require_once __DIR__ . "/../app/Controllers/{$controllerName}.php";

    $controllerClass = "App\\Controllers\\{$controllerName}";

    $controller = new $controllerClass();

    $controller->$action();
}

else {

    http_response_code(404);

    echo "404 - Halaman tidak ditemukan";
}

?>