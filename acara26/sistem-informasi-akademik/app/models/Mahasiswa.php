<?php

class Mahasiswa
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Mengambil semua data mahasiswa
    public function getAll(): array
    {
        return $this->db->query(
            'SELECT * FROM mahasiswa ORDER BY id DESC'
        )->fetchAll();
    }

    // Mengambil data mahasiswa berdasarkan ID
    public function find(int $id)
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM mahasiswa WHERE id = :id'
        );

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch();
    }

    // Menambahkan data mahasiswa
    public function create(array $data): string
    {
        // Memeriksa apakah NIM sudah digunakan sebelum insert
        if ($this->isNimExists($data['nim'])) {
            throw new InvalidArgumentException(
                'NIM sudah terdaftar pada mahasiswa lain'
            );
        }

        $stmt = $this->db->prepare(
            'INSERT INTO mahasiswa (nim, nama, prodi, foto)
            VALUES (:nim, :nama, :prodi, :foto)'
        );

        $stmt->execute([
            ':nim' => $data['nim'],
            ':nama' => $data['nama'],
            ':prodi' => $data['prodi'],
            ':foto' => $data['foto'] ?? null,
        ]);

        return $this->db->lastInsertId();
    }

    // Mengubah data mahasiswa
    public function update(int $id, array $data): void
    {
        // Memeriksa apakah NIM sudah digunakan mahasiswa lain
        if ($this->isNimExists($data['nim'], $id)) {
            throw new InvalidArgumentException(
                'NIM sudah digunakan mahasiswa lain'
            );
        }

        $stmt = $this->db->prepare(
            'UPDATE mahasiswa
            SET nim = :nim,
                nama = :nama,
                prodi = :prodi,
                foto = COALESCE(:foto, foto)
            WHERE id = :id'
        );

        $stmt->execute([
            ':nim' => $data['nim'],
            ':nama' => $data['nama'],
            ':prodi' => $data['prodi'],
            ':foto' => $data['foto'] ?? null,
            ':id' => $id
        ]);
    }

    // Menghapus data mahasiswa
    public function delete(int $id): void
    {
        $stmt = $this->db->prepare(
            'DELETE FROM mahasiswa WHERE id = :id'
        );

        $stmt->execute([
            ':id' => $id
        ]);
    }

    // Memeriksa apakah NIM sudah digunakan
    public function isNimExists(
        string $nim,
        ?int $excludeId = null
    ): bool {
        $sql = 'SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim';

        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
        }

        $stmt = $this->db->prepare($sql);

        $params = [
            ':nim' => $nim
        ];

        if ($excludeId !== null) {
            $params[':id'] = $excludeId;
        }

        $stmt->execute($params);

        return $stmt->fetchColumn() > 0;
    }
}