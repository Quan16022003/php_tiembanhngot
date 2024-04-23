<?php

namespace App\Models;

use Core\Database;

class AdminCustomerModel
{
    private ?Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function index(): false|array
    {
        $sql = "SELECT * FROM customer";
        $result = $this->db->conn->query($sql);
        if (!$result) {
            echo "List is empty!" . $this->db->conn->error;
            return false;
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insert($customerId, $customerCategoryId, $customerName, $customerPrice, $customerQuantity): bool
    {
        // Kiểm tra xem sản phẩm có tồn tại không
        $existingProduct = $this->getProductByID($customerId);

        if ($existingProduct) {
            // Nếu sản phẩm đã tồn tại, cập nhật số lượng
            $newQuantity = $existingProduct['stock'] + $customerQuantity;
            $sql = "UPDATE customer SET stock = ? WHERE id = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("is", $newQuantity, $customerId);
            $success = $stmt->execute();
            $stmt->close();
        } else {
            // Nếu sản phẩm chưa tồn tại, thực hiện thêm mới
            $sql = "INSERT INTO customer (id, category_id, name, price, stock) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("sssss", $customerId, $customerCategoryId, $customerName, $customerPrice, $customerQuantity);
            $success = $stmt->execute();
            $stmt->close();
        }

        return $success;
    }

    public function create($customerId, $customerCategoryId, $customerName, $customerPrice, $customerContent): bool
    {
        $existingProduct = $this->getProductByID($customerId);

        if ($existingProduct) {
            echo "ID has exist!";
        } else {
            // Nếu sản phẩm chưa tồn tại, thực hiện thêm mới
            $sql = "INSERT INTO customer (id, category_id, name, price, content) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("sssss", $customerId, $customerCategoryId, $customerName, $customerPrice, $customerContent);
            $success = $stmt->execute();
            $stmt->close();
        }
        return $success;
    }

    public function getById($customerID): false|array|null
    {
        $sql = "SELECT * FROM customer WHERE id = ?";
        if ($stmt = $this->db->conn->prepare($sql)) {
            $stmt->bind_param("s", $customerID);
            if ($stmt->execute()) {
                $result = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                return $result;
            } else {
                error_log("Failed to execute SQL statement: " . $stmt->error);
                $stmt->close();
                return false;
            }
        } else {
            error_log("Failed to prepare SQL statement: " . $this->db->conn->error);
            return false;
        }
    }

    public function delete($customerID): bool
    {
        $sql = "DELETE FROM customer WHERE id = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("s", $customerID);
        $success = $stmt->execute();

        if (!$success) {
            error_log("SQL error: " . $stmt->error);
            return false;
        }

        $stmt->close();
        return true;
    }

    public function update($customerId, $customerCategoryId, $customerName, $customerContent, $customerImage, $customerPrice, $Customertock): bool
    {
        $sql = "UPDATE customer SET name = ?, content = ?, image_link = ?, price = ?, stock = ?, category_id = ? WHERE id = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("sssiiis", $customerName, $customerContent, $customerImage, $customerPrice, $Customertock, $customerCategoryId, $customerId);
        $success = $stmt->execute();
        if (!$success) {
            error_log("SQL error: " . $stmt->error);
            return false;
        }
        $stmt->close();
        return true;
    }


    public function search($searchText): array
    {
        $sql = "SELECT * FROM customer WHERE ";
        $sql .= "id LIKE ? OR ";
        $sql .= "category_id LIKE ? OR ";
        $sql .= "name LIKE ? OR ";
        $sql .= "content LIKE ? OR ";
        $sql .= "price LIKE ? OR ";
        $sql .= "stock LIKE ?";
        $stmt = $this->db->conn->prepare($sql);
        $searchText = "%$searchText%"; // Thêm dấu wildcards để tìm kiếm từ mô tả
        $stmt->bind_param("ssssss", $searchText, $searchText, $searchText, $searchText, $searchText, $searchText);
        $stmt->execute();

        // Trả về kết quả
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getCustomer($offset, $limit): array
    {
        $sql = "SELECT * FROM customer LIMIT ?, ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function getTotalCustomer()
    {
        $sql = "SELECT COUNT(*) as total FROM customer";
        $result = $this->db->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}