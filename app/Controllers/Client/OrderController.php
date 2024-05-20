<?php

namespace App\Controllers\Client;

use App\Models\OrderModel;
use App\Models\UserModel;

class OrderController extends ClientController
{
    private OrderModel $orderModel;

    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new OrderModel();
    }

    // Hiển thị danh sách đơn hàng
    public function index(): void
    {
        $userId = (new UserModel())->getUserIdByUsername($_SESSION['username']);
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $orders = $this->orderModel->getOrdersByUserId($userId);
        $totalOrders = $this->orderModel->getTotalOrders();
        $totalPages = ceil($totalOrders / $limit);

        $data = [
            'orders' => $orders,
            'total_orders' => $totalOrders,
            'current_page' => $page,
            'total_pages' => $totalPages
        ];

        $this->render('orders/index', $data);
    }

    // Hiển thị chi tiết đơn hàng
    public function show($id): void
    {
        if (is_array($id) && isset($id['id'])) {
            $id = $id['id'];
        }
        $order = $this->orderModel->getOrderById($id);
        $orderDetails = $this->orderModel->getOrderDetails($id);
        $this->render('orders/show', ['order' => $order, 'orderDetails' => $orderDetails]);
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $success = (new OrderModel())->updateOrderStatus($id, 4);

            if ($success) {
                echo json_encode(['success' => true]);
                 header("Location: /orders/$id");
            } else {
                echo json_encode(['success' => false]);
            }
            exit;
        }
    }


    // Hiển thị lịch sử đơn hàng của khách hàng
    public
    function history(int $userId): void
    {
        $orders = $this->orderModel->getOrderById($userId);
        $this->render('orders/history', ['orders' => $orders]);
    }
}
