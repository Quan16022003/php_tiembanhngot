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

    /**
     * Lấy tổng số sản phẩm đã bán theo loại trong khoảng thời gian và loại sản phẩm cụ thể
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @param int|null $categoryId
     * @return array
     */
    public function getTotalSoldProductsByType(string $startDate = null, string $endDate = null, int $categoryId = null): array
    {
        $sql = "SELECT p.id AS product_id, p.name AS product_name, SUM(od.quantity) AS total_sold, p.price AS product_price 
                FROM order_detail od
                INNER JOIN `order` o ON od.order_id = o.id
                INNER JOIN product p ON od.product_id = p.id";

        $conditions = [];
        $params = [];
        $types = '';

        if ($startDate && $endDate) {
            $conditions[] = "o.created_at BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
            $types .= 'ss';
        }

        if ($categoryId) {
            $conditions[] = "p.category_id = ?";
            $params[] = $categoryId;
            $types .= 'i';
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= " GROUP BY od.product_id";

        $stmt = $this->db->conn->prepare($sql);

        if ($types) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $totalSoldByType = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $totalSoldByType[$row['product_id']] = [
                    'price' => (int)$row['product_price'],
                    'name' => $row['product_name'],
                    'total_sold' => (int)$row['total_sold']
                ];
            }
        }

        $stmt->close(); // Đóng statement sau khi sử dụng
        return $totalSoldByType;
    }

    /**
     * Lấy các năm có dữ liệu từ cơ sở dữ liệu
     *
     * @return array
     */
    public function getYearsFromDatabase(): array
    {
        $query = "SELECT DISTINCT YEAR(created_at) AS year FROM `order` ORDER BY year DESC";
        $result = $this->db->conn->query($query);

        $years = [];
        while ($row = $result->fetch_assoc()) {
            $years[] = $row['year'];
        }

        return $years;
    }
}
