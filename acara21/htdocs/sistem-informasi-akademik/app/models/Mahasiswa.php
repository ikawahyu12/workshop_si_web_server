
<?php

class Mahasiswa {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(): array {
        return $this->db->query(
            'SELECT * FROM mahasiswa ORDER BY id DESC'
        )->fetchAll();
    }

    public function find(int $id) {
        $stmt = $this->db->prepare(
            'SELECT * FROM mahasiswa WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create(array $data): string {
        try {
            if ($this->isNimExists($data['nim'])) {
                throw new InvalidArgumentException(
                    'NIM sudah terdaftar'
                );
            }

            $stmt = $this->db->prepare(
                'INSERT INTO mahasiswa (nim, nama, prodi)
                 VALUES (:nim, :nama, :prodi)'
            );

            $stmt->execute([
                ':nim' => $data['nim'],
                ':nama' => $data['nama'],
                ':prodi' => $data['prodi']
            ]);

            return $this->db->lastInsertId();

        } catch (InvalidArgumentException $e) {
            throw $e;

        } catch (PDOException $e) {
            error_log($e->getMessage());

            throw new RuntimeException(
                'Terjadi kesalahan saat menyimpan data mahasiswa'
            );
        }
    }

    public function update(int $id, array $data): void {
        if ($this->isNimExists($data['nim'], $id)) {
            throw new InvalidArgumentException(
                'NIM sudah digunakan mahasiswa lain'
            );
        }

        $stmt = $this->db->prepare(
            'UPDATE mahasiswa
             SET nim = :nim, nama = :nama, prodi = :prodi
             WHERE id = :id'
        );

        $stmt->execute([
            ':nim' => $data['nim'],
            ':nama' => $data['nama'],
            ':prodi' => $data['prodi'],
            ':id' => $id
        ]);
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare(
            'DELETE FROM mahasiswa WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);
    }

    public function isNimExists(
        string $nim,
        ?int $excludeId = null
    ): bool {
        $sql = 'SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim';

        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
        }

        $stmt = $this->db->prepare($sql);

        $params = [':nim' => $nim];

        if ($excludeId !== null) {
            $params[':id'] = $excludeId;
        }

        $stmt->execute($params);

        return $stmt->fetchColumn() > 0;
    }
}
