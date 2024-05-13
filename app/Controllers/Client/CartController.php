<?php

namespace App\Controllers\Client;


use App\Models\CartModel;
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

    function index(): void
    {
        $limit = (isset($_GET['limit'])) ? $_GET['limit'] : 16;
        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $sort_by = (isset($_GET['sort_by'])) ? $_GET['sort_by'] : 'default';
        $result = match ($sort_by) {
            'price_asc' => $this->cartModel->selectAllPriceASC($limit, $page),
            'price_desc' => $this->cartModel->selectAllPriceDESC($limit, $page),
            'title_asc' => $this->cartModel->selectAllTitleASC($limit, $page),
            'title_desc' => $this->cartModel->selectAllTitleDESC($limit, $page),
            default => $this->cartModel->selectAll($limit, $page)
        };
        $totalPages = ceil($result->total / $limit);
        $data = [
            'carts' => $result->data,
            'total_carts' => $result->total,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'sort_by' => [
                'default' => 'Mới nhất',
                'price_asc' => 'Giá tăng dần',
                'price_desc' => 'Giá giảm dần',
                'title_asc' => 'Tên A-Z',
                'title_desc' => 'Tên Z-A',
            ],
            'current_sort' => $sort_by
        ];
        $this->render('Cart/cart', $data);
    }

    public function indexPage(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $cartsPerPage = 10;
        $offset = ($page - 1) * $cartsPerPage;
        $model = new CartModel();
        $carts = $model->getCart($offset, $cartsPerPage);
        $totalCart = $model->getTotalCart();
        $totalPages = ceil($totalCart / $cartsPerPage);
        $this->render('Cart/cart', ['carts' => $carts, 'totalPages' => $totalPages, 'currentPage' => $page]);
    }
}