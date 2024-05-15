<?php

namespace App\Models;

use Core\Database;

class OrderLogsModel
{
    public static function getLogsForOrder($orderId) {
        $db = Database::getConnection();
        $sql = "SELECT * FROM order_logs WHERE order_id = ? ORDER BY timestamp DESC";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $logs = array();
        while ($row = $result->fetch_assoc()) {
            $logs[] = $row;
        }
        return $logs;
    }
}