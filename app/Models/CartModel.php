<?php

namespace AppModels;

use CoreDatabase;
use CorePaginator;

class CartModel
{
    protected $table = 'cart'; // Tên bảng trong cơ sở dữ liệu
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function selectProductbyID($id): ?array
    {
        return $this->db->selectById('product', $id);
    } 

    public function addToCart($customer_id, $product_id, $quantity): ?bool
    {
        return $this->db->insertById($customer_id, $product_id, $quantity);
    } 
    public function getAll(): array
    {
        return $this->db->getAllcart();  
    }

    public function index($limit, $page, $orderBy = 'created', $direction = 'desc'): stdClass
    {
        $validColumns = ['created', 'title', 'price'];
        $validDirections = ['asc', 'desc'];

        if (!in_array($orderBy, $validColumns) || !in_array($direction, $validDirections)) {
            throw new InvalidArgumentException('Invalid orderBy or direction.');
        }

        $query = "SELECT product.id, product.name AS title, category.id AS category_name, product.price, created_at AS created
                        FROM product
                        INNER JOIN category
                        ON product.category_id = category.id
                        ORDER BY $orderBy $direction";

        $paginator = new Paginator($this->db->conn, $query);
        return $paginator->getData($limit, $page);
    }
}
