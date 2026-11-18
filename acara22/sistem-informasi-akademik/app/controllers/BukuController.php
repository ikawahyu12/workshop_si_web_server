<?php

class BukuController {
    private Buku $model;

    public function __construct() {
        $this->model = new Buku();
    }

    public function index(): void {
        header('Content-Type: application/json');

        $id = $_GET['id'] ?? null;

        if ($id !== null) {
            $data = $this->model->find((int) $id);

            if (!$data) {
                http_response_code(404);

                echo json_encode([
                    'success' => false,
                    'message' => 'Data buku tidak ditemukan'
                ]);

                return;
            }

            echo json_encode([
                'success' => true,
                'data' => $data
            ]);

            return;
        }

        echo json_encode([
            'success' => true,
            'data' => $this->model->getAll()
        ]);
    }

    public function store(): void {
        header('Content-Type: application/json');

        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (
            !is_array($data) ||
            empty($data['judul']) ||
            empty($data['penulis']) ||
            empty($data['penerbit']) ||
            empty($data['tahun_terbit'])
        ) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Semua field wajib diisi'
            ]);

            return;
        }

        $id = $this->model->create($data);

        http_response_code(201);

        echo json_encode([
            'success' => true,
            'message' => 'Data buku berhasil ditambahkan',
            'data' => ['id' => $id]
        ]);
    }

    public function update($id): void {
        header('Content-Type: application/json');

        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (
            !$id ||
            !is_array($data) ||
            empty($data['judul']) ||
            empty($data['penulis']) ||
            empty($data['penerbit']) ||
            empty($data['tahun_terbit'])
        ) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'ID dan semua field wajib diisi'
            ]);

            return;
        }

        if (!$this->model->find((int) $id)) {
            http_response_code(404);

            echo json_encode([
                'success' => false,
                'message' => 'Data buku tidak ditemukan'
            ]);

            return;
        }

        $this->model->update((int) $id, $data);

        echo json_encode([
            'success' => true,
            'message' => 'Data buku berhasil diperbarui'
        ]);
    }

    public function destroy($id): void {
        header('Content-Type: application/json');

        if (!$id || !$this->model->find((int) $id)) {
            http_response_code(404);

            echo json_encode([
                'success' => false,
                'message' => 'Data buku tidak ditemukan'
            ]);

            return;
        }

        $this->model->delete((int) $id);

        echo json_encode([
            'success' => true,
            'message' => 'Data buku berhasil dihapus'
        ]);
    }
}