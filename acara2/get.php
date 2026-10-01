<h2>Form GET</h2>

<form action="" method="GET">
    <label>Cari Mahasiswa:</label>
    <input type="text" name="keyword">

    <button type="submit">Cari</button>
</form>

<?php
$keyword = $_GET['keyword'] ?? '';

if ($keyword !== '') {
    echo "<p>Anda mencari: " . htmlspecialchars($keyword) . "</p>";
}
?>