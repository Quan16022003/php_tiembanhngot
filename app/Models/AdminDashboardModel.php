<?php

namespace App\Models;

use Core\Database;

class AdminDashboardModel
{
    private ?Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function index(): false|array
    {
        $sql = "SELECT * FROM product";
        $result = $this->db->conn->query($sql);
        if (!$result) {
            echo "List is empty!" . $this->db->conn->error;
            return false;
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}