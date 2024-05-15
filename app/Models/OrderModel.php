<?php

namespace App\Models;

use Core\Database;

class OrderModel
{
    private $id;
    private $orderDate;
    private $totalPrice;
    private $customerId;
    private $address1;
    private $address2;
    private $phoneNumber;
    private $createdAt;

    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->orderDate = $data['order_date'] ?? null;
        $this->totalPrice = $data['total_price'] ?? null;
        $this->customerId = $data['user_id'] ?? null;
        $this->address1 = $data['address1'] ?? null;
        $this->address2 = $data['address2'] ?? null;
        $this->phoneNumber = $data['phone_number'] ?? null;
        $this->createdAt = $data['created_at'] ?? null;
    }

    public function save() {
        $db = Database::getConnection();

        if ($this->id) {
            $this->updateOrder($db);
        } else {
            $this->createOrder($db);
        }
    }

    public function createOrder($db) {
        $sql = "INSERT INTO `order`(`order_date`, `total_price`, `customer_id`, `address1`, `address2`, `phone_number`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("siiisss", $this->orderDate, $this->totalPrice, $this->customerId, $this->address1, $this->address2, $this->phoneNumber, $this->createdAt);
        $success = $stmt->execute();
        $this->id = $stmt->insert_id;
        $stmt->close();
    }

    public function updateOrder($db) {
        $sql = "UPDATE `order` SET `order_date`=?, `total_price`=?, `customer_id`=?, `address1`=?, `address2`=?, `phone_number`=?, `created_at`=? WHERE `id`=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("siiisssi", $this->orderDate, $this->totalPrice, $this->customerId, $this->address1, $this->address2, $this->phoneNumber, $this->createdAt, $this->id);
        $success = $stmt->execute();
        $stmt->close();
    }

    public static function getAllOrders() {
        $db = Database::getConnection();
        $sql = "SELECT o.*, u.name
                FROM `order` o
                INNER JOIN `users` u ON u.id = o.user_id";
        $result = $db->query($sql);
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    }

    public static function getOrderById($id) {
        $db = Database::getConnection();
        $sql = "SELECT o.*, u.name
                FROM `order` o
                INNER JOIN `users` u ON u.id = o.user_id
                WHERE o.`id`=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    public static function getAllStatusOrder() {
        $db = Database::getConnection();
        $sql = "SELECT * FROM `order_status`";
        $result = $db->query($sql);
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[$row['id']] = $row;
        }
        return $orders;
    }

    public static function updateOrderStatus($id, $status) {
        $db = Database::getConnection();
        $sql = "UPDATE `order` SET `status`=? WHERE `id`=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $status, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public static function getAllOrdersByStatus($status) {
        $db = Database::getConnection();
        $sql = "SELECT o.*, u.name 
            FROM `order` o
            INNER JOIN `users` u ON u.id = o.user_id
            WHERE o.status = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $status);
        $stmt->execute();

        $orders = [];
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    }
}