<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\AdminDashboardModel;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct('Admin');
    }

    public function index(): void
    {
        $model = new AdminDashboardModel();
        $products = $model->index();
        $totalProducts = count($products);
        $totalStock = 0;
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalStock += $product['stock'];
            $totalPrice += $product['price'];
        }
        parent::render('dashboard/dashboard', ['totalProducts' => $totalProducts, 'totalStock' => $totalStock, 'totalPrice' => $totalPrice]);
    }
}
