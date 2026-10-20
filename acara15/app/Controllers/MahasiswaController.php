<?php

require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';
require_once __DIR__ . '/../Service/MahasiswaService.php';

class MahasiswaController extends Controller
{
    private MahasiswaService $service;

    public function __construct(MahasiswaService $service)
    {
        $this->service = $service;
    }

    public function index(): void
    {
        $keyword = trim($_GET['keyword'] ?? '');

        $mahasiswa = $this->service->getAllMahasiswa($keyword);

        $this->view('mahasiswa/index', [
            'mahasiswa' => $mahasiswa
        ]);
    }

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $result = $this->service->createMahasiswa($_POST);

            if (!$result['success']) {

                $this->setFlash(
                    'danger',
                    implode('<br>', $result['errors'])
                );

                $this->redirect(
                    '/workshop_si_web_server/acara14/public/mahasiswa/create'
                );
            }

            $this->setFlash(
                'success',
                'Data mahasiswa berhasil ditambahkan.'
            );

            $this->redirect(
                '/workshop_si_web_server/acara14/public/mahasiswa'
            );
        }

        $this->view('mahasiswa/create');
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        $mahasiswa = $this->service->getMahasiswaById($id);

        if (!$mahasiswa) {

            $this->setFlash(
                'danger',
                'Data mahasiswa tidak ditemukan.'
            );

            $this->redirect(
                '/workshop_si_web_server/acara14/public/mahasiswa'
            );
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $result = $this->service->updateMahasiswa(
                $id,
                $_POST
            );

            if (!$result['success']) {

                $this->setFlash(
                    'danger',
                    implode('<br>', $result['errors'])
                );

                $this->redirect(
                    '/workshop_si_web_server/acara14/public/mahasiswa/edit?id=' . $id
                );
            }

            $this->setFlash(
                'success',
                'Data mahasiswa berhasil diperbarui.'
            );

            $this->redirect(
                '/workshop_si_web_server/acara14/public/mahasiswa'
            );
        }

        $this->view('mahasiswa/edit', [
            'mahasiswa' => $mahasiswa
        ]);
    }

    public function delete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        $success = $this->service->deleteMahasiswa($id);

        if ($success) {

            $this->setFlash(
                'success',
                'Data mahasiswa berhasil dihapus.'
            );

        } else {

            $this->setFlash(
                'danger',
                'Gagal menghapus data mahasiswa.'
            );
        }

        $this->redirect(
            '/workshop_si_web_server/acara14/public/mahasiswa'
        );
    }
}