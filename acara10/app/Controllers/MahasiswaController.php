<?php

require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';

class MahasiswaController extends Controller
{
    private MahasiswaRepository $repo;

    public function __construct(MahasiswaRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        $keyword = trim($_GET['keyword'] ?? '');

        if ($keyword !== '') {
            $mahasiswa = $this->repo->all();

            $mahasiswa = array_filter($mahasiswa, function ($data) use ($keyword) {
                return stripos($data['nim'], $keyword) !== false
                    || stripos($data['nama'], $keyword) !== false;
            });
        } else {
            $mahasiswa = $this->repo->all();
        }

        $this->view('mahasiswa/index', [
            'mahasiswa' => $mahasiswa
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->repo->create(
                $_POST['nim'],
                $_POST['nama'],
                $_POST['email'],
                (int) $_POST['prodi_id'],
                (int) $_POST['angkatan']
            );

            $this->redirect(
                '/workshop_si_web_server/acara10/public/mahasiswa'
            );
        }

        $this->view('mahasiswa/create');
    }

    public function edit()
    {
        $id = (int) ($_GET['id'] ?? 0);

        $mahasiswa = $this->repo->find($id);

        if (!$mahasiswa) {
            echo "Data mahasiswa tidak ditemukan.";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->repo->update(
                $id,
                $_POST['nim'],
                $_POST['nama'],
                $_POST['email'],
                (int) $_POST['prodi_id'],
                (int) $_POST['angkatan']
            );

            $this->redirect(
                '/workshop_si_web_server/acara10/public/mahasiswa'
            );
        }

        $this->view('mahasiswa/edit', [
            'mahasiswa' => $mahasiswa
        ]);
    }

    public function delete()
    {
        $id = (int) ($_GET['id'] ?? 0);

        $this->repo->delete($id);

        $this->redirect(
            '/workshop_si_web_server/acara10/public/mahasiswa'
        );
    }
}