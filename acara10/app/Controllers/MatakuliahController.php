<?php

require_once __DIR__ . '/../Models/MatakuliahModel.php';

class MatakuliahController
{
    public function index()
    {
        $model = new MatakuliahModel();

        $matakuliah = $model->all();

        require_once __DIR__ . '/../Views/matakuliah/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $model = new MatakuliahModel();

            $model->create(
                $_POST['kode'],
                $_POST['nama'],
                (int) $_POST['sks']
            );

            header('Location: /workshop_si_web_server/acara8/public/matakuliah');
            exit;
        }

        require_once __DIR__ . '/../Views/matakuliah/create.php';
    }

    public function edit()
    {
        $id = (int) ($_GET['id'] ?? 0);

        $model = new MatakuliahModel();

        $matakuliah = $model->find($id);

        if (!$matakuliah) {
            echo "Data mata kuliah tidak ditemukan.";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $model->update(
                $id,
                $_POST['kode'],
                $_POST['nama'],
                (int) $_POST['sks']
            );

            header('Location: /workshop_si_web_server/acara8/public/matakuliah');
            exit;
        }

        require_once __DIR__ . '/../Views/matakuliah/edit.php';
    }

    public function delete()
    {
        $id = (int) ($_GET['id'] ?? 0);

        $model = new MatakuliahModel();

        $model->delete($id);

        header('Location: /workshop_si_web_server/acara8/public/matakuliah');
        exit;
    }
}