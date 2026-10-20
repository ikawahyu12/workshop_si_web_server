<?php

require_once __DIR__ . '/app/Models/Mahasiswa.php';

use App\Models\Mahasiswa;

$mahasiswa = new Mahasiswa(
    '12345',
    'Budi Santoso',
    'Teknik Informatika'
);

echo "Data awal:<br>";
echo "NIM: " . $mahasiswa->getNim() . "<br>";
echo "Nama: " . $mahasiswa->getNama() . "<br>";

echo "<hr>";

try {

    $mahasiswa->setNama('');

    echo "Nama berhasil diubah.";

} catch (Exception $e) {

    echo "Validasi nama: " . $e->getMessage();

}

echo "<br><br>";

try {

    $mahasiswa->setNim('ABC123');

    echo "NIM berhasil diubah.";

} catch (Exception $e) {

    echo "Validasi NIM: " . $e->getMessage();

}