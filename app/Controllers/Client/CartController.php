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

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $userModel = new UserModel();
            $userID = $userModel->getUserIdByUsername($_SESSION['username']);
            $data['carts'] = $this->cartModel->getAllCart($userID);
//            echo json_encode($data['carts']);
            parent::render('cart', $data);
        } else {
            parent::render('login_required_message');
        }
    }

    public function addToCart(): void
    {
        if (!isset($_SESSION['username'])) {
            header('Location: /account/login');
            return;
        }

        $userModel = new UserModel();
        $userId = $userModel->getUserIdByUsername($_SESSION['username']);
        $productID = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        $existingCartItem = $this->cartModel->getCartItem($userId, $productID);

        if ($existingCartItem) {
            // Nếu sản phẩm đã tồn tại, cập nhật số lượng
            $newQuantity = $existingCartItem['quantity'] + $quantity;
            if ($this->cartModel->updateCart($userId, $productID, $newQuantity)) {
                header('Location: /cart');
            } else {
                echo 'error';
            }
        } else {
            // Nếu sản phẩm chưa tồn tại, thêm mới vào giỏ hàng
            if ($this->cartModel->addCart($userId, $productID, $quantity)) {
                echo 'success';
            } else {
                echo 'error';
            }
        }
    }
}