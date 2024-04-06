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
        $sql = "SELECT * FROM admin_list_view WHERE `$option` LIKE '%$text%'";
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

    public function selectByID(string $id)
    {
        return $this->search('id', $id)[0];
    }

    public function update($id, array $data): \mysqli_result|bool
    {
        return $this->db->update(
            table: 'admin',
            data: [
                'name' => $data['name'],
            ],id: $id);
    }

    public function selectAllFunctions(): array
    {
        $rs = $this->db->selectAll('function');
        $row = [];
        foreach ($rs as $r) {
            $row[] = [
                'id' => $r['id'],
                'name' => $r['name']
            ];
        }
        return $row;
    }
}