<?php

require_once __DIR__ . '/../routes/web.php';

require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Middleware/AuthMiddleware.php';

require_once __DIR__ . '/../app/Core/Model.php';
require_once __DIR__ . '/../app/Models/MahasiswaModel.php';

require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


// Base path project Acara 7
$base = '/workshop_si_web_server/acara7/public';


// Hilangkan base path
if (str_starts_with($uri, $base)) {

    $uri = substr($uri, strlen($base));

}


// Jika kosong, anggap sebagai /
$uri = $uri ?: '/';


// Ambil HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Buat Router
$router = new Router($routes);


// Jalankan route
$router->dispatch($method, $uri);