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

    public function create($productCategoryId, $productName, $productPrice, $productContent, $productImage, $productSupplierId): bool
    {
        // Nếu sản phẩm chưa tồn tại, thực hiện thêm mới
        $sql = "INSERT INTO product (category_id, name, price, content, image_link, supplier_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("issssi", $productId, $productCategoryId, $productName, $productPrice, $productContent, $productImage, $productSupplierId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }


    public function getProductById($productId): false|array|null
    {
        $sql = "SELECT * FROM product WHERE id = ?";
        if ($stmt = $this->db->conn->prepare($sql)) {
            $stmt->bind_param("i", $productId);
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

    public function updateProduct($productId, $productCategoryId, $productName, $productContent, $productPrice, $productStock, $productImage, $productSupplier)
    {
        $sql = "UPDATE product SET name = ?, content = ?, image_link = ?, price = ?, stock = ?, category_id = ?, supplier_id = ? WHERE id = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("sssiiisi", $productName, $productContent, $productImage, $productPrice, $productStock, $productCategoryId, $productSupplier, $productId);
        $success = $stmt->execute();
        $stmt->close();
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

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getProducts($offset, $limit): array
    {
        $sql = "SELECT * FROM product LIMIT ?, ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function getTotalProducts()
    {
        $sql = "SELECT COUNT(*) as total FROM product";
        $result = $this->db->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getAllProducts()
    {
        $sql = "SELECT * FROM product";
        $result = $this->db->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteImageByProductId($productId): bool
    {
        // Cập nhật cột image_link thành null cho sản phẩm có productId tương ứng
        $sql = "UPDATE product SET image_link = NULL WHERE id = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("s", $productId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function saveImageLink($productId, $imageLink)
    {
        $sql = "UPDATE product SET image_link = :image_link WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':image_link', $imageLink);
        $stmt->bindParam(':id', $productId);
        return $stmt->execute();
    }

    public function insertFromCsv($filePath)
    {
        $file = fopen($filePath, "r");

        if ($file) {
            // Bỏ qua dòng tiêu đề
            fgetcsv($file);

            while (($data = fgetcsv($file)) !== false) {
                // Phân tích dữ liệu từ mỗi dòng
                $id = $data[0];
                $name = $data[1];
                $category_id = $data[2];
                $price = $data[3];
                $discount = $data[4];
                $stock = $data[5];
                $view = $data[6];
                $supplier_id = $data[7];

                // Chèn dữ liệu vào cơ sở dữ liệu
                $sql = "INSERT INTO product (id, category_id, name, price, discount, stock, view, supplier_id) 
                        VALUES ('$id', '$name', '$category_id', '$price', '$discount', '$stock', '$view', '$supplier_id')";

                if ($this->db->conn->query($sql) !== true) {
                    echo "Error: " . $sql . "<br>" . $this->db->error;
                }
            }

            fclose($file);
        } else {
            echo "Error opening file";
        }
    }

    public function exportToCSV($data)
    {
        $csvData = '';
        $csvData .= "ID,Tên sản phẩm,Loại,Giá,Giảm giá,Tồn kho,Lượt xem\n";
        foreach ($data as $product) {
            $csvData .= "{$product['id']},{$product['name']},{$product['category_id']},{$product['price']},{$product['discount']},{$product['stock']},{$product['view']}\n";
        }
        return $csvData;
    }

    public function reduceStock($productId, $quantity)
    {
        $db = Database::getConnection();
        $sql = "UPDATE `product` SET `stock` = `stock` - ? WHERE `id` = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $quantity, $productId);
        $stmt->execute();
        $stmt->close();
    }
}