<?php

class Model
{
    protected PDO $db;

    public function __construct()
    {
        //Membaca konfigurasi database.
        $config = require __DIR__ . '/../../config/database.php';

        //Membuat koneksi ke database MySQL menggunakan PDO.
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";

        //membuat koneksi database.
        $this->db = new PDO(
            $dsn,
            $config['username'],
            $config['password']
        );

        //Mengatur penanganan error pada koneksi PDO agar melempar exception jika terjadi kesalahan.
        $this->db->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        //Mengatur format hasil query menjadi array asosiatif secara default.
        $this->db->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            PDO::FETCH_ASSOC
        );
    }
}