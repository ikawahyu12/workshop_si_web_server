<?php

require_once __DIR__ . '/../Models/MahasiswaModel.php';

class MahasiswaController
{
    public function index()
    {
        $model = new MahasiswaModel();

        $keyword = trim($_GET['keyword'] ?? '');

        if ($keyword !== '') {
            $mahasiswa = $model->search($keyword);
        } else {
            $mahasiswa = $model->all();
        }

        require_once __DIR__ . '/../Views/mahasiswa/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $model = new MahasiswaModel();

            $model->create(
                $_POST['nim'],
                $_POST['nama'],
                $_POST['email'],
                (int) $_POST['prodi_id'],
                (int) $_POST['angkatan']
            );

            header('Location: /workshop_si_web_server/acara8/public/mahasiswa');
            exit;
        }

        require_once __DIR__ . '/../Views/mahasiswa/create.php';
    }

    public function edit()
    {
        $id = (int) ($_GET['id'] ?? 0);

        $model = new MahasiswaModel();

        $mahasiswa = $model->find($id);

        if (!$mahasiswa) {
            echo "Data mahasiswa tidak ditemukan.";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $model->update(
                $id,
                $_POST['nim'],
                $_POST['nama'],
                $_POST['email'],
                (int) $_POST['prodi_id'],
                (int) $_POST['angkatan']
            );

            header('Location: /workshop_si_web_server/acara8/public/mahasiswa');
            exit;
        }

        require_once __DIR__ . '/../Views/mahasiswa/edit.php';
    }

    public function delete()
    {
        $id = (int) ($_GET['id'] ?? 0);

        $model = new MahasiswaModel();

        $model->delete($id);

        header('Location: /workshop_si_web_server/acara8/public/mahasiswa');
        exit;
    }
}