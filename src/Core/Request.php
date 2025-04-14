<?php

namespace App\Core;

class Request {
    public static function capture(): self {
        return new static();
    }

    public function uri(): string {
        $uri = $_SERVER['REQUEST_URI'];
        return strtok($uri, '?');
    }

    public function method(): string {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function input(?string $key = null) {
        $rawInput = file_get_contents('php://input');

        $data = json_decode($rawInput, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            \App\Core\Response::error('JSON inválido: ' . json_last_error_msg(), 400);
        }

        $clean = [];
        foreach ($data as $k => $v) {
            $clean[$k] = is_string($v) ? htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8') : $v;
        }

        return $key ? $clean[$key] ?? null : $clean;
    }
}
