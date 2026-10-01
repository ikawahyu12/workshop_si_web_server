<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Sistem Informasi Akademik</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 40px;
        }

        .login-container {
            width: 350px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        .description {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
        }

        form {
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #0d6efd;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0b5ed7;
        }

        .alert {
            padding: 10px;
            margin: 15px 0;
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <div class="login-container">

        <h1>Login</h1>

        <p class="description">
            Sistem Informasi Akademik
        </p>

        <?php if (!empty($_SESSION['flash_message'])): ?>

            <div class="alert">
                <?= $_SESSION['flash_message'] ?>
            </div>

            <?php unset($_SESSION['flash_message']); ?>

        <?php endif; ?>

        <form method="POST" action="/workshop_si_web_server/acara6/public/login">

            <label for="username">Username</label>
            <input
                type="text"
                id="username"
                name="username"
                placeholder="Masukkan username"
                required
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Masukkan password"
                required
            >

            <button type="submit">Login</button>

        </form>

    </div>

</body>
</html>