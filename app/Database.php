<?php

namespace App;

use PDO;

class Database
{
    public string $error = "";
    private \PDO|null $pdo;
    private \PDOStatement|null $stmt;
    public function __construct()
    {
        $this->pdo = new PDO(
            "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8",
            DB_USER, DB_PW, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }
    public function __destruct()
    {
        if ($this->stmt != null) { $this->stmt = null;}
        if ($this->pdo != null) {$this->pdo=null;}
    }
    function select ($sql, $data=null): bool|array
    {
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute($data);
        return $this->stmt->fetchAll();
    }
}