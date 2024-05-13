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

    public function create($productId, $productCategoryId, $productName, $productPrice, $productContent, $productImage): bool
    {
        echo "console.log('Data inserted into database:')";
        echo "console.log('Product ID:', '$productId')";
        echo "console.log('Product Category ID:', '$productCategoryId')";
        echo "console.log('Product Name:', '$productName')";
        echo "console.log('Product Price:', '$productPrice')";
        echo "console.log('Product Content:', '$productContent')";
        echo "console.log('Product Image:', '$productImage')";
        $existingProduct = $this->getProductByID($productId);

        if ($existingProduct) {
            echo "ID đã tồn tại!";
            return false;
        } else {
            // Nếu sản phẩm chưa tồn tại, thực hiện thêm mới
            $sql = "INSERT INTO product (id, category_id, name, price, content, image_link) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("sissss", $productId, $productCategoryId, $productName, $productPrice, $productContent, $productImage);
            $success = $stmt->execute();
            $stmt->close();
            return $success;
        }
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

    public function updateProduct($productId, $productCategoryId, $productName, $productContent, $productImage, $productPrice, $productStock): bool
    {
        // Lấy thông tin sản phẩm trước đó từ cơ sở dữ liệu
        $previousProduct = $this->getProductById($productId);

        // Kiểm tra xem $previousProduct có giá trị không
        if ($previousProduct !== false && $previousProduct !== null) {
            // Lấy đường dẫn hình ảnh của sản phẩm trước đó
            $oldImagePath = $previousProduct['image_link'];

            // Kiểm tra xem người dùng đã tải lên hình ảnh mới chưa
            if ($productImage && isset($productImage['error']) && $productImage['error'] == 0) {
                $img_name = $productImage['name'];
                $img_size = $productImage['size'];
                $tmp_name = $productImage['tmp_name'];

                // Tiếp tục xử lý hình ảnh và lưu trữ

                // Chuyển biến $productImage thành chuỗi
                $productImage = $new_img_name;
                if ($oldImagePath && file_exists($oldImagePath)) {
                    unlink($oldImagePath); // Xoá hình ảnh cũ
                }
            } else {
                // Nếu không có hình ảnh mới, giữ nguyên đường dẫn cũ
                $productImage = $oldImagePath;
            }

            // Cập nhật sản phẩm trong cơ sở dữ liệu
            $sql = "UPDATE product SET name = ?, content = ?, image_link = ?, price = ?, stock = ?, category_id = ? WHERE id = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("sssiiis", $productName, $productContent, $productImage, $productPrice, $productStock, $productCategoryId, $productId);

            // Thực thi câu lệnh SQL và kiểm tra kết quả
            $success = $stmt->execute();
            if ($success) {
                // Hiển thị thông tin sản phẩm trước và sau khi cập nhật
                // ...

                $stmt->close();
                return true;
            } else {
                echo "Cập nhật sản phẩm thất bại: " . $stmt->error;
            }
        } else {
            echo "Không tìm thấy sản phẩm có ID là $productId";
        }

        return false;
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

    public function generateProductId()
    {
        // Query to get the last product ID
        $sql = "SELECT id FROM product ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $lastId = $result->fetch_assoc()['id'];

        // Extract the numeric part of the ID
        $number = intval(substr($lastId, 2));

        // Increment the number
        $number++;

        // Generate the new ID
        $newId = 'SP' . str_pad($number, 4, '0', STR_PAD_LEFT);

        return $newId;
    }

    public function saveImageLink($productId, $imageLink)
    {
        $sql = "UPDATE product SET image_link = :image_link WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':image_link', $imageLink);
        $stmt->bindParam(':id', $productId);
        return $stmt->execute();
    }
}