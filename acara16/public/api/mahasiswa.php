
<?php

header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__, 2) . '/app/Core/Database.php';

try {

    $database = new Database();
    $pdo = $database->getConnection();

    // Memeriksa metode HTTP
    $method = $_SERVER['REQUEST_METHOD'];

    // =========================
    // METHOD GET
    // =========================
    if ($method === 'GET') {

        if (isset($_GET['id'])) {

            // Mengambil mahasiswa berdasarkan ID
            $id = $_GET['id'];

            $query = "SELECT id, nim, nama, email
                      FROM mahasiswa
                      WHERE id = :id";

            $stmt = $pdo->prepare($query);
            $stmt->execute(['id' => $id]);

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {

                $response = [
                    'success' => true,
                    'message' => 'Data mahasiswa berhasil ditemukan',
                    'data' => $data
                ];

            } else {

                http_response_code(404);

                $response = [
                    'success' => false,
                    'message' => 'Data mahasiswa tidak ditemukan'
                ];
            }

        } else {

            // Mengambil seluruh mahasiswa
            $query = "SELECT id, nim, nama, email FROM mahasiswa";

            $stmt = $pdo->query($query);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $response = [
                'success' => true,
                'message' => 'Data berhasil diambil',
                'data' => $data
            ];
        }

    }


    // =========================
    // METHOD POST
    // =========================
    elseif ($method === 'POST') {

        // Membaca JSON dari Postman
        $input = json_decode(file_get_contents('php://input'), true);

        // Memeriksa format JSON
        if (!is_array($input)) {

            http_response_code(400);

            $response = [
                'success' => false,
                'message' => 'Format JSON tidak valid'
            ];

        }

        // Memeriksa kelengkapan data
        elseif (
            empty($input['nim']) ||
            empty($input['nama']) ||
            empty($input['email']) ||
            empty($input['prodi_id']) ||
            empty($input['angkatan'])
        ) {

            http_response_code(400);

            $response = [
                'success' => false,
                'message' => 'Semua data wajib diisi'
            ];

        } else {

            // Memeriksa apakah prodi tersedia
            $cekProdi = $pdo->prepare(
                "SELECT id FROM prodi WHERE id = :id"
            );

            $cekProdi->execute([
                'id' => $input['prodi_id']
            ]);

            if (!$cekProdi->fetch()) {

                http_response_code(400);

                $response = [
                    'success' => false,
                    'message' => 'Program studi tidak ditemukan'
                ];

            } else {

                // Menambahkan mahasiswa baru
                $query = "INSERT INTO mahasiswa
                          (nim, nama, email, prodi_id, angkatan)
                          VALUES
                          (:nim, :nama, :email, :prodi_id, :angkatan)";

                $stmt = $pdo->prepare($query);

                $stmt->execute([
                    'nim' => $input['nim'],
                    'nama' => $input['nama'],
                    'email' => $input['email'],
                    'prodi_id' => $input['prodi_id'],
                    'angkatan' => $input['angkatan']
                ]);

                http_response_code(201);

                $response = [
                    'success' => true,
                    'message' => 'Data mahasiswa berhasil ditambahkan',
                    'data' => [
                        'id' => $pdo->lastInsertId(),
                        'nim' => $input['nim'],
                        'nama' => $input['nama'],
                        'email' => $input['email'],
                        'prodi_id' => $input['prodi_id'],
                        'angkatan' => $input['angkatan'],
                        'status' => 'aktif'
                    ]
                ];
            }
        }
    }


    // =========================
    // METHOD PUT
    // =========================
    elseif ($method === 'PUT') {

        // Mengambil ID dari URL
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id) || $id <= 0) {

            http_response_code(400);

            $response = [
                'success' => false,
                'message' => 'ID mahasiswa tidak valid'
            ];

        } else {

            // Membaca JSON dari Postman
            $input = json_decode(file_get_contents('php://input'), true);

            if (!is_array($input)) {

                http_response_code(400);

                $response = [
                    'success' => false,
                    'message' => 'Format JSON tidak valid'
                ];

            } elseif (
                empty($input['nim']) ||
                empty($input['nama']) ||
                empty($input['email']) ||
                empty($input['prodi_id']) ||
                empty($input['angkatan'])
            ) {

                http_response_code(400);

                $response = [
                    'success' => false,
                    'message' => 'Semua data wajib diisi'
                ];

            } elseif (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {

                http_response_code(400);

                $response = [
                    'success' => false,
                    'message' => 'Format email tidak valid'
                ];

            } else {

                // Memeriksa apakah mahasiswa tersedia
                $cek = $pdo->prepare(
                    "SELECT id FROM mahasiswa WHERE id = :id"
                );

                $cek->execute(['id' => $id]);

                if (!$cek->fetch()) {

                    http_response_code(404);

                    $response = [
                        'success' => false,
                        'message' => 'Data mahasiswa tidak ditemukan'
                    ];

                } else {

                    // Memeriksa program studi
                    $cekProdi = $pdo->prepare(
                        "SELECT id FROM prodi WHERE id = :id"
                    );

                    $cekProdi->execute([
                        'id' => $input['prodi_id']
                    ]);

                    if (!$cekProdi->fetch()) {

                        http_response_code(400);

                        $response = [
                            'success' => false,
                            'message' => 'Program studi tidak ditemukan'
                        ];

                    } else {

                        // Memperbarui data mahasiswa
                        $query = "UPDATE mahasiswa
                                  SET nim = :nim,
                                      nama = :nama,
                                      email = :email,
                                      prodi_id = :prodi_id,
                                      angkatan = :angkatan
                                  WHERE id = :id";

                        $stmt = $pdo->prepare($query);

                        $stmt->execute([
                            'nim' => $input['nim'],
                            'nama' => $input['nama'],
                            'email' => $input['email'],
                            'prodi_id' => $input['prodi_id'],
                            'angkatan' => $input['angkatan'],
                            'id' => $id
                        ]);

                        $response = [
                            'success' => true,
                            'message' => 'Data mahasiswa berhasil diperbarui'
                        ];
                    }
                }
            }
        }
    }


    // =========================
    // METHOD DELETE
    // =========================
    elseif ($method === 'DELETE') {

        // Mengambil ID dari URL
        $id = $_GET['id'] ?? null;

        // Memeriksa validitas ID
        if (!$id || !is_numeric($id) || $id <= 0) {

            http_response_code(400);

            $response = [
                'success' => false,
                'message' => 'ID mahasiswa tidak valid'
            ];

        } else {

            // Memeriksa apakah mahasiswa tersedia
            $cek = $pdo->prepare(
                "SELECT id FROM mahasiswa WHERE id = :id"
            );

            $cek->execute(['id' => $id]);

            if (!$cek->fetch()) {

                http_response_code(404);

                $response = [
                    'success' => false,
                    'message' => 'Data mahasiswa tidak ditemukan'
                ];

            } else {

                // Menghapus data mahasiswa
                $query = "DELETE FROM mahasiswa WHERE id = :id";

                $stmt = $pdo->prepare($query);
                $stmt->execute(['id' => $id]);

                $response = [
                    'success' => true,
                    'message' => 'Data mahasiswa berhasil dihapus'
                ];
            }
        }
    }


    // =========================
    // METHOD LAIN
    // =========================
    else {

        http_response_code(405);

        $response = [
            'success' => false,
            'message' => 'Method tidak diizinkan'
        ];
    }

    echo json_encode(
        $response,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );


} catch (Throwable $e) {

    // Menentukan lokasi folder log
    $logDir = dirname(__DIR__, 2) . '/storage/logs';

    // Membuat folder jika belum tersedia
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    // Menyimpan pesan kesalahan
    $errorMessage = date('Y-m-d H:i:s')
        . ' - POST API Error: '
        . $e->getMessage()
        . PHP_EOL;

    file_put_contents(
        $logDir . '/app.log',
        $errorMessage,
        FILE_APPEND
    );

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan pada server'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}