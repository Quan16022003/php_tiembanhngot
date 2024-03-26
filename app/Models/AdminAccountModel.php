<?php

namespace App\Models;

use Core\Database;

class AdminAccountModel
{
    private ?Database $db;
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    public function selectAll(): array
    {
        return $this->db->selectAll('user');
    }
}