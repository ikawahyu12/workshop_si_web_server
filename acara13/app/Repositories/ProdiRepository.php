<?php

require_once __DIR__ . '/../Core/Database.php';

class ProdiRepository
{
    private PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->getConnection();
    }

    public function exists(int $id): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) 
             FROM prodi 
             WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }
}