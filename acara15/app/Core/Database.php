
<?php

class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";

        try {
            $this->pdo = new PDO(
                $dsn,
                $config['username'],
                $config['password']
            );

            $this->pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $this->pdo->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );

        } catch (PDOException $e) {

            // Lokasi folder log
            $logDir = dirname(__DIR__, 2) . '/storage/logs';

            // Membuat folder jika belum tersedia
            if (!is_dir($logDir)) {
                mkdir($logDir, 0775, true);
            }

            // Menyimpan error ke app.log
            $logFile = $logDir . '/app.log';

            $message = '[' . date('Y-m-d H:i:s') . '] ';
            $message .= 'Database Connection Error: ';
            $message .= $e->getMessage() . PHP_EOL;

            file_put_contents($logFile, $message, FILE_APPEND);

            // Menampilkan pesan aman
            http_response_code(500);
            exit('Terjadi kesalahan pada sistem. Silakan coba kembali.');
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
