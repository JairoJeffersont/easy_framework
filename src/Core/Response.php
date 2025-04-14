<?php

namespace App\Core;

class Response {
    public static function json(
        int $statusCode = 200,
        string $message = '',
        array $dados = [],
        string $status = 'success'
    ): void {
        http_response_code($statusCode);

        header('Content-Type: application/json');

        echo json_encode([
            'status_code' => $statusCode,
            'status' => $status,
            'message' => $message,
            'data' => $dados
        ]);

        exit;
    }

    public static function success(string $message = '', array $dados = [], string $status = 'success'): void {
        self::json(200, $message, $dados, $status);
    }

    public static function error(string $message = '', int $statusCode = 400, array $dados = [], string $status = 'error'): void {
        self::json($statusCode, $message, $dados, $status);
    }
}
