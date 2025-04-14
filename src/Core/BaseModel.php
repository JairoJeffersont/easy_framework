<?php

namespace App\Core;

use PDO;

/**
 * Class BaseModel
 *
 * An abstract base class to provide basic CRUD operations for database models.
 * All models that interact with the database can extend this class.
 */
abstract class BaseModel {
    /**
     * @var PDO The PDO database connection instance.
     */
    protected PDO $db;

    /**
     * @var string The name of the table associated with the model.
     */
    protected string $table;

    /**
     * BaseModel constructor.
     *
     * Automatically connects to the database when a child model is instantiated.
     */
    public function __construct() {
        $this->db = Database::connect();
    }

    /**
     * Find a single record in the table by a specific column and value.
     *
     * @param string $column The column name to search by.
     * @param mixed $value The value to search for.
     * @return array|null The found record as an associative array, or null if not found.
     */
    public function find(string $column, mixed $value): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = :value");
        $stmt->execute(['value' => $value]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Retrieve all records from the table, with optional sorting.
     *
     * @param string $orderBy The column to sort by (default is 'id').
     * @param string $order The sort direction, 'ASC' or 'DESC' (default is 'ASC').
     * @return array The list of records as an array.
     */
    public function findAll(string $orderBy = 'id', string $order = 'ASC'): array {
        $allowedOrder = ['ASC', 'DESC'];
        $order = strtoupper($order);

        if (!in_array($order, $allowedOrder)) {
            $order = 'ASC';
        }

        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY {$orderBy} {$order}");
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Insert a new record into the table.
     *
     * @param array $data An associative array where keys are column names and values are the data to insert.
     * @return bool Returns the ID of the inserted record on success, false on failure.
     */
    public function create(array $data): bool {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Update existing records in the table.
     *
     * @param string $whereColumn The column name to filter which record(s) to update.
     * @param mixed $whereValue The value of the column to match.
     * @param array $data An associative array of columns and their new values.
     * @return bool Returns true if the update was successful, false otherwise.
     */
    public function update(string $whereColumn, mixed $whereValue, array $data): bool {
        $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $data['__where'] = $whereValue;
        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$set} WHERE {$whereColumn} = :__where");
        return $stmt->execute($data);
    }

    /**
     * Delete records from the table based on a specific column and value.
     *
     * @param string $filterColumn The column name to filter by.
     * @param mixed $filterValue The value to match for deletion.
     * @return bool Returns true if the deletion was successful, false otherwise.
     */
    public function delete(string $filterColumn, mixed $filterValue): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$filterColumn} = :filter");
        return $stmt->execute(['filter' => $filterValue]);
    }
}
