<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Class BaseModel
 *
 * An abstract base class that provides basic CRUD operations for database models.
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
     * @var array The columns and their types defined in the model.
     */
    protected array $columns = [];

    /**
     * BaseModel constructor.
     *
     * Automatically connects to the database when a child model is instantiated.
     */
    public function __construct() {
        $this->db = Database::connect();
        $this->checkAndSyncTable(); // Checks and synchronizes the table when the model is instantiated
    }

    /**
     * Checks if the table exists in the database and if the columns are synchronized with the model.
     */
    private function checkAndSyncTable() {
        if (!$this->doesTableExist()) {
            // The table doesn't exist, so create the table
            $this->createTable();
        } else {
            // If the table exists, synchronize the columns
            $existingColumns = $this->getTableColumns();
            $this->syncColumns($existingColumns);
        }
    }

    /**
     * Checks if the table exists in the database.
     *
     * @return bool Returns true if the table exists, false otherwise.
     */
    private function doesTableExist(): bool {
        try {
            $stmt = $this->db->query("SHOW TABLES LIKE '{$this->table}'");
            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            // In case of an error with the query, assume the table doesn't exist
            return false;
        }
    }

    /**
     * Gets the columns of the table in the database.
     *
     * @return array|null The columns of the table or null if not found.
     */
    private function getTableColumns(): ?array {
        try {
            $stmt = $this->db->query("DESCRIBE {$this->table}");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;  // If it fails, return null
        }
    }

    /**
     * Synchronizes the table columns with those defined in the model.
     *
     * @param array $existingColumns The columns that exist in the database.
     */
    private function syncColumns(array $existingColumns): void {
        $modelColumns = array_keys($this->columns);
        $existingColumnNames = array_column($existingColumns, 'Field');

        // Checks if all columns in the model exist in the table
        foreach ($modelColumns as $column) {
            if (!in_array($column, $existingColumnNames)) {
                // If the column doesn't exist in the table, create the column
                $this->addColumn($column);
            }
        }

        // Removes columns that are no longer in the model
        foreach ($existingColumnNames as $existingColumn) {
            if (!in_array($existingColumn, $modelColumns)) {
                // If the column doesn't exist in the model, remove it from the database
                $this->removeColumn($existingColumn);
            }
        }
    }

    /**
     * Adds a column to the table in the database.
     *
     * @param string $column The name of the column to be added.
     */
    private function addColumn(string $column): void {
        $columnDefinition = $this->columns[$column];
        $this->db->exec("ALTER TABLE {$this->table} ADD COLUMN {$column} {$columnDefinition}");
    }

    /**
     * Removes a column from the table in the database.
     *
     * @param string $column The name of the column to be removed.
     */
    private function removeColumn(string $column): void {
        $this->db->exec("ALTER TABLE {$this->table} DROP COLUMN {$column}");
    }

    /**
     * Creates the table if it doesn't exist.
     */
    private function createTable(): void {
        $columns = [];
        foreach ($this->columns as $column => $definition) {
            $columns[] = "{$column} {$definition}";
        }

        $columnsSql = implode(", ", $columns);
        $this->db->exec("CREATE TABLE {$this->table} ({$columnsSql})");
    }

    /**
     * Finds a single record in the table by a specific column and value.
     *
     * @param string $column The name of the column to search.
     * @param mixed $value The value to search for.
     * @return array|null The found record as an associative array, or null if not found.
     */
        public function find(string $column, mixed $value): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = :value");
        $stmt->execute(['value' => $value]);
        
        // Verifica o número de resultados e escolhe entre fetch ou fetchAll
        //$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //return count($results) === 1 ? $results[0] : ($results ?: null);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves all records from the table, with optional sorting.
     *
     * @param string $orderBy The column to sort by (default is 'id').
     * @param string $order The sorting direction, 'ASC' or 'DESC' (default is 'ASC').
     * @return array The list of records as an array.
     */
    public function findAll(string $orderBy = 'id', string $order = 'ASC'): array {
        $allowedOrder = ['ASC', 'DESC'];
        $order = strtoupper($order);

        if (!in_array($order, $allowedOrder)) {
            $order = 'ASC';
        }

        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY {$orderBy} {$order}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inserts a new record into the table.
     *
     * @param array $data An associative array where the keys are column names and the values are the data to be inserted.
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
     * Updates existing records in the table.
     *
     * @param string $whereColumn The column name to filter which records to update.
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
     * Deletes records from the table based on a specific column and value.
     *
     * @param string $filterColumn The column name to filter.
     * @param mixed $filterValue The value to match for deletion.
     * @return bool Returns true if the deletion was successful, false otherwise.
     */
    public function delete(string $filterColumn, mixed $filterValue): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$filterColumn} = :filter");
        return $stmt->execute(['filter' => $filterValue]);
    }
}
