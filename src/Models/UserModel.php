<?php

namespace App\Models;

use App\Core\BaseModel;

class UserModel extends BaseModel {
    protected string $table = 'users';
    
    public array $columns = [
        'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
        'name' => 'VARCHAR(255) NOT NULL',
        'teste' => 'VARCHAR(255) DEFAULT NULL',
        'password' => 'VARCHAR(255) NOT NULL',
        'email' => 'VARCHAR(255) NOT NULL UNIQUE',
        'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    ];
}
