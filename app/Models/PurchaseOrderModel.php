<?php

namespace App\Models;

use Core\Database;

class PurchaseOrderModel
{
    private $id;
    private $supplierCompanyName;
    private $supplierContactName;
    private $supplierEmail;
    private $supplierPhone;
    private $supplierAddress;
    private $orderDate;
    private $deliveryDate;
    private $paymentMethod;
    private $shippingMethod;
    private $shippingTerms;
    private $shippingAddress;
    private $shippingFee;
    private $tax;
    private $notes;
    private $status;

    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->supplierCompanyName = $data['supplier_company_name'] ?? null;
        $this->supplierContactName = $data['supplier_contact_name'] ?? null;
        $this->supplierEmail = $data['supplier_email'] ?? null;
        $this->supplierPhone = $data['supplier_phone'] ?? null;
        $this->supplierAddress = $data['supplier_address'] ?? null;
        $this->orderDate = $data['order_date'] ?? null;
        $this->deliveryDate = $data['delivery_date'] ?? null;
        $this->paymentMethod = $data['payment_method'] ?? null;
        $this->shippingMethod = $data['shipping_method'] ?? null;
        $this->shippingTerms = $data['shipping_terms'] ?? null;
        $this->shippingAddress = $data['shipping_address'] ?? null;
        $this->shippingFee = $data['shipping_fee'] ?? null;
        $this->tax = $data['tax'] ?? null;
        $this->notes = $data['notes'] ?? null;
        $this->status = $data['status'] ?? null;
    }

    public function save() {
        $db = Database::getConnection();

        if ($this->id) {
            $this->updatePurchaseOrder($db);
        } else {
            $this->createPurchaseOrder($db);
        }
    }

    public function createPurchaseOrder($db) {
        $sql = "INSERT INTO `purchase_order`(`supplier_company_name`, `supplier_contact_name`, `supplier_email`, `supplier_phone`, `supplier_address`, `delivery_date`, `payment_method`, `shipping_method`, `shipping_terms`, `shipping_address`, `shipping_fee`, `tax`, `notes`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssssssssssiis", $this->supplierCompanyName, $this->supplierContactName, $this->supplierEmail, $this->supplierPhone, $this->supplierAddress, $this->deliveryDate, $this->paymentMethod, $this->shippingMethod, $this->shippingTerms, $this->shippingAddress, $this->shippingFee, $this->tax, $this->notes);
        $success = $stmt->execute();
        $this->id = $stmt->insert_id;
        $stmt->close();
    }

    public function updatePurchaseOrder($db) {
        $sql = "UPDATE purchase_order SET supplier_company_name=?, supplier_contact_name=?, supplier_email=?, supplier_phone=?, supplier_address=?, delivery_date=?, payment_method=?, shipping_method=?, shipping_terms=?, shipping_address=?, shipping_fee=?, tax=?, notes=? WHERE id=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssssssssssiisi", $this->supplierCompanyName, $this->supplierContactName, $this->supplierEmail, $this->supplierPhone, $this->supplierAddress, $this->deliveryDate, $this->paymentMethod, $this->shippingMethod, $this->shippingTerms, $this->shippingAddress, $this->shippingFee, $this->tax, $this->notes, $this->id);
        $success = $stmt->execute();
        $stmt->close();
    }

    public static function getAllPurchaseOrders() {
        $db = Database::getConnection();
        $sql = "SELECT po.id, po.supplier_company_name as supplier, pos.name as status_name, pos.color as status_color, SUM(pod.quantity) as received,SUM(total_price)*po.tax + po.shipping_fee as total, po.delivery_date \n"

            . "FROM purchase_order po\n"

            . "LEFT JOIN purchase_order_detail pod ON pod.po_id = po.id\n"

            . "INNER JOIN purchase_order_status pos ON pos.id = po.status\n"

            . "GROUP BY po.id;";
        $result = $db->query($sql);
        $purchaseOrders = [];
        while ($row = $result->fetch_assoc()) {
            $purchaseOrders[] = $row;
        }
        return $purchaseOrders;
    }

    public static function getAllPurchaseOrdersByStatus($status) {
        $db = Database::getConnection();
        $sql = "SELECT po.id, po.supplier_company_name as supplier, pos.name as status_name, pos.color as status_color, SUM(pod.quantity) as received,SUM(total_price)*po.tax + po.shipping_fee as total, po.delivery_date \n"

            . "FROM purchase_order po\n"

            . "LEFT JOIN purchase_order_detail pod ON pod.po_id = po.id\n"

            . "INNER JOIN purchase_order_status pos ON pos.id = po.status\n"

            . "WHERE pos.code = ?\n"

            . "GROUP BY po.id;";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $purchaseOrders = [];
        while ($row = $result->fetch_assoc()) {
            $purchaseOrders[] = $row;
        }
        return $purchaseOrders;
    }

    public static function getPurchaseOrderById($id) {
        $db = Database::getConnection();
        $sql = "SELECT po.*, pos.name AS status_name, pos.color AS status_color, \n"

            . "    SUM(pod.total_price) AS sub_total, \n"

            . "    FLOOR(SUM(pod.total_price) * po.tax / 100) AS tax_price, \n"

            . "    SUM(pod.total_price) + FLOOR(SUM(pod.total_price) * po.tax / 100) + po.shipping_fee AS total\n"

            . "FROM purchase_order po\n"

            . "LEFT JOIN purchase_order_detail pod ON pod.po_id = po.id\n"

            . "INNER JOIN purchase_order_status pos ON po.status = pos.id\n"

            . "WHERE po.id = ?\n"

            . "GROUP BY po.id;";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    public static function getAllPOStatus() {
        $db = Database::getConnection();
        $sql = "SELECT * FROM purchase_order_status";
        $result = $db->query($sql);
        $status = [];
        while ($row = $result->fetch_assoc()) {
            $status[] = $row;
        }
        return $status;
    }

    public static function updatePOStatus($id, $status) {
        $db = Database::getConnection();
        $sql = "UPDATE purchase_order SET status =? WHERE id =?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $status, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function getId(): mixed
    {
        return $this->id;
    }

    public function setId(mixed $id): void
    {
        $this->id = $id;
    }

    public function getSupplierCompanyName(): mixed
    {
        return $this->supplierCompanyName;
    }

    public function setSupplierCompanyName(mixed $supplierCompanyName): void
    {
        $this->supplierCompanyName = $supplierCompanyName;
    }

    public function getSupplierContactName(): mixed
    {
        return $this->supplierContactName;
    }

    public function setSupplierContactName(mixed $supplierContactName): void
    {
        $this->supplierContactName = $supplierContactName;
    }

    public function getSupplierEmail(): mixed
    {
        return $this->supplierEmail;
    }

    public function setSupplierEmail(mixed $supplierEmail): void
    {
        $this->supplierEmail = $supplierEmail;
    }

    public function getSupplierPhone(): mixed
    {
        return $this->supplierPhone;
    }

    public function setSupplierPhone(mixed $supplierPhone): void
    {
        $this->supplierPhone = $supplierPhone;
    }

    public function getSupplierAddress(): mixed
    {
        return $this->supplierAddress;
    }

    public function setSupplierAddress(mixed $supplierAddress): void
    {
        $this->supplierAddress = $supplierAddress;
    }

    public function getDeliveryDate(): mixed
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate(mixed $deliveryDate): void
    {
        $this->deliveryDate = $deliveryDate;
    }

    public function getPaymentMethod(): mixed
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(mixed $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getShippingMethod(): mixed
    {
        return $this->shippingMethod;
    }

    public function setShippingMethod(mixed $shippingMethod): void
    {
        $this->shippingMethod = $shippingMethod;
    }

    public function getShippingTerms(): mixed
    {
        return $this->shippingTerms;
    }

    public function setShippingTerms(mixed $shippingTerms): void
    {
        $this->shippingTerms = $shippingTerms;
    }

    public function getShippingAddress(): mixed
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(mixed $shippingAddress): void
    {
        $this->shippingAddress = $shippingAddress;
    }

    public function getShippingFee(): mixed
    {
        return $this->shippingFee;
    }

    public function setShippingFee(mixed $shippingFee): void
    {
        $this->shippingFee = $shippingFee;
    }

    public function getTax(): mixed
    {
        return $this->tax;
    }

    public function setTax(mixed $tax): void
    {
        $this->tax = $tax;
    }

    public function getNotes(): mixed
    {
        return $this->notes;
    }

    public function setNotes(mixed $notes): void
    {
        $this->notes = $notes;
    }

    public function getOrderDate(): mixed
    {
        return $this->orderDate;
    }

    public function setOrderDate(mixed $orderDate): void
    {
        $this->orderDate = $orderDate;
    }

    public function getStatus(): mixed
    {
        return $this->status;
    }

    public function setStatus(mixed $status): void
    {
        $this->status = $status;
    }
}