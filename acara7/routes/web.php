<?php

$routes = [

    'GET' => [

        '/' => ['HomeController', 'index'],

        '/login' => ['AuthController', 'loginForm'],

        '/logout' => ['AuthController', 'logout'],

        '/mahasiswa' => [
            'MahasiswaController',
            'index',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

        '/mahasiswa/create' => [
            'MahasiswaController',
            'create',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

    ],

    'POST' => [

        '/login' => ['AuthController', 'login'],

    ],

];