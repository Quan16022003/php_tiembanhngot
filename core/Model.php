<?php

namespace Core;

class Model
{
    protected Database $db;

    public function __construct()
    {
        $this->db = new Database(); // Assuming you have a Database class for database connectivity
    }

    public function getAll($table)
    {
        $query = "SELECT * FROM $table";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($table, $id)
    {
        $query = "SELECT * FROM $table WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}