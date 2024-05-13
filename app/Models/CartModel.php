<?php

namespace App\Models;

use Core\Database;

class CartModel
{
    private ?Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAllCart(int $userId)
    {
        $sql = "SELECT cart.id, product_id, name, content, price, quantity, image_link
                FROM cart 
                INNER JOIN product ON product.id = cart.product_id
                WHERE user_id = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart = array();
        while ($row = $result->fetch_assoc()) {
            $cart[$row['product_id']] = $row;
        }
        $stmt->close();
        return $cart;
    }

    public function addCart($userId, $productId, $quantity)
    {
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?,?,?)";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("iii", $userId, $productId, $quantity);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows > 0;
    }

    public function updateCart($userId, $productId, $quantity)
    {
        $sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("iii", $quantity, $userId, $productId);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows > 0;
    }

    public function getCartItem(mixed $userId, mixed $productId)
    {
        $sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function deleteCart(mixed $cartId): bool
    {
        $sql = "DELETE FROM cart WHERE id = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("i", $cartId);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows > 0;
    }

    public function getTotalQuantity($userId)
    {
        $sql = "SELECT IFNULL(SUM(quantity), 0) AS total_quantity FROM (SELECT quantity FROM cart WHERE user_id = ?) AS subquery";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result['total_quantity'];
    }
}
