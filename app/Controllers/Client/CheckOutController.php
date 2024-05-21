<?php

namespace App\Controllers\Client;

use App\Models\AdminProductsModel;
use App\Models\UserModel;
use Core\Controller;
use App\Models\OrderModel;
use App\Models\CartModel;

class CheckOutController extends ClientController
{
    private $orderModel;
    private $cartModel;

    public function __construct()
    {
        parent::__construct('Client');
        $this->orderModel = new OrderModel();
        $this->cartModel = new CartModel();
    }

    public function showCheckOutPage(): void
    {
        $userModel = new UserModel();
        $username = $_SESSION['username'];
        $userId = $userModel->getUserIdByUsername($username);
        $userName = $userModel->getNameByUsername($username);
        $data['cart'] = $this->cartModel->getAllCart($userId);
        $cart = $this->cartModel->getAllCart($userId);

        $totalPrice = array_reduce($cart, function ($sum, $item) {
            return $sum + ($item['quantity'] * $item['price']);
        }, 0);

        parent::render('checkout/index', [
            'userName' => $userName,
            'userId' => $userId,
            'cart' => $cart,
            'totalPrice' => $totalPrice
        ]);
    }

    public function placeOrder()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'placeOrder') {
            $name = $this->checkInput($_POST['name']);
            $email = $this->checkInput($_POST['email']);
            $phone = $this->checkInput($_POST['phone']);
            $address = $this->checkInput($_POST['address']);
            $total = $this->checkInput($_POST['total']);

            if ($this->orderModel->createOrder($name, $email, $phone, $address, $total, $created)) {
                $this->reduceProductStock();
                echo 'Order Placed Successfully!';
            } else {
                echo 'Something went wrong. Please try again!';
            }
        }
    }

    public function reduceProductStock($userId)
    {
        $cart = $this->cartModel->getAllCart($userId);
        $productModel = new AdminProductsModel();

        foreach ($cart as $item) {
            $productId = $item['product_id'];
            $quantity = $item['quantity'];
            $productModel->reduceStock($productId, $quantity);
        }
    }

    public function thanks() {
        parent::render('thanks');
    }
}
