<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Token
{
    private static $key = 'YOUR_SECRET_KEY'; // put this in .env for real project

    /**
     * Generate JWT
     */
    public static function generate($payload, $expiryMinutes = 60)
    {
        $payload['exp'] = time() + ($expiryMinutes * 60);
        return JWT::encode($payload, self::$key, 'HS256');
    }

    /**
     * Validate + Return decoded
     */
    public static function decode($token)
    {
        return JWT::decode($token, new Key(self::$key, 'HS256'));
    }

    /**
     * Mini Middleware for API
     */
    public static function verify()
    {
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $matches = [];
        if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
            try {
                $decoded = self::decode($matches[1]);
                return $decoded; // use inside your handler if needed
            } catch (Exception $e) {
                http_response_code(401);
                echo json_encode(['error' => 'Invalid Token']);
                exit;
            }
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Bearer Token Required']);
            exit;
        }
    }
}
