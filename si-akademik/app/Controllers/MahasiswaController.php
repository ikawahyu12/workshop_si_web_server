<?php

namespace App\Controllers;

class MahasiswaController
{
    public function index()
    {
        echo "Halaman Daftar Mahasiswa";
    }

    public function create()
    {
        echo "Halaman Tambah Mahasiswa";
    }

    public function store()
    {
        echo "Data mahasiswa disimpan";
    }
    public function show($id)
    {
        echo "Detail Mahasiswa dengan ID: " . $id;
    }
}