<?php

namespace App\Models;

use Core\Database;

class SupplierModel
{
    private $id;
    private $companyName;
    private $contactName;
    private $contactEmail;
    private $contactPhone;
    private $address;
    private $postalCode;
    private $country;

    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->companyName = $data['companyName'] ?? null;
        $this->contactName = $data['contactName'] ?? null;
        $this->contactEmail = $data['contactEmail'] ?? null;
        $this->contactPhone = $data['contactPhone'] ?? null;
        $this->address = $data['address'] ?? null; // Changed 'address1' to 'address'
        $this->postalCode = $data['postalCode'] ?? null;
        $this->country = $data['country'] ?? null;
    }

    public function save() {
        $db = Database::getConnection();

        if ($this->id) {
            $this->updateSupplier($db);
        } else {
            $this->createSupplier($db);
        }
    }

    public function createSupplier($db) {
        $sql = "INSERT INTO `supplier`(`company_name`, `contact_name`, `contact_email`, `contact_phone`, `address`, `postal_code`, `country`) VALUES (?,?,?,?,?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssssss", $this->companyName, $this->contactName, $this->contactEmail, $this->contactPhone, $this->address, $this->postalCode, $this->country);
        $success = $stmt->execute();
        $stmt->close();
    }

    public function updateSupplier($db) {
        $sql = "UPDATE supplier SET company_name=?, contact_name=?, contact_email=?, contact_phone=?, address=?, postal_code=?, country=? WHERE id=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssssssi", $this->companyName, $this->contactName, $this->contactEmail, $this->contactPhone, $this->address, $this->postalCode, $this->country, $this->id);
        $success = $stmt->execute();
        $stmt->close();
    }

    public static function getAllSuppliers() {
        $db = Database::getConnection();
        $sql = "SELECT * FROM supplier";
        $result = $db->query($sql);
        $suppliers = [];
        while ($row = $result->fetch_assoc()) {
            $suppliers[] = $row;
        }
        return $suppliers;
    }

    public static function getSupplierById($id) {
        $db = Database::getConnection();
        $sql = "SELECT * FROM supplier WHERE id=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }
}
