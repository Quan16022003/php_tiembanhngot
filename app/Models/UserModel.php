<?php

namespace App\Models;

use Core\Database;

class UserModel
{
    private ?Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->conn = $this->db->conn;
    }

    public function registerUser($name, $uname, $email, $pass, $created)
    {
        $pass = sha1($pass);
        $stmt = $this->conn->prepare("INSERT INTO `users` (`name`, `username`, `email`, `pass`, `created`) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $uname, $email, $pass, $created);

        return $stmt->execute();
    }

    public function loginUser($username, $password)
    {
        $password = sha1($password);
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username=? AND pass=?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function generateResetToken($email)
    {
        $token = "qwertyuiopasdfghjklzxcvbnm1234567890";
        $token = str_shuffle($token);
        $token = substr($token, 0, 10);

        $stmt = $this->conn->prepare("UPDATE users SET token = ?, tokenExpire = DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        return $token;
    }

    public function verifyResetToken($email, $token)
    {
        $stmt = $this->conn->prepare("SELECT `id` FROM `users` WHERE `email`=? AND `token`=?");
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    public function resetPassword($email, $newpass)
    {
        $newpass = sha1($newpass);
        $stmt = $this->conn->prepare("UPDATE users SET token='', pass=? WHERE email=?");
        $stmt->bind_param("ss", $newpass, $email);
        return $stmt->execute();
    }

    public function getUserIdByUsername($username)
    {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['id'];
    }

    public function getNameByUsername($username)
    {
        $stmt = $this->conn->prepare("SELECT name FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['name'];
    }

    public function getCustomerPurchaseSummary()
    {
        $sql = "SELECT u.id, u.name AS name, u.email, \n"

            . "	COUNT(o.id) AS orders,\n"

            . " COALESCE(SUM(od.price * od.quantity), 0) AS total_spent\n"

            . "FROM users u\n"

            . "LEFT JOIN `order` o ON o.user_id = u.id\n"

            . "LEFT JOIN order_detail od ON od.order_id = o.id\n"

            . "GROUP BY u.id, u.name, u.email;";
        $result = $this->conn->query($sql);
        $customers = [];
        while ($row = $result->fetch_assoc()) {
            $customers[] = $row;
        }
        return $customers;
    }
}