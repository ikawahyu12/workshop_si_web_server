
<?php

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        require __DIR__ . '/../Views/' . $view . '.php';
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    protected function setFlash(
        string $type,
        string $message
    ): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    protected function logError(Throwable $e): void
    {
        $logDirectory = __DIR__ . '/../../storage/logs';

        if (!is_dir($logDirectory)) {
            mkdir($logDirectory, 0775, true);
        }

        $logFile = $logDirectory . '/app.log';

        $message = date('Y-m-d H:i:s')
            . ' - '
            . get_class($e)
            . ': '
            . $e->getMessage()
            . PHP_EOL;

        error_log($message, 3, $logFile);
    }
}
