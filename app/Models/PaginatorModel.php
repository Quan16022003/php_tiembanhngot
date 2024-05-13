<?php

namespace App\Models;

use Core\Database;

class AdminProductsModel
{
    // Các phương thức khác của model ở đây

    public function getProducts($offset, $limit)
    {
        // Thực hiện truy vấn để lấy danh sách sản phẩm phân trang từ cơ sở dữ liệu
        // Sử dụng $offset và $limit để phân trang
        $sql = "SELECT * FROM products LIMIT ?, ?";
        $statement = $this->db->prepare($sql);
        $statement->bind_param("ii", $offset, $limit);
        $statement->execute();
        $result = $statement->get_result();
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }
}
