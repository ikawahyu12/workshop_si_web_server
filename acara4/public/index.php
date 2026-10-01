<?php

require_once '../app/Models/Mahasiswa.php';

use App\Models\Mahasiswa;

$mahasiswa = [

    new Mahasiswa(
        "2401001",
        "Budi Santoso",
        "Teknik Informatika"
    ),

    new Mahasiswa(
        "2401002",
        "Ani Lestari",
        "Teknik Informatika"
    ),

    new Mahasiswa(
        "2401003",
        "Citra Dewi",
        "Sistem Informasi"
    )

];

$title = "Daftar Mahasiswa";

$content = '../app/Views/mahasiswa/index.php';

include '../app/Views/layouts/main.php';