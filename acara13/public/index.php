<?php

// Mulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ==============================
// CORE
// ==============================

require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Database.php';


// ==============================
// MIDDLEWARE
// ==============================

require_once __DIR__ . '/../app/Core/Middleware/AuthMiddleware.php';


// ==============================
// REPOSITORIES
// ==============================

require_once __DIR__ . '/../app/Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../app/Repositories/ProdiRepository.php';


// ==============================
// SERVICES
// ==============================

require_once __DIR__ . '/../app/Service/MahasiswaService.php';


// ==============================
// CONTROLLERS
// ==============================

require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/ProdiController.php';
require_once __DIR__ . '/../app/Controllers/MatakuliahController.php';


// ==============================
// ROUTES
// ==============================

require_once __DIR__ . '/../routes/web.php';


// ==============================
// AMBIL URI
// ==============================

$uri = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);


// ==============================
// BASE PATH ACARA 13
// ==============================

$base = '/workshop_si_web_server/acara13/public';


// Hilangkan base path
if (str_starts_with($uri, $base)) {

    $uri = substr(
        $uri,
        strlen($base)
    );
}


// Jika URI kosong
$uri = $uri ?: '/';


// ==============================
// HTTP METHOD
// ==============================

$method = $_SERVER['REQUEST_METHOD'];


// ==============================
// ROUTER
// ==============================

$router = new Router($routes);


// Jalankan route
$router->dispatch(
    $method,
    $uri
);