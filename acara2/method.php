<?php
$method = $_SERVER['REQUEST_METHOD'];
?>

<h2>Deteksi HTTP Method</h2>

<?php if ($method === 'GET'): ?>

    <p>Request yang diterima menggunakan method GET.</p>

<?php elseif ($method === 'POST'): ?>

    <p>Request yang diterima menggunakan method POST.</p>

<?php else: ?>

    <p>Method tidak dikenali.</p>

<?php endif; ?>

<hr>

<h2>Test POST</h2>

<form action="index.php" method="POST">

    <label for="nama">Nama:</label>
    <input type="text" id="nama" name="nama">

    <button type="submit">Kirim POST</button>

</form>