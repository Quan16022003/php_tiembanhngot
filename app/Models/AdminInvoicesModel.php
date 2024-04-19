<?php

namespace App\Models;

use Core\Database;

class AdminInvoicesModel
{
    private ?Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function index(): false|array
    {
        $sql = "SELECT * FROM Invoice";
        $result = $this->db->conn->query($sql);
        if (!$result) {
            echo "List is empty!" . $this->db->conn->error;
            return false;
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insert($InvoiceId, $InvoiceCategoryId, $InvoiceName, $InvoicePrice, $InvoiceQuantity): bool
    {
        // Kiểm tra xem sản phẩm có tồn tại không
        $existingInvoice = $this->getInvoiceByID($InvoiceId);

        if ($existingInvoice) {
            // Nếu sản phẩm đã tồn tại, cập nhật số lượng
            $newQuantity = $existingInvoice['stock'] + $InvoiceQuantity;
            $sql = "UPDATE invoices SET stock = ? WHERE id = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("is", $newQuantity, $InvoiceId);
            $success = $stmt->execute();
            $stmt->close();
        } else {
            // Nếu sản phẩm chưa tồn tại, thực hiện thêm mới
            $sql = "INSERT INTO invoices (id, category_id, name, price, stock) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("sssss", $InvoiceId, $InvoiceCategoryId, $InvoiceName, $InvoicePrice, $InvoiceQuantity);
            $success = $stmt->execute();
            $stmt->close();
        }

        return $success;
    }

    public function getInvoiceByID($InvoiceID): false|array|null
    {
        $sql = "SELECT * FROM Invoice WHERE id = ?";
        if ($stmt = $this->db->conn->prepare($sql)) {
            $stmt->bind_param("s", $InvoiceID);
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

    public function delete($InvoiceID): bool
    {
        $sql = "DELETE FROM invoices WHERE id = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("s", $InvoiceID);
        $success = $stmt->execute();

        if (!$success) {
            error_log("SQL error: " . $stmt->error);
            return false;
        }

        $stmt->close();
        return true;
    }

    public function update($InvoiceId, $InvoiceCategoryId, $InvoiceContent, $InvoiceName, $InvoicePrice, $InvoiceStock, $InvoiceImage): bool
    {
        $sql = "UPDATE Invoice SET name = ?,content=?,image_link=?, price = ?, stock = ?, category_id = ? WHERE id = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("sssiiis", $InvoiceName, $InvoiceContent, $InvoiceImage, $InvoicePrice, $InvoiceStock, $InvoiceCategoryId, $InvoiceId);
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
        $sql = "SELECT * FROM invoices WHERE ";
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

    public function getInvoices($offset, $limit): array
    {
        $sql = "SELECT * FROM invoice LIMIT ?, ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function getTotalInvoices()
    {
        $sql = "SELECT COUNT(*) as total FROM invoice";
        $result = $this->db->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
