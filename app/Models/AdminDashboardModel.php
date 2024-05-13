<?php

namespace App\Models;

use Core\Database;

class AdminDashboardModel
{
    private ?Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getTotalSoldProductsByType($startDate = null, $endDate = null): array
    {
        $sql = "SELECT p.id AS product_id, p.name AS product_name, SUM(id.quantity) AS total_sold 
            FROM invoice_detail id
            INNER JOIN invoice i ON id.invoice_id = i.id
            INNER JOIN product p ON id.product_id = p.id";
        if ($startDate && $endDate) {
            $sql .= " WHERE i.date BETWEEN ? AND ?";
        }
        $sql .= " GROUP BY id.product_id";

        $stmt = $this->db->conn->prepare($sql);
        // Nếu có startDate và endDate, bind các tham số
        if ($startDate !== null && $endDate !== null) {
            $stmt->bind_param("ss", $startDate, $endDate);
        }
        $stmt->execute();
        $result = $stmt->get_result();
//        $result = $this->db->conn->query($sql);

        $totalSoldByType = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $totalSoldByType[$row['product_id']] = [
                    'name' => $row['product_name'],
                    'total_sold' => (int)$row['total_sold']
                ];
            }
        }
        return $totalSoldByType;
    }

    function getYearsFromDatabase()
    {
        $query = "SELECT DISTINCT YEAR(date) AS year FROM invoice ORDER BY year DESC";
        $result = $this->db->conn->query($query);

        $years = array();

        // Lặp qua các kết quả và thêm năm vào mảng
        while ($row = $result->fetch_assoc()) {
            $years[] = $row['year'];
        }
//        echo "console.log('Years:', " . json_encode($years) .
        return $years;
    }


}