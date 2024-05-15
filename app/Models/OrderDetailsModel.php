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

    public function createOrderDetail($db)
    {
        $sql = "INSERT INTO `order_detail`(`order_id`, `product_id`, `quantity`) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iii", $this->orderId, $this->productId, $this->quantity);
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

    public static function getOrderDetailsById($id)
    {
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

    private function getOrderDetailsDataFromCart($orderID, $userID)
    {
        $cartItems = new CartModel();
        $cartItems->getAllCart($userID);
        foreach ($cartItems as $item) {
            $detail = new OrderDetailsModel();
            $detail->setOrderID($orderID);
            $detail->setProductId($item['product_id']);
            $detail->setQuantity($item['quantity']);
            $detail->setUnitPrice($item['price']);
            $detail->save();
        }
    }

    public function setId(mixed $id): void
    {
        $this->id = $id;
    }

    public function getOrderId(): mixed
    {
        return $this->orderId;
    }

    public function setOrderId(mixed $odId): void
    {
        $this->orderId = $odId;
    }

    public function getProductId(): mixed
    {
        return $this->productId;
    }

    public function setProductId(mixed $productId): void
    {
        $this->productId = $productId;
    }

    public function getQuantity(): mixed
    {
        return $this->quantity;
    }

    public function setQuantity(mixed $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getUnitPrice(): mixed
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(mixed $unitPrice): void
    {
        $this->unitPrice = $unitPrice;
    }

    public function getTotalPrice(): mixed
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(mixed $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    public function getPrice(): mixed
    {
        return $this->price;
    }

    public function setPrice(mixed $price): void
    {
        $this->price = $price;
    }


}