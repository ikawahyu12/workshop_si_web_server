<?php

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    echo "<h2>Request menggunakan GET</h2>";
} elseif ($method === 'POST') {
    echo "<h2>Request menggunakan POST</h2>";
} else {
    echo "<h2>Request menggunakan method lain</h2>";
}
?>

<hr>

<form method="POST">
    <input type="text" name="nama" placeholder="Masukkan nama">
    <button type="submit">Kirim POST</button>
</form>