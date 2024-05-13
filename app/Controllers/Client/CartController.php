<?php

namespace App\Controllers\Client;


use App\Models\CartModel;
use App\Models\UserModel;
use App\Models\AdminProductsModel;
use mysql_xdevapi\Result;

class CartController extends ClientController
{
    private CartModel $cartModel;
    public function __construct()
    {
        parent::__construct();
        $this->cartModel = new CartModel();
    }

    // Trong CartController.php
    public function index()
    {
        
        // $cartModel->getAll();
        // echo $cartModel;
        // var_dump ($cartModel);
        // $cart = $this->addToCart();
        // Trả về view 'cart.twig' với dữ liệu cần thiết
        $data['cart'] = $this->cartModel->getAll();
        // print_r($data);
        $this->render('cart',$data);    
        
        // $this->render('cart', ['cartData' => $cartModel]);
    }

    public function addToCart(){
        $product_id = $_GET['product_id'];
        $quantity = $_GET['quantity_product'];
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
        $userModel = new UserModel();
        $user_id = $userModel->getUserIdByUsername($username);
        
        $cartModel = new CartModel();
        $cartModel->addToCart($user_id, $product_id, $quantity);
        header('Location: /products/'.$product_id);
    }
}