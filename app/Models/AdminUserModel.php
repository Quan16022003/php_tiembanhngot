<?php

namespace App\Models;

use Core\Database;

class AdminUserModel
{
    private ?Database $db;
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    public function insert($name, $username, $password): bool
    {
        return $this->db->insert('admin', data: [
            'name' => $name,
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }
    public function selectAll(): array
    {
        return $this->db->selectAll('admin_list_view');
    }
    public function search($option, $text): array
    {
        $sql = match ($option) {
            'name' => "SELECT * FROM admin_list_view WHERE `name` LIKE '%$text%'",
            default => "SELECT * FROM admin_list_view WHERE `username` LIKE '%$text%'",
        };
        $result = $this->db->conn->query($sql);
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function check($username): bool
    {
        return count($this->search('username', $username)) == 1;
    }
}