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

    public function getInvoiceDetailsByID($invoiceId): ?array
    {
        $sql = "SELECT invoice_detail.id, invoice_detail.invoice_id, invoice_detail.product_id, invoice_detail.quantity, product.name AS product_name, product.price
                FROM invoice_detail
                JOIN product ON invoice_detail.product_id = product.id
                WHERE invoice_detail.invoice_id = ?";
        if ($stmt = $this->db->conn->prepare($sql)) {
            $stmt->bind_param("s", $invoiceId);
            if ($stmt->execute()) {
                $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                return $result;
            } else {
                error_log("Failed to execute SQL statement: " . $stmt->error);
                $stmt->close();
                return null;
            }
        } else {
            error_log("Failed to prepare SQL statement: " . $this->db->conn->error);
            return null;
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
