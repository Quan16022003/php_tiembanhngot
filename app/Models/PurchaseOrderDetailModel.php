<?php

namespace App\Models;

use Core\Database;

class PurchaseOrderDetailModel
{
    private $id;
    private $poId;
    private $productId;
    private $quantity;
    private $unitPrice;
    private $totalPrice;

    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->poId = $data['po_id'] ?? null;
        $this->productId = $data['product_id'] ?? null;
        $this->quantity = $data['quantity'] ?? null;
        $this->unitPrice = $data['unit_price'] ?? null;
        $this->totalPrice = $data['total_price'] ?? null;
    }

    public function save() {
        $db = Database::getConnection();

        if ($this->id) {
            $this->updatePurchaseOrderDetail($db);
        } else {
            $this->createPurchaseOrderDetail($db);
        }
    }

    public function createPurchaseOrderDetail($db) {
        $sql = "INSERT INTO `purchase_order_detail`(`po_id`, `product_id`, `quantity`, `unit_price`, `total_price`) VALUES (?,?,?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iiiii", $this->poId, $this->productId, $this->quantity, $this->unitPrice, $this->totalPrice);
        $success = $stmt->execute();
        $this->id = $stmt->insert_id;
        $stmt->close();
    }

    public function updatePurchaseOrderDetail($db) {
        $sql = "UPDATE purchase_order_detail SET po_id=?, product_id=?, quantity=?, unit_price=?, total_price=? WHERE id=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iiiiii", $this->poId, $this->productId, $this->quantity, $this->unitPrice, $this->totalPrice, $this->id);
        $success = $stmt->execute();
        $stmt->close();
    }

    public static function getAllPurchaseOrderDetails() {
        $db = Database::getConnection();
        $sql = "SELECT * FROM purchase_order_detail";
        $result = $db->query($sql);
        $purchaseOrderDetails = [];
        while ($row = $result->fetch_assoc()) {
            $purchaseOrderDetails[] = $row;
        }
        return $purchaseOrderDetails;
    }

    public static function getPurchaseOrderDetailById($id) {
        $db = Database::getConnection();
        $sql = "SELECT * FROM purchase_order_detail WHERE id=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    public static function getAllPurchaseOrderDetailByPoID($poId) {
        $db = Database::getConnection();
        $sql = "SELECT prod.id, prod.name, pod.quantity, pod.unit_price, pod.total_price\n"

            . "FROM purchase_order_detail pod\n"

            . "INNER JOIN product prod ON prod.id = pod.product_id\n"

            . "WHERE po_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $poId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $purchaseOrderDetails = [];
        while ($row = $result->fetch_assoc()) {
            $purchaseOrderDetails[] = $row;
        }
        return $purchaseOrderDetails;
    }

    public static function deletePODByPOId($poid) {
        $db = Database::getConnection();
        $sql = "DELETE FROM purchase_order_detail WHERE po_id=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $poid);
        $stmt->execute();
        $stmt->close();
    }

    public function getId(): mixed
    {
        return $this->id;
    }

    public function setId(mixed $id): void
    {
        $this->id = $id;
    }

    public function getPoId(): mixed
    {
        return $this->poId;
    }

    public function setPoId(mixed $poId): void
    {
        $this->poId = $poId;
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
}
