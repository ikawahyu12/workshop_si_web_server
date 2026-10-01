<?php

class Router
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(string $method, string $uri): void
    {
        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
            return;
        }

        $route = $this->routes[$method][$uri];

        $controllerName = $route[0];
        $action = $route[1];

        // Jalankan middleware jika ada
        if (isset($route['middleware'])) {

            foreach ($route['middleware'] as $middleware) {

                $middlewareInstance = new $middleware();
                $middlewareInstance->handle();

            }
        }

        // Buat controller
        if ($controllerName === 'HomeController') {

            $controller = new HomeController();

        } elseif ($controllerName === 'MahasiswaController') {

            $controller = new MahasiswaController();

        } elseif ($controllerName === 'AuthController') {

            $controller = new AuthController();

        } elseif ($controllerName === 'ProdiController') {

            $controller = new ProdiController();
        
        } elseif ($controllerName === 'MatakuliahController') {
        $controller = new MatakuliahController();

        } else {

            http_response_code(404);
            echo "<h1>Controller Tidak Ditemukan</h1>";
            return;

        }

        // Jalankan method controller
        $controller->$action();
    }
}