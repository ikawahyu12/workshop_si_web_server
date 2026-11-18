<?php

class MahasiswaController
{
    private Mahasiswa $model;
    private string $folderUpload;

    public function __construct()
    {
        $this->model = new Mahasiswa();
        $this->folderUpload = __DIR__ . '/../../public/uploads/';
    }

    // Mengambil semua data atau satu data mahasiswa
    public function index(): void
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $data = $this->model->find((int) $id);

            if (!$data) {
                Response::json(
                    404,
                    false,
                    'Data tidak ditemukan'
                );
            }

            Response::json(
                200,
                true,
                'Data berhasil diambil',
                $data
            );
        }

        Response::json(
            200,
            true,
            'Data berhasil diambil',
            $this->model->getAll()
        );
    }

    // Memvalidasi dan menyimpan file foto (untuk POST multipart)
    private function uploadFoto(): ?string
    {
        // Jika tidak ada foto yang diupload
        if (
            !isset($_FILES['foto']) ||
            $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE
        ) {
            return null;
        }

        $file = $_FILES['foto'];

        // Memeriksa error upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            Response::json(
                400,
                false,
                'Terjadi kesalahan saat upload foto'
            );
        }

        return $this->validasiDanSimpanGambar(
            $file['name'],
            $file['size'],
            $file['tmp_name'],
            true
        );
    }

    // Validasi keamanan gambar dan penyimpanan ke folder uploads
    private function validasiDanSimpanGambar(
        string $originalName,
        int $fileSize,
        string $sourcePath,
        bool $isUploadedFile = true
    ): string {
        // Validasi ukuran maksimal 2 MB
        if ($fileSize > 2 * 1024 * 1024) {
            if (!$isUploadedFile && file_exists($sourcePath)) {
                unlink($sourcePath);
            }
            Response::json(
                422,
                false,
                'Ukuran foto maksimal 2 MB'
            );
        }

        // Memeriksa ekstensi file
        $ekstensi = strtolower(
            pathinfo($originalName, PATHINFO_EXTENSION)
        );

        if (!in_array($ekstensi, ['jpg', 'jpeg', 'png'])) {
            if (!$isUploadedFile && file_exists($sourcePath)) {
                unlink($sourcePath);
            }
            Response::json(
                422,
                false,
                'Foto harus menggunakan format JPG, JPEG, atau PNG'
            );
        }

        // Memeriksa apakah file benar-benar gambar
        $infoGambar = @getimagesize($sourcePath);

        if ($infoGambar === false) {
            if (!$isUploadedFile && file_exists($sourcePath)) {
                unlink($sourcePath);
            }
            Response::json(
                422,
                false,
                'File yang diupload bukan gambar yang valid'
            );
        }

        // Mengambil tipe gambar sebenarnya
        $tipeGambar = $infoGambar[2];

        // Memastikan gambar berformat JPG atau PNG
        if (!in_array($tipeGambar, [IMAGETYPE_JPEG, IMAGETYPE_PNG])) {
            if (!$isUploadedFile && file_exists($sourcePath)) {
                unlink($sourcePath);
            }
            Response::json(
                422,
                false,
                'Foto harus berupa gambar JPG atau PNG'
            );
        }

        // Menentukan ekstensi berdasarkan tipe gambar
        if ($tipeGambar === IMAGETYPE_JPEG) {
            $ekstensi = 'jpg';
        } elseif ($tipeGambar === IMAGETYPE_PNG) {
            $ekstensi = 'png';
        }

        // Membuat nama file unik
        $namaBaru = uniqid('foto_', true) . '.' . $ekstensi;

        // Membuat folder jika belum tersedia
        if (!is_dir($this->folderUpload)) {
            mkdir($this->folderUpload, 0755, true);
        }

        $tujuan = $this->folderUpload . $namaBaru;

        // Memindahkan file ke folder uploads
        $berhasil = $isUploadedFile
            ? move_uploaded_file($sourcePath, $tujuan)
            : rename($sourcePath, $tujuan);

        if (!$berhasil) {
            if (!$isUploadedFile && file_exists($sourcePath)) {
                unlink($sourcePath);
            }
            Response::json(
                500,
                false,
                'Gagal menyimpan foto'
            );
        }

        if (!$isUploadedFile) {
            chmod($tujuan, 0644);
        }

        return $namaBaru;
    }

    // Menghapus file fisik foto lama
    private function hapusFileFoto(?string $namaFoto): void
    {
        if ($namaFoto !== null && $namaFoto !== '') {
            $pathFoto = $this->folderUpload . basename($namaFoto);

            if (file_exists($pathFoto)) {
                unlink($pathFoto);
            }
        }
    }

    // Parser body multipart/form-data khusus untuk request PUT
    private function parseMultipartPut(): array
    {
        $rawData = file_get_contents('php://input');
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $result = [
            'fields' => [],
            'files' => []
        ];

        if (!preg_match('/boundary=(.*)$/', $contentType, $matches)) {
            return $result;
        }

        $boundary = trim($matches[1], '"');
        $blocks = preg_split("/-+$boundary/", $rawData);
        array_pop($blocks);

        foreach ($blocks as $block) {
            if (empty(trim($block))) {
                continue;
            }

            if (!str_contains($block, "\r\n\r\n")) {
                continue;
            }

            [$rawHeaders, $body] = explode("\r\n\r\n", $block, 2);
            $body = substr($body, 0, -2); // Menghapus \r\n di akhir blok

            if (preg_match('/name="([^"]*)"/', $rawHeaders, $nameMatch)) {
                $fieldName = $nameMatch[1];

                if (preg_match('/filename="([^"]*)"/', $rawHeaders, $fileMatch)) {
                    if ($fileMatch[1] !== '') {
                        $result['files'][$fieldName] = [
                            'name' => $fileMatch[1],
                            'content' => $body
                        ];
                    }
                } else {
                    $result['fields'][$fieldName] = $body;
                }
            }
        }

        return $result;
    }

    // Menambahkan mahasiswa
    public function store(): void
    {
        Auth::requireAdmin();

        // Membaca data FormData atau JSON
        if (!empty($_POST)) {
            $data = $_POST;
        } else {
            $data = json_decode(
                file_get_contents('php://input'),
                true
            );
        }

        $errors = [];

        // Memastikan format data valid
        if (!is_array($data)) {
            Response::json(
                400,
                false,
                'Format data tidak valid'
            );
        }

        $data['nim'] = trim((string) ($data['nim'] ?? ''));
        $data['nama'] = trim((string) ($data['nama'] ?? ''));
        $data['prodi'] = trim((string) ($data['prodi'] ?? ''));

        // Validasi field
        if (empty($data['nim'])) {
            $errors['nim'] = 'NIM wajib diisi';
        }

        if (empty($data['nama'])) {
            $errors['nama'] = 'Nama wajib diisi';
        }

        if (empty($data['prodi'])) {
            $errors['prodi'] = 'Program studi wajib diisi';
        }

        // Mengirim error validasi
        if (!empty($errors)) {
            Response::json(
                422,
                false,
                'Validasi gagal',
                null,
                $errors
            );
        }

        // Validasi panjang NIM
        if (strlen($data['nim']) !== 9) {
            Response::json(
                422,
                false,
                'NIM harus terdiri dari 9 karakter'
            );
        }

        // Upload foto
        $namaFoto = $this->uploadFoto();

        $data['foto'] = $namaFoto;

        try {
            // Menyimpan data mahasiswa
            $id = $this->model->create($data);

            Response::json(
                201,
                true,
                'Data berhasil ditambahkan',
                [
                    'id' => $id,
                    'foto' => $namaFoto
                ]
            );

        } catch (InvalidArgumentException $e) {

            // Menghapus foto jika proses database gagal
            $this->hapusFileFoto($namaFoto);

            Response::json(
                409,
                false,
                $e->getMessage()
            );

        } catch (Throwable $e) {

            // Menghapus foto jika proses database gagal
            $this->hapusFileFoto($namaFoto);

            Response::json(
                500,
                false,
                'Terjadi kesalahan saat menyimpan data ke database'
            );
        }
    }

    // Memperbarui mahasiswa
    public function update($id): void
    {
        Auth::requireAdmin();

        if (!$id) {
            Response::json(
                400,
                false,
                'ID mahasiswa wajib disertakan'
            );
        }

        // Mencari data mahasiswa
        $mahasiswa = $this->model->find((int) $id);

        if (!$mahasiswa) {
            Response::json(
                404,
                false,
                'Data mahasiswa tidak ditemukan'
            );
        }

        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $data = [];
        $namaFotoBaru = null;

        // Jika request menggunakan FormData (multipart/form-data) saat mengganti foto
        if (str_contains($contentType, 'multipart/form-data')) {
            $parsed = $this->parseMultipartPut();
            $data = $parsed['fields'];

            if (isset($parsed['files']['foto'])) {
                $file = $parsed['files']['foto'];
                $tmpPath = tempnam(sys_get_temp_dir(), 'put_foto_');
                file_put_contents($tmpPath, $file['content']);

                $namaFotoBaru = $this->validasiDanSimpanGambar(
                    $file['name'],
                    strlen($file['content']),
                    $tmpPath,
                    false
                );
            }
        } else {
            // Membaca data JSON
            $data = json_decode(
                file_get_contents('php://input'),
                true
            );
        }

        // Validasi data
        if (!is_array($data)) {
            $this->hapusFileFoto($namaFotoBaru);

            Response::json(
                400,
                false,
                'Format data tidak valid'
            );
        }

        $data['nim'] = trim((string) ($data['nim'] ?? ''));
        $data['nama'] = trim((string) ($data['nama'] ?? ''));
        $data['prodi'] = trim((string) ($data['prodi'] ?? ''));

        if (
            empty($data['nim']) ||
            empty($data['nama']) ||
            empty($data['prodi'])
        ) {
            $this->hapusFileFoto($namaFotoBaru);

            Response::json(
                400,
                false,
                'Field nim, nama, dan prodi wajib diisi'
            );
        }

        // Validasi panjang NIM
        if (strlen($data['nim']) !== 9) {
            $this->hapusFileFoto($namaFotoBaru);

            Response::json(
                422,
                false,
                'NIM harus terdiri dari 9 karakter'
            );
        }

        // Jika null, COALESCE(:foto, foto) di model akan mempertahankan foto lama
        $data['foto'] = $namaFotoBaru;

        try {
            // Memperbarui data mahasiswa
            $this->model->update((int) $id, $data);

            // Hapus foto lama jika user mengunggah foto baru dan update DB sukses
            if ($namaFotoBaru !== null && !empty($mahasiswa['foto'])) {
                $this->hapusFileFoto($mahasiswa['foto']);
            }

            Response::json(
                200,
                true,
                'Data berhasil diperbarui',
                [
                    'foto' => $namaFotoBaru ?? $mahasiswa['foto']
                ]
            );

        } catch (InvalidArgumentException $e) {

            $this->hapusFileFoto($namaFotoBaru);

            Response::json(
                409,
                false,
                $e->getMessage()
            );

        } catch (Throwable $e) {

            $this->hapusFileFoto($namaFotoBaru);

            Response::json(
                500,
                false,
                'Terjadi kesalahan saat memperbarui data'
            );
        }
    }

    // Menghapus mahasiswa
    public function destroy($id): void
    {
        Auth::requireAdmin();

        if (!$id) {
            Response::json(
                400,
                false,
                'ID mahasiswa wajib disertakan'
            );
        }

        // Mencari data mahasiswa
        $mahasiswa = $this->model->find((int) $id);

        if (!$mahasiswa) {
            Response::json(
                404,
                false,
                'Data mahasiswa tidak ditemukan'
            );
        }

        // Menghapus file foto lama dari server
        $this->hapusFileFoto($mahasiswa['foto'] ?? null);

        // Menghapus data mahasiswa
        $this->model->delete((int) $id);

        Response::json(
            200,
            true,
            'Data berhasil dihapus'
        );
    }
}