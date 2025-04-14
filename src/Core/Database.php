<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Class Database
 *
 * Manages the database connection using PDO as a singleton.
 * This ensures that only one PDO instance is created and reused throughout the application.
 */
class Database {
    /**
     * @var PDO|null The single instance of the PDO connection, or null if not yet connected.
     */
    protected static ?PDO $pdo = null;

    /**
     * Connect to the database and return the PDO instance.
     *
     * If a connection has already been established, it returns the existing one.
     * Otherwise, it creates a new connection using environment variables.
     *
     * Required environment variables:
     * - DB_HOST (e.g., 'localhost')
     * - DB_NAME (e.g., 'my_database')
     * - DB_CHARSET (e.g., 'utf8mb4')
     * - DB_USER (e.g., 'root')
     * - DB_PASS (e.g., 'password')
     *
     * @return PDO The PDO connection instance.
     */
    public static function connect(): PDO {
        if (self::$pdo === null) {
            $dsn = "mysql:host=" . $_ENV['DB_HOST'] .
                ";dbname=" . $_ENV['DB_NAME'] .
                ";charset=" . $_ENV['DB_CHARSET'];

            try {
                self::$pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // If connection fails, return a formatted error response
                Response::error('Error connecting to the database', 500, [], 'internal_server_error');
            }
        }

        return self::$pdo;
    }
}
