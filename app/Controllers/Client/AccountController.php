<?php

namespace App\Controllers\Client;

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

    public function updateInfo() {


    }

    public function changePass() {

    }
}