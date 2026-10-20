<?php

$routes = [

    'GET' => [

        '/' => ['HomeController', 'index'],

        '/login' => ['AuthController', 'loginForm'],

        '/logout' => ['AuthController', 'logout'],

        // =========================
        // MAHASISWA
        // =========================

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

        '/mahasiswa/edit' => [
            'MahasiswaController',
            'edit',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

        '/mahasiswa/delete' => [
            'MahasiswaController',
            'delete',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

        // =========================
        // PRODI
        // =========================

        '/prodi' => [
            'ProdiController',
            'index',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

        '/prodi/create' => [
            'ProdiController',
            'create',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

        '/prodi/edit' => [
            'ProdiController',
            'edit',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

        '/prodi/delete' => [
            'ProdiController',
            'delete',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

                // =========================
        // MATA KULIAH
        // =========================

        '/matakuliah' => [
            'MatakuliahController',
            'index',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

        '/matakuliah/create' => [
            'MatakuliahController',
            'create',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

        '/matakuliah/edit' => [
            'MatakuliahController',
            'edit',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

        '/matakuliah/delete' => [
            'MatakuliahController',
            'delete',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

    ],

    'POST' => [

        '/login' => ['AuthController', 'login'],

        // =========================
        // MAHASISWA
        // =========================

        '/mahasiswa/create' => [
            'MahasiswaController',
            'create',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

        '/mahasiswa/edit' => [
            'MahasiswaController',
            'edit',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

        // =========================
        // PRODI
        // =========================

        '/prodi/create' => [
            'ProdiController',
            'create',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

        '/prodi/edit' => [
            'ProdiController',
            'edit',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

        // =========================
        // MATA KULIAH
        // =========================

        '/matakuliah/create' => [
            'MatakuliahController',
            'create',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

        '/matakuliah/edit' => [
            'MatakuliahController',
            'edit',
            'middleware' => [
                \App\Core\Middleware\AuthMiddleware::class
            ]
        ],

    ],

];