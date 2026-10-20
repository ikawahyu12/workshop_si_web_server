<?php

require_once __DIR__ . '/../Core/Model.php';

class ProdiModel extends Model
{
    public function all(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM prodi ORDER BY kode"
        );

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM prodi WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $data = $stmt->fetch();

        return $data ?: null;
    }

    public function create(
        string $kode,
        string $nama
    ): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO prodi
            (kode, nama)
            VALUES
            (:kode, :nama)"
        );

        return $stmt->execute([
            'kode' => $kode,
            'nama' => $nama
        ]);
    }

    public function update(
        int $id,
        string $kode,
        string $nama
    ): bool {
        $stmt = $this->db->prepare(
            "UPDATE prodi SET
                kode = :kode,
                nama = :nama
            WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id,
            'kode' => $kode,
            'nama' => $nama
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM prodi WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}