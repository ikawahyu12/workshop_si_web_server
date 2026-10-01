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

    <title>Login</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        form {
            width: 300px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
        }

        .alert {
            padding: 10px;
            margin: 15px 0;
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
            border-radius: 5px;
            width: 300px;
            box-sizing: border-box;
        }
    </style>
</head>

<body>

    <h1>Login</h1>

    <?php if (!empty($_SESSION['flash_message'])): ?>

        <div class="alert">
            <?= $_SESSION['flash_message'] ?>
        </div>

        <?php unset($_SESSION['flash_message']); ?>

    <?php endif; ?>

    <form method="POST" action="/workshop_si_web_server/acara6/public/login">

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>

    </form>

</body>
</html>