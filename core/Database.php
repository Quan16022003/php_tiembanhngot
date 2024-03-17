<?php

namespace Core;

use mysqli;
use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    public ?mysqli $conn = NULL;
    private string $server = 'localhost';
    private string $dbName = 'eco';
    private string $user = 'root';
    private string $password = '123';

    // Hàm kết nối CSDL
    private function __construct()
    {
        $this->conn = new mysqli($this->server, $this->user, $this->password, $this->dbName);

        if ($this->conn->connect_error) {
            printf($this->conn->connect_error);
            exit();
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
}
