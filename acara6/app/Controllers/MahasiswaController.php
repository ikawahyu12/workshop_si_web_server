<?php

class MahasiswaController
{
    public function index()
    {
        echo "<h1>Daftar Mahasiswa</h1>";
        echo "<p>Halaman daftar mahasiswa.</p>";
    }

    public function create()
    {
        echo "<h1>Tambah Mahasiswa</h1>";
        echo "<p>Halaman form tambah mahasiswa.</p>";
    }

    public function show($id)
    {
        echo "<h1>Detail Mahasiswa</h1>";
        echo "<p>ID Mahasiswa: " . htmlspecialchars($id) . "</p>";
    }
}