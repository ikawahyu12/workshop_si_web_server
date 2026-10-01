<?php

require_once __DIR__ . '/../Core/Model.php';

class MahasiswaModel extends Model
{
    public function all(): array
    {
        $stmt = $this->db->query(
            "SELECT mahasiswa.*, prodi.nama AS prodi_nama
            FROM mahasiswa
            JOIN prodi ON mahasiswa.prodi_id = prodi.id
            ORDER BY mahasiswa.nim"
        );

        return $stmt->fetchAll();
    }

    public function search(string $keyword): array
    {
        $stmt = $this->db->prepare(
            "SELECT mahasiswa.*, prodi.nama AS prodi_nama
            FROM mahasiswa
            JOIN prodi ON mahasiswa.prodi_id = prodi.id
            WHERE mahasiswa.nim LIKE :keyword
            OR mahasiswa.nama LIKE :keyword
            ORDER BY mahasiswa.nim"
        );

        $stmt->execute([
            'keyword' => '%' . $keyword . '%'
        ]);

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM mahasiswa WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $data = $stmt->fetch();

        return $data ?: null;
    }

    public function create(
        string $nim,
        string $nama,
        string $email,
        int $prodi_id,
        int $angkatan
    ): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO mahasiswa
            (nim, nama, email, prodi_id, angkatan)
            VALUES
            (:nim, :nama, :email, :prodi_id, :angkatan)"
        );

        return $stmt->execute([
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'prodi_id' => $prodi_id,
            'angkatan' => $angkatan
        ]);
    }

    public function update(
        int $id,
        string $nim,
        string $nama,
        string $email,
        int $prodi_id,
        int $angkatan
    ): bool {
        $stmt = $this->db->prepare(
            "UPDATE mahasiswa SET
                nim = :nim,
                nama = :nama,
                email = :email,
                prodi_id = :prodi_id,
                angkatan = :angkatan
            WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id,
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'prodi_id' => $prodi_id,
            'angkatan' => $angkatan
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM mahasiswa WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}