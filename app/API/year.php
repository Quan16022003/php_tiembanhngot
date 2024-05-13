<?php
require_once 'app/Models/AdminDashboardModel.php';
$model = new AdminDashboardModel();
// Xử lý yêu cầu AJAX
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Lấy dữ liệu từ model
    $years = $model->getYearsFromDatabase();

    // Trả về dữ liệu dưới dạng JSON
    header('Content-Type: application/json');
    echo json_encode($years);
} else {
    // Trả về mã lỗi hoặc thông báo nếu yêu cầu không hợp lệ
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method']);
}