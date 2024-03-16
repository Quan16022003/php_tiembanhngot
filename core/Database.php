<?php

namespace Core;

use PDO;
use PDOException;

class Database {
    private $host = 'your_host';
    private $username = 'your_username';
    private $password = 'your_password';
    private $database = 'your_database';
    private $connection;

    public function __construct()
    {
        try {
            $this->connection = new PDO("mysql:host={$this->host};dbname={$this->database}", $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Failed to connect to database: " . $e->getMessage());
        }
    }

    public function query($sql)
    {
        return $this->connection->query($sql);
    }

    public function prepare($sql)
    {
        return $this->connection->prepare($sql);
    }
}
