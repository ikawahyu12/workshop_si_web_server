<?php

// Mengambil data pencarian dari GET
$keyword = $_GET['keyword'] ?? '';

// Mengambil data login dari POST
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>GET dan POST</title>
</head>
<body>

    <h1>Demo GET dan POST</h1>

    <hr>

    <h2>Form Pencarian - GET</h2>

    <form method="GET">
        <input
            type="text"
            name="keyword"
            placeholder="Masukkan kata pencarian"
            value="<?php echo htmlspecialchars($keyword); ?>"
        >

        <button type="submit">Cari</button>
    </form>

    <?php if ($keyword !== ''): ?>
        <p>
            Hasil pencarian:
            <strong><?php echo htmlspecialchars($keyword); ?></strong>
        </p>
    <?php endif; ?>

    <hr>

    <h2>Form Login - POST</h2>

    <form method="POST">

        <label>Username:</label>
        <br>

        <input
            type="text"
            name="username"
            placeholder="Masukkan username"
        >

        <br><br>

        <label>Password:</label>
        <br>

        <input
            type="password"
            name="password"
            placeholder="Masukkan password"
        >

        <br><br>

        <button type="submit">Login</button>

    </form>

    <?php if ($username !== ''): ?>

        <p>
            Username yang dikirim:
            <strong><?php echo htmlspecialchars($username); ?></strong>
        </p>

        <p>
            Data login dikirim menggunakan method
            <strong>POST</strong>.
        </p>

    <?php endif; ?>

</body>
</html>