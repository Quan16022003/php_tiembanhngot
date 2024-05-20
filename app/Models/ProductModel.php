<?php

namespace App\Models;

use Core\Database;
use App\Models\Paginator;

class ProductModel
{
    private ?Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function selectAll($limit, $page, $orderBy = 'created', $direction = 'desc', $categoryIds): \stdClass
    {
        $validColumns = ['created', 'title', 'price'];
        $validDirections = ['asc', 'desc'];

        if (!in_array($orderBy, $validColumns) || !in_array($direction, $validDirections)) {
            throw new \InvalidArgumentException('Invalid orderBy or direction.');
        }

        $query = "SELECT product.`id`, product.`name` AS `title`, category.id AS `category_name`, product.price , created_at AS `created`, image_link

                        FROM `product` 
                        INNER JOIN category
                        ON product.category_id = category.id";
        if (!empty($categoryIds)) {
            $query .= " WHERE category.id IN (". implode(',', $categoryIds). ")";
        }

        $query .= " ORDER BY $orderBy $direction";

        $paginator = new Paginator($this->db->conn, $query);
        return $paginator->getData($limit, $page);
    }

    public function selectAllPriceASC(mixed $limit, mixed $page, $categoryIds): \stdClass
    {
        return $this->selectAll($limit, $page, 'price', 'asc', $categoryIds);
    }

    public function selectAllPriceDESC(mixed $limit, mixed $page, $categoryIds): \stdClass
    {
        return $this->selectAll($limit, $page, 'price', 'desc', $categoryIds);
    }

    public function selectAllTitleASC(mixed $limit, mixed $page, $categoryIds): \stdClass
    {
        return $this->selectAll($limit, $page, 'title', 'asc', $categoryIds);
    }

    public function selectAllTitleDESC(mixed $limit, mixed $page, $categoryIds): \stdClass
    {
        return $this->selectAll($limit, $page, 'title', 'desc', $categoryIds);
    }

    public function selectProductbyID($id): ?array
    {
        $sql = "SELECT * FROM `product` WHERE id = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }
}