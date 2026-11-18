
<?php

class AuthController
{
    private User $model;

    public function __construct()
    {
        $this->model = new User();
    }

    // Proses registrasi
    public function register(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (
            !is_array($data) ||
            empty($data['username']) ||
            empty($data['email']) ||
            empty($data['password'])
        ) {
            Response::json(
                400,
                false,
                'Username, email, dan password wajib diisi'
            );
        }

        $data['password'] = password_hash(
            $data['password'],
            PASSWORD_DEFAULT
        );

        try {
            $id = $this->model->create($data);

            Response::json(
                201,
                true,
                'Registrasi berhasil',
                ['id' => $id]
            );
        } catch (InvalidArgumentException $e) {
            Response::json(
                422,
                false,
                $e->getMessage()
            );
        }
    }

    // Proses login
    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        // Memeriksa input
        if (
            !is_array($data) ||
            empty($data['username']) ||
            !isset($data['password']) ||
            $data['password'] === ''
        ) {
            Response::json(
                400,
                false,
                'Username dan password wajib diisi'
            );
        }

        // Mencari pengguna berdasarkan username
        $user = $this->model->findByUsername($data['username']);

        // Memeriksa username dan password
        if (
            !$user ||
            !password_verify($data['password'], $user['password'])
        ) {
            Response::json(
                401,
                false,
                'Username atau password salah'
            );
        }

        // Membuat JWT Token
        $token = Auth::generateToken([
            'id' => $user['id'],
            'role' => $user['role']
        ]);

        // Mengirim respons login berhasil
        Response::json(
            200,
            true,
            'Login berhasil',
            [
                'token' => $token,
                'role' => $user['role']
            ]
        );
    }
}
