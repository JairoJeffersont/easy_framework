<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    protected static ?PDO $pdo = null;

    public static function connect(): PDO {
        if (self::$pdo === null) {
            $dsn = $_ENV['DB_DRIVER'] . ":host=" . $_ENV['DB_HOST'] .
                ";dbname=" . $_ENV['DB_NAME'] .
                ";charset=" . $_ENV['DB_CHARSET'];

            try {
                self::$pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                Response::error('Erro ao conectar no banco: ' . $e->getMessage(), 500);
            }
        }

        return self::$pdo;
    }
}
