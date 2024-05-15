<?php

namespace App\Models;

use Core\Database;

class OrderModel
{
    private $id;
    private $orderDate;
    private $totalPrice;
    private $userId;
    private $address1;
    private $address2;
    private $phoneNumber;
    private $createdAt;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->orderDate = $data['order_date'] ?? null;
        $this->totalPrice = $data['total_price'] ?? null;
        $this->userId = $data['user_id'] ?? null;
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

    public function createOrder($db)
    {
        $sql = "INSERT INTO `order`(`total_price`, `user_id`, `address1`, `address2`, `phone_number`) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iiiss", $this->totalPrice, $this->userId, $this->address1, $this->address2, $this->phoneNumber);
        if ($stmt->execute()) {
            $this->id = $stmt->insert_id;
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function updateOrder($db)
    {
        $sql = "UPDATE `order` SET `order_date`=?, `total_price`=?, `user_id`=?, `address1`=?, `address2`=?, `phone_number`=?, `created_at`=? WHERE `id`=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("siiisssi", $this->orderDate, $this->totalPrice, $this->userId, $this->address1, $this->address2, $this->phoneNumber, $this->createdAt, $this->id);
        $success = $stmt->execute();
        $stmt->close();
    }

    public static function getAllOrders()
    {
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

    public function getOrderDate(): mixed
    {
        return $this->orderDate;
    }

    public function setOrderDate(mixed $orderDate): void
    {
        $this->orderDate = $orderDate;
    }

    public function getTotalPrice(): mixed
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(mixed $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    public function getUserId(): mixed
    {
        return $this->userId;
    }

    public function setUserId(mixed $userId): void
    {
        $this->userId = $userId;
    }

    public function getAddress1(): mixed
    {
        return $this->address1;
    }

    public function setAddress1(mixed $address1): void
    {
        $this->address1 = $address1;
    }

    public function getAddress2(): mixed
    {
        return $this->address2;
    }

    public function setAddress2(mixed $address2): void
    {
        $this->address2 = $address2;
    }

    public function getPhoneNumber(): mixed
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(mixed $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getCreatedAt(): mixed
    {
        return $this->createdAt;
    }

    public function setCreatedAt(mixed $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


}