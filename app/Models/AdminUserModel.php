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
    public function insert($name, $username, $password, $per): bool
    {
        return $this->db->insert('admin', data: [
            'name' => $name,
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'id_per' => $per
        ]);
    }
    public function selectAll(): array
    {
        $sql = "SELECT a.id, a.username, a.name, p.name as 'permission_name' FROM admin a LEFT JOIN permission p on p.id = a.id;";
        $result = $this->db->conn->query($sql);
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
//        return $this->db->selectAll('admin_list_view');
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
                'id_per' => $data['id_per'],
            ],id: $id);
    }

    public function delete($id): \mysqli_result|bool
    {
        return $this->db->delete('admin', id: $id);
    }
    public function getPermissions(): array
    {
        $query = "SELECT p.*, CASE WHEN a.id_per IS NOT NULL THEN 1 ELSE 0 END AS hasUse
                    FROM `permission` p
                    LEFT JOIN admin a ON a.id_per = p.id;";
        $result = $this->db->conn->query($query);
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function selectAllActions(): array
    {
        $rs = $this->db->selectAll('action');
        $row = [];
        foreach ($rs as $r) {
            $row[] = [
                'id' => $r['id'],
                'name' => $r['name']
            ];
        }
        return $row;
    }

    public function selectAllPerAction($idPer): array
    {
        $query = "SELECT id_action, f FROM per_act WHERE id_per = $idPer";
        $rows = $this->db->conn->query($query);
        $result = array();
        while ($row = $rows->fetch_assoc())
        {
            $id_action = $row['id_action'];
            $f = $row['f'];

            $result[$id_action][$f] = true;
        }
        return $result;
    }

    public function createPermission(string $name, string $describe): int|string
    {
        $query = "INSERT INTO permission (name, permission.describe) VALUES (?, ?)";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bind_param("ss", $name, $describe);
        $stmt->execute();
        return $stmt->insert_id;

    }

    public function assignActionToPermission($permissionId, $actionId, $f): void
    {
        $query = "INSERT INTO per_act (id_per, id_action, f) VALUES (?, ?, ?)";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bind_param("iss", $permissionId, $actionId, $f);
        $stmt->execute();
    }

    public function selectPermissionById(string $id)
    {
        $sql = "SELECT * FROM `permission` WHERE id=?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function deletePermission(mixed $id): bool
    {
        $sql = "DELETE FROM permission WHERE id = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows > 0;
    }

    public function updatePermission(mixed $id, string $name, string $describe): bool
    {
        $sql = "UPDATE permission SET name =?, permission.describe =? WHERE id = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("sss", $name, $describe, $id);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows > 0;
    }
    
    public function deleteAllActionsFromPermission(string $id): bool
    {
        $sql = "DELETE FROM per_act WHERE id_per = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows > 0;
    }
}