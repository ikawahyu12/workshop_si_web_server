
<?php

class AuthController
{
    private string $baseUrl = '/workshop_si_web_server/acara14/public';

    public function loginForm(): void
    {
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_name'] = 'Admin';
            $_SESSION['flash_message'] = 'Selamat datang, Admin';

            header('Location: ' . $this->baseUrl . '/');
            exit;
        }

        $_SESSION['flash_message'] = 'Username atau password salah';

        header('Location: ' . $this->baseUrl . '/login');
        exit;
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();

        header('Location: ' . $this->baseUrl . '/login');
        exit;
    }
}
