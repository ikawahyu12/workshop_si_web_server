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

            header('Location: /workshop_si_web_server/acara6/public/');
            exit;

        } else {

            echo "<h3>Username atau password salah</h3>";
            echo "<a href='/workshop_si_web_server/acara6/public/login'>Kembali ke Login</a>";
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();

        header('Location: /workshop_si_web_server/acara6/public/login');
        exit;
    }
}