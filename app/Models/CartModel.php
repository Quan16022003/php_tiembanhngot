<?php

namespace App\Models;

use Core\Database;

class CartModel
{
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
}
