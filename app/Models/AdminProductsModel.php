<?php

namespace App\Models;

use Core\Database;

class AdminProductsModel
{
    private ?Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function index(): false|array
    {
        $sql = "SELECT * FROM product";
        $result = $this->db->conn->query($sql);
        if (!$result) {
            echo "List is empty!" . $this->db->conn->error;
            return false;
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insert($productId, $productCategoryId, $productName, $productPrice, $productQuantity): bool
    {
        // Kiểm tra xem sản phẩm có tồn tại không
        $existingProduct = $this->getProductByID($productId);

        if ($existingProduct) {
            // Nếu sản phẩm đã tồn tại, cập nhật số lượng
            $newQuantity = $existingProduct['stock'] + $productQuantity;
            $sql = "UPDATE product SET stock = ? WHERE id = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("is", $newQuantity, $productId);
            $success = $stmt->execute();
            $stmt->close();
        } else {
            // Nếu sản phẩm chưa tồn tại, thực hiện thêm mới
            $sql = "INSERT INTO product (id, category_id, name, price, stock) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("sssss", $productId, $productCategoryId, $productName, $productPrice, $productQuantity);
            $success = $stmt->execute();
            $stmt->close();
        }

        return $success;
    }

    public function getProductByID($productID): false|array|null
    {
        $sql = "SELECT * FROM product WHERE id = ?";
        if ($stmt = $this->db->conn->prepare($sql)) {
            $stmt->bind_param("s", $productID);
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

    public function delete($productID): bool
    {
        $sql = "DELETE FROM product WHERE id = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("s", $productID);
        $success = $stmt->execute();

        if (!$success) {
            error_log("SQL error: " . $stmt->error);
            return false;
        }

        $stmt->close();
        return true;
    }

    public function update($productId, $productCategoryId, $productContent, $productName, $productPrice, $productStock, $productImage): bool
    {
        $sql = "UPDATE product SET name = ?,content=?,image_link=?, price = ?, stock = ?, category_id = ? WHERE id = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("sssiiis", $productName, $productContent, $productImage, $productPrice, $productStock, $productCategoryId, $productId);
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
        $sql = "SELECT * FROM product WHERE ";
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

}