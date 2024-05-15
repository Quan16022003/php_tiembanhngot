<?php

namespace App\Controllers\Admin;

use App\Models\OrderDetailModel;
use App\Models\OrderLogsModel;
use App\Models\OrderModel;
use App\Models\UserModel;
use Core\Controller;

class OrdersController extends AdminController
{
    public function index() {
        $status = $_GET['status'] ?? '';

        $orders = match($status) {
            'pending' => OrderModel::getAllOrdersByStatus(1),
            'confirmed' => OrderModel::getAllOrdersByStatus(2),
            'shipping' => OrderModel::getAllOrdersByStatus(3),
            'completed' => OrderModel::getAllOrdersByStatus(4),
            default => OrderModel::getAllOrders()
        };


        $data = [
            'orders' => $orders,
            'statuses' => OrderModel::getAllStatusOrder(),
            'status_active' => $status
        ];
        parent::render('Orders/index', $data);
    }

    public function getById($vars) {
        $id = $vars['id'];
        $order = OrderModel::getOrderById($id);
        $order['products'] = OrderDetailModel::getAllOrderDetailByOrderId($order['id']);
        echo json_encode($order);
    }

    public function show($vars) {
        $id = $vars['id'];
        $data = [
            'order' => OrderModel::getOrderById($id),
            'products' => OrderDetailModel::getAllOrderDetailByOrderId($id),
            'statuses' => OrderModel::getAllStatusOrder(),
            'logs' => OrderLogsModel::getLogsForOrder($id)
        ];
        parent::render('Orders/show', $data);
    }

    public function updateStatus($vars) {
        $id = $vars['id'];
        $newStatus = $_POST['newStatus'];
        // Trả về JSON
        echo json_encode(['success' => OrderModel::updateOrderStatus($id, $newStatus)]);
    }



}
