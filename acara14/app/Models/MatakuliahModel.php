<?php

require_once __DIR__ . '/../Core/Model.php';

class MatakuliahModel extends Model
{
    public function all(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM matakuliah ORDER BY kode"
        );

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM matakuliah WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $data = $stmt->fetch();

        return $data ?: null;
    }

    public function create(
        string $kode,
        string $nama,
        int $sks
    ): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO matakuliah
            (kode, nama, sks)
            VALUES
            (:kode, :nama, :sks)"
        );

        return $stmt->execute([
            'kode' => $kode,
            'nama' => $nama,
            'sks' => $sks
        ]);
    }

    public function update(
        int $id,
        string $kode,
        string $nama,
        int $sks
    ): bool {
        $stmt = $this->db->prepare(
            "UPDATE matakuliah SET
                kode = :kode,
                nama = :nama,
                sks = :sks
            WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id,
            'kode' => $kode,
            'nama' => $nama,
            'sks' => $sks
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM matakuliah WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}