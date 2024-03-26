<?php

namespace App\Models;

use Core\Database;

class SearchModel
{
    private ?Database $db;
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    public function searchByTitle($keyword): array
    {
        $keyword = '%' . $keyword . '%';
        $stmt = $this->db->conn->prepare("SELECT * FROM products_sort_by_created_desc WHERE products_sort_by_created_desc.title LIKE ?");
        $stmt->bind_param("s", $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}