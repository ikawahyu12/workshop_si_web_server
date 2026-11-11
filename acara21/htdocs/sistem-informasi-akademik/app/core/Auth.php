
<?php

class Auth
{
    // Secret key untuk membuat dan memeriksa JWT
    private const SECRET = 'kunci_rahasia_acara20_ganti_dengan_kunci_acak';

    // Mengubah data menjadi format Base64 URL-safe
    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    // Mengubah Base64 URL-safe kembali menjadi data asli
    private static function base64UrlDecode(string $data)
    {
        $padding = strlen($data) % 4;

        if ($padding > 0) {
            $data .= str_repeat('=', 4 - $padding);
        }

        return base64_decode(strtr($data, '-_', '+/'), true);
    }

    // Membuat JWT Token
    public static function generateToken(array $payload): string
    {
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        $payload['iat'] = time();
        $payload['exp'] = time() + 3600; // Berlaku 1 jam

        $headerEncoded = self::base64UrlEncode(
            json_encode($header)
        );

        $payloadEncoded = self::base64UrlEncode(
            json_encode($payload)
        );

        $signature = hash_hmac(
            'sha256',
            $headerEncoded . '.' . $payloadEncoded,
            self::SECRET,
            true
        );

        $signatureEncoded = self::base64UrlEncode($signature);

        return $headerEncoded . '.' .
               $payloadEncoded . '.' .
               $signatureEncoded;
    }

    // Memeriksa keaslian dan masa berlaku JWT
    public static function validateToken(string $token): ?array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return null;
        }

        [$headerEncoded, $payloadEncoded, $signatureEncoded] = $parts;

        $headerJson = self::base64UrlDecode($headerEncoded);
        $payloadJson = self::base64UrlDecode($payloadEncoded);
        $signature = self::base64UrlDecode($signatureEncoded);

        if (
            $headerJson === false ||
            $payloadJson === false ||
            $signature === false
        ) {
            return null;
        }

        $header = json_decode($headerJson, true);
        $payload = json_decode($payloadJson, true);

        if (
            !is_array($header) ||
            !is_array($payload) ||
            ($header['alg'] ?? null) !== 'HS256' ||
            !isset($payload['exp'])
        ) {
            return null;
        }

        // Membuat ulang tanda tangan untuk dibandingkan
        $expectedSignature = hash_hmac(
            'sha256',
            $headerEncoded . '.' . $payloadEncoded,
            self::SECRET,
            true
        );

        if (!hash_equals($expectedSignature, $signature)) {
            return null;
        }

        // Memeriksa apakah token sudah kedaluwarsa
        if ($payload['exp'] < time()) {
            return null;
        }

        return $payload;
    }

    // Memastikan pengguna sudah login
    public static function requireAuth(): array
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (!preg_match('/^Bearer\s+(.+)$/i', $authHeader, $matches)) {
            Response::json(
                401,
                false,
                'Token tidak ditemukan'
            );
        }

        $payload = self::validateToken($matches[1]);

        if ($payload === null) {
            Response::json(
                401,
                false,
                'Token tidak valid atau sudah kedaluwarsa'
            );
        }

        return $payload;
    }
}
