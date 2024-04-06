<?php

namespace Core;


use mysqli;

class Database
{
    private static ?Database $instance = null;
    public ?mysqli $conn = NULL;
    private string $host = 'db';
    private string $dbName = 'eco';
    private string $user = 'root';
    private string $password = '123';

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
}
