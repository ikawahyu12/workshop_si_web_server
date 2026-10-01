<?php

$routes = [
    'GET' => [
        '/' => ['HomeController', 'index'],
        '/mahasiswa' => ['MahasiswaController', 'index'],
        '/mahasiswa/create' => ['MahasiswaController', 'create'],
        '/login' => ['AuthController', 'loginForm'],
    ],

    'POST' => [
        '/mahasiswa' => ['MahasiswaController', 'store'],
        '/login' => ['AuthController', 'login'],
    ],
];