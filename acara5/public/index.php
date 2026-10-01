<?php

require_once __DIR__ . '/../routes/web.php';

require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


// Base path project
$base = '/workshop_si_web_server/acara5/public';


// Hilangkan base path
if (str_starts_with($uri, $base)) {

    $uri = substr($uri, strlen($base));

}


// Jika kosong, anggap sebagai /
$uri = $uri ?: '/';


// Ambil HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Cek URL parameter /mahasiswa/{id}
$prefix = '/mahasiswa/';

if ($method === 'GET' && str_starts_with($uri, $prefix)) {
    $id = substr($uri, strlen($prefix));

    if (ctype_digit($id)) {
        $controller = new MahasiswaController();
        $controller->show((int) $id);
        exit;
    }
}

// Cek apakah route tersedia
if (isset($routes[$method][$uri])) {

    [$controllerName, $action] = $routes[$method][$uri];


    if ($controllerName === 'HomeController') {

        $controller = new HomeController();

    } elseif ($controllerName === 'MahasiswaController') {

        $controller = new MahasiswaController();

    }


    $controller->$action();

} else {

    http_response_code(404);

    echo "<h1>404 - Halaman Tidak Ditemukan</h1>";

}