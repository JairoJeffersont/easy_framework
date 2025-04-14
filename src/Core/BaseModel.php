<?php

namespace App\Core;

use PDO;

abstract class BaseModel {
    protected PDO $db;
    protected string $table;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function find(string $column, mixed $value): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = :value");
        $stmt->execute(['value' => $value]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }


    public function findAll(string $orderBy = 'id', string $order = 'ASC'): array {
        $allowedOrder = ['ASC', 'DESC'];
        $order = strtoupper($order);

        if (!in_array($order, $allowedOrder)) {
            $order = 'ASC';
        }

        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY {$orderBy} {$order}");
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }


    public function create(array $data): bool {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function update(string $whereColumn, mixed $whereValue, array $data): bool {
        $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $data['__where'] = $whereValue;
        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$set} WHERE {$whereColumn} = :__where");
        return $stmt->execute($data);
    }


    public function delete(string $filterColumn, mixed $filterValue): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$filterColumn} = :filter");
        return $stmt->execute(['filter' => $filterValue]);
    }
}
