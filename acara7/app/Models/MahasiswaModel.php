<?php

require_once __DIR__ . '/../Core/Model.php';

class MahasiswaModel extends Model
{
    public function all(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM mahasiswa ORDER BY nim"
        );

        return $stmt->fetchAll();
    }
}