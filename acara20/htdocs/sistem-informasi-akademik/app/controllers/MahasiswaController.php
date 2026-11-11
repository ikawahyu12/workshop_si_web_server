<?php

class MahasiswaController
{
    private Mahasiswa $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    // Mengambil semua data atau satu data mahasiswa
    public function index(): void
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $data = $this->model->find((int) $id);

            if (!$data) {
                Response::json(404, false, 'Data tidak ditemukan');
            }

            Response::json(200, true, 'Data berhasil diambil', $data);
        }

        Response::json(
            200,
            true,
            'Data berhasil diambil',
            $this->model->getAll()
        );
    }

    // Menambahkan mahasiswa
    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $errors = [];

        if (!is_array($data)) {
            Response::json(400, false, 'Format JSON tidak valid');
        }

        if (empty($data['nim'])) {
            $errors['nim'] = 'NIM wajib diisi';
        }

        if (empty($data['nama'])) {
            $errors['nama'] = 'Nama wajib diisi';
        }

        if (empty($data['prodi'])) {
            $errors['prodi'] = 'Program studi wajib diisi';
        }

        if (!empty($errors)) {
            Response::json(422, false, 'Validasi gagal', null, $errors);
        }

        if (strlen((string) $data['nim']) !== 9) {
            Response::json(422, false, 'NIM harus terdiri dari 9 karakter');
        }

        try {
            $id = $this->model->create($data);

            Response::json(
                201,
                true,
                'Data berhasil ditambahkan',
                ['id' => $id]
            );
        } catch (InvalidArgumentException $e) {
            Response::json(409, false, $e->getMessage());
        }
    }

    // Memperbarui mahasiswa
    public function update($id): void
    {
        $mahasiswa = $this->model->find((int) $id);

        if (!$mahasiswa) {
            Response::json(404, false, 'Data mahasiswa tidak ditemukan');
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (
            !is_array($data) ||
            empty($data['nim']) ||
            empty($data['nama']) ||
            empty($data['prodi'])
        ) {
            Response::json(400, false, 'Field nim, nama, dan prodi wajib diisi');
        }

        if (strlen((string) $data['nim']) !== 9) {
            Response::json(422, false, 'NIM harus terdiri dari 9 karakter');
        }

        try {
            $this->model->update((int) $id, $data);

            Response::json(200, true, 'Data berhasil diperbarui');
        } catch (InvalidArgumentException $e) {
            Response::json(409, false, $e->getMessage());
        }
    }

    // Menghapus mahasiswa
    public function destroy($id): void
    {
        $mahasiswa = $this->model->find((int) $id);

        if (!$mahasiswa) {
            Response::json(404, false, 'Data mahasiswa tidak ditemukan');
        }

        $this->model->delete((int) $id);

        Response::json(200, true, 'Data berhasil dihapus');
    }
}