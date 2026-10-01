<?php

namespace App\Models;

class Mahasiswa
{
    private string $nim;
    private string $nama;
    private string $prodi;

    public function __construct(
        string $nim,
        string $nama,
        string $prodi
    ) {
        $this->setNim($nim);
        $this->setNama($nama);
        $this->setProdi($prodi);
    }

    // Getter NIM
    public function getNim(): string
    {
        return $this->nim;
    }

    // Setter NIM
    public function setNim(string $nim): void
    {
        if (!ctype_digit($nim)) {
            throw new \InvalidArgumentException(
            'NIM harus berupa angka.'
        );
        }

        $this->nim = $nim;
    }

    // Getter Nama
    public function getNama(): string
    {
        return $this->nama;
    }

    // Setter Nama
    public function setNama(string $nama): void
    {
        if (trim($nama) === '') {
            throw new \InvalidArgumentException(
            'Nama mahasiswa tidak boleh kosong.'
        );
        }

        $this->nama = $nama;
    }

    // Getter Prodi
    public function getProdi(): string
    {
        return $this->prodi;
    }

    // Setter Prodi
    public function setProdi(string $prodi): void
    {
        $this->prodi = $prodi;
    }

    // Mendapatkan angkatan dari NIM
    public function getAngkatan(): int
    {
        return 2000 + (int) substr($this->nim, 0, 2);
    }
}