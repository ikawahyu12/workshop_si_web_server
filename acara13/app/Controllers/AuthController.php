<?php

class AuthController
{
    public function loginForm()
    {
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === 'admin' && $password === 'admin123') {

            $_SESSION['logged_in'] = true;
            $_SESSION['user_name'] = 'Admin';
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Selamat datang! Anda berhasil login.'
            ];

            header('Location: /workshop_si_web_server/acara13/public/mahasiswa');
            exit;

        } else {

            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Username atau password salah.'
            ];

            header('Location: /workshop_si_web_server/acara13/public/login');
            exit;
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Anda telah logout.'
        ];

        header('Location: /workshop_si_web_server/acara13/public/login');
        exit;
    }
}