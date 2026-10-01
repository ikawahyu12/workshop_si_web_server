<?php

require_once __DIR__ . '/../Models/ProdiModel.php';

class ProdiController
{
    public function index()
    {
        $model = new ProdiModel();

        $prodi = $model->all();

        require_once __DIR__ . '/../Views/prodi/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $model = new ProdiModel();

            $model->create(
                $_POST['kode'],
                $_POST['nama']
            );

            header('Location: /workshop_si_web_server/acara8/public/prodi');
            exit;
        }

        require_once __DIR__ . '/../Views/prodi/create.php';
    }

    public function edit()
    {
        $id = (int) ($_GET['id'] ?? 0);

        $model = new ProdiModel();

        $prodi = $model->find($id);

        if (!$prodi) {
            echo "Data prodi tidak ditemukan.";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $model->update(
                $id,
                $_POST['kode'],
                $_POST['nama']
            );

            header('Location: /workshop_si_web_server/acara8/public/prodi');
            exit;
        }

        require_once __DIR__ . '/../Views/prodi/edit.php';
    }

    public function delete()
    {
        $id = (int) ($_GET['id'] ?? 0);

        $model = new ProdiModel();

        $model->delete($id);

        header('Location: /workshop_si_web_server/acara8/public/prodi');
        exit;
    }
}