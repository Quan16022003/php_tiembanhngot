<?php

namespace App\Models;

use Core\Database;

class CategoriesModel
{
    private ?Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getCategories($offset, $limit): array
    {
        $sql = "SELECT * FROM category LIMIT ?, ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function getTotalCategories(): int
    {
        $sql = "SELECT COUNT(*) as total FROM category";
        $result = $this->db->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getAllCategories()
    {
        $sql = "SELECT * FROM category";
        $statement = $this->db->conn->prepare($sql);
        $statement->execute();

        $result = $statement->get_result();
        if (!$result) {
            echo "List is empty!" . $this->db->conn->error;
            return false;
        }

        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }

        return $categories;
    }

    public function getCategoryById($id)
    {
        $sql = "SELECT * FROM category WHERE id = ?";
        $statement = $this->db->conn->prepare($sql);
        $statement->bind_param("i", $id);
        $statement->execute();

        $result = $statement->get_result();
        if ($result->num_rows == 0) {
            return false;
        }

        return $result->fetch_assoc();
    }

    public function createCategory($name)
    {
        $sql = "INSERT INTO category (name) VALUES (?)";
        $statement = $this->db->conn->prepare($sql);
        $statement->bind_param("s", $name);
        $statement->execute();

        return $this->db->conn->insert_id;
    }

    public function updateCategory($id, $name)
    {
        $sql = "UPDATE category SET name = ? WHERE id = ?";
        $statement = $this->db->conn->prepare($sql);
        $statement->bind_param("si", $name, $id);
        $statement->execute();

        return $statement->affected_rows > 0;
    }

    public function deleteCategory($id)
    {
        $sql = "DELETE FROM category WHERE id = ?";
        $statement = $this->db->conn->prepare($sql);
        $statement->bind_param("i", $id);
        $statement->execute();

        return $statement->affected_rows > 0;
    }
}
