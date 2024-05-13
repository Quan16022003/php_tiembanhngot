<?php

namespace Core;


use mysqli;

class Database
{
    private static ?Database $instance = null;
    public ?mysqli $conn = NULL;
    private string $host = 'localhost';
    private string $dbName = 'cuahangbanbanh';
    private string $user = 'root';
    private string $password = 'namdt2003';

    // Hàm kết nối CSDL
    private function __construct()
    {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbName);

        if ($this->conn->connect_error) {
            echo 'Failed: ' . $this->conn->connect_error;
            die();
        }
        $this->conn->set_charset("utf8");
    }

    // Hàm đóng kết nối CSDL
    public function closeDatabase(): void
    {
        $this->conn?->close();
    }

    // Phương thức tạo một đối tượng Singleton
    public static function getInstance(): ?Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public static function getConnection(): mysqli|null
    {
        return self::getInstance()->conn;
    }

    public function error(): bool
    {
        if ($this->conn)
            return $this->conn->error;
        else
            return false;
    }

    public function insert($table = '', $data = []): \mysqli_result|bool
    {
        $keys = '';
        $values = '';
        foreach ($data as $key => $value) {
            $keys .= ',' . $key;
            $values .= ',"' . $this->conn->real_escape_string($value) . '"';
        }
        $sql = 'INSERT INTO ' . $table . '(' . trim($keys, ',') . ') VALUES (' . trim($values, ',') . ')';
        return $this->conn->query($sql);
    }

    public function update($table = '', $data = [], $id = ''): \mysqli_result|bool
    {
        $content = '';
        foreach ($data as $key => $value) {
            $content .= ',' . $key . '="' . $this->conn->real_escape_string($value) . '"';
        }
        $sql = 'UPDATE ' . $table . ' SET ' . trim($content, ',') . ' WHERE id = ' . $id;
        return $this->conn->query($sql);
    }

    public function delete($table = '', $id = ''): \mysqli_result|bool
    {
        $sql = 'DELETE FROM ' . $table . ' WHERE id = ' . $id;
        return $this->conn->query($sql);
    }

    public function selectAll($table): array
    {
        $sql = "SELECT * FROM `$table`";
        $result = $this->conn->query($sql);
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
    public function selectById($table = '', $id = ''): ?array
    {
    $sql = "SELECT * FROM `$table` WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row;
    }

    public function insertById($customer_id, $product_id, $quantity = 1): bool
    {
        $sql = "INSERT INTO `cart` (customer_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('iii', $customer_id, $product_id, $quantity);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    
    public function getAllcart()
    {
        try {
            $sql = "SELECT c.quantity, p.name AS product_name, p.price
                    FROM cart c
                    JOIN product p ON c.product_id = p.id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result(); // Lấy kết quả dưới dạng một đối tượng kết quả có thể sử dụng được
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $e) {
            // Xử lý ngoại lệ nếu có lỗi xảy ra
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }
    

}
