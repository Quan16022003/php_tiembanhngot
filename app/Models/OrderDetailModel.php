<?php

namespace App\Models;

use Core\Database;

class OrderDetailModel
{
    private $id;
    private $orderId;
    private $productId;
    private $quantity;
    private $price;

    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->orderId = $data['order_id'] ?? null;
        $this->productId = $data['product_id'] ?? null;
        $this->quantity = $data['quantity'] ?? null;
        $this->price = $data['price'] ?? null;
    }

    public function save() {
        $db = Database::getConnection();

        if ($this->id) {
            $this->updateOrderDetail($db);
        } else {
            $this->createOrderDetail($db);
        }
    }

    public function createOrderDetail($db) {
        $sql = "INSERT INTO `order_detail`(`order_id`, `product_id`, `quantity`, `price`) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iiii", $this->orderId, $this->productId, $this->quantity, $this->price);
        $success = $stmt->execute();
        $this->id = $stmt->insert_id;
        $stmt->close();
    }

    public function updateOrderDetail($db) {
        $sql = "UPDATE `order_detail` SET `order_id`=?, `product_id`=?, `quantity`=?, `price`=? WHERE `id`=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iiiii", $this->orderId, $this->productId, $this->quantity, $this->price, $this->id);
        $success = $stmt->execute();
        $stmt->close();
    }

    public static function getAllOrderDetails() {
        $db = Database::getConnection();
        $sql = "SELECT o.*, p.name FROM `order_detail` o INNER JOIN product p ON o.product_id = p.id;";
        $result = $db->query($sql);
        $orderDetails = [];
        while ($row = $result->fetch_assoc()) {
            $orderDetails[] = $row;
        }
        return $orderDetails;
    }

    public static function getOrderDetailById($id) {
        $db = Database::getConnection();
        $sql = "SELECT o.*, p.name FROM `order_detail` o INNER JOIN product p ON o.product_id = p.id WHERE `id`=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    public static function getAllOrderDetailByOrderId($orderId) {
        $db = Database::getConnection();
        $sql = "SELECT o.*, p.name, o.quantity*o.price as total FROM `order_detail` o INNER JOIN product p ON o.product_id = p.id WHERE `order_id`=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $orderDetails = [];
        while ($row = $result->fetch_assoc()) {
            $orderDetails[] = $row;
        }
        return $orderDetails;
    }


    public static function deleteOrderDetailById($id) {
        $db = Database::getConnection();
        $sql = "DELETE FROM `order_detail` WHERE `id`=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}