<?php

namespace App\Controllers\Client;

use App\Models\OrderDetailsModel;
use App\Models\OrderModel;
use App\Models\UserModel;
use http\Client\Curl\User;

class AccountController extends ClientController
{
    public function index() {
        $userId = $_SESSION['userId'];

        $orders = OrderModel::getAllOrdersByUserId($userId);
        $name = (new UserModel())->getNameByUsername($_SESSION['username']);
        $statuses = OrderModel::getAllStatusOrder();

        $data = [
            'orders' => $orders,
            'name' => $name,
            'statuses' => $statuses
        ];

        parent::render('Account/index', $data);
    }

    public function changePass() {

    }

    public function orderDetails() {
        if (!isset($_GET['id'])) {
            header('Location: /account');
        }
        $id = $_GET['id'];
        $data = [
            'order' => OrderModel::getOrderById($id),
            'products' => OrderDetailsModel::getAllOrderDetailByOrderId($id)
        ];
        print_r($data);
        parent::render('Account/orderDetails', $data);
    }
}