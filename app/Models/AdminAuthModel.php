<?php

namespace App\Models;

use Core\Database;

class AdminAuthModel
{
    private ?Database $db;
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    public function verify($username, $password): bool
    {
        $sql = "SELECT password FROM user WHERE username = '$username'";
        $result = $this->db->conn->query($sql);
        if (!$result) {
            echo "Lỗi: " . $this->db->conn->error;
            return false;
        }
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $pw_from_db = $row['password'];
            return password_verify($password, $pw_from_db);
        } else {
            return false;
        }
    }


}