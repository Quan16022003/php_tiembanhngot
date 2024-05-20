<?php

namespace App\Controllers\Admin;

use App\Models\OrderDetailsModel;
use App\Models\OrderLogsModel;
use App\Models\CartModel;
use App\Models\OrderModel;
use App\Models\UserModel;
use Core\Controller;

class OrdersController extends AdminController
{
    public function index()
    {
        $status = $_GET['status'] ?? '';

        $orders = match ($status) {
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

    public function getById($vars)
    {
        $id = $vars['id'];
        $order = OrderModel::getOrderById($id);
        $order['products'] = OrderDetailModel::getAllOrderDetailByOrderId($order['id']);
        echo json_encode($order);
    }

    public function show($vars)
    {
        $id = $vars['id'];
        $data = [
            'order' => OrderModel::getOrderById($id),
            'products' => OrderDetailsModel::getAllOrderDetailByOrderId($id),
            'statuses' => OrderModel::getAllStatusOrder(),
            'logs' => OrderLogsModel::getLogsForOrder($id)
        ];
        parent::render('Orders/show', $data);
    }

    public function create(): void
    {
        $order = new OrderModel();
        $this->getOrderData($order);
        $order->save();
        $orderId = $order->getId();
        $this->getOrderDetailsFromCart($orderId);
        header("Location: /orders/{$orderId}");
    }

    private function getOrderData($order)
    {
        $order->setTotalPrice($_POST['total']);
        $order->setUserID($_POST['user_id']);
        $order->setAddress1($_POST['address1']);
        $order->setAddress2($_POST['address2']);
        $order->setPhoneNumber($_POST['phone']);
    }

    private function getOrderDetailsFromCart($orderId)
    {
        $model = new CartModel();
        $cartItems = $model->getCartByUserId($_POST['user_id']);
        foreach ($cartItems as $item) {
            $detail = new OrderDetailsModel();
            $detail->setOrderId($orderId);
            $detail->setProductId($item['product_id']);
            $detail->setQuantity($item['quantity']);
            $detail->setUnitPrice($item['price']);
            $detail->save();
            $model->deleteCart($item['id']);
        }
    }

    public function updateStatus($vars)
    {
        $id = $vars['id'];
        $newStatus = $_POST['newStatus'];
        // Trả về JSON
        echo json_encode(['success' => OrderModel::updateOrderStatus($id, $newStatus)]);
    }


}
