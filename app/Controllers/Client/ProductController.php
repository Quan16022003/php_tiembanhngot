<?php

namespace App\Controllers\Client;


use App\Models\AdminProductsModel;
use App\Models\ProductModel;
use mysql_xdevapi\Result;

class ProductController extends ClientController
{
    private ProductModel $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new ProductModel();
    }

    function index(): void
    {
        $limit = (isset($_GET['limit'])) ? $_GET['limit'] : 16;
        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $sort_by = (isset($_GET['sort_by'])) ? $_GET['sort_by'] : 'default';
        $result = match ($sort_by) {
            'price_asc' => $this->productModel->selectAllPriceASC($limit, $page),
            'price_desc' => $this->productModel->selectAllPriceDESC($limit, $page),
            'title_asc' => $this->productModel->selectAllTitleASC($limit, $page),
            'title_desc' => $this->productModel->selectAllTitleDESC($limit, $page),
            default => $this->productModel->selectAll($limit, $page)
        };
        $totalPages = ceil($result->total / $limit);
        $data = [
            'products' => $result->data,
            'total_products' => $result->total,
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
        $this->render('Products/product', $data);
    }

    public function indexPage(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $productsPerPage = 10;
        $offset = ($page - 1) * $productsPerPage;
        $model = new AdminProductsModel();
        $products = $model->getProducts($offset, $productsPerPage);
        $totalProducts = $model->getTotalProducts();
        $totalPages = ceil($totalProducts / $productsPerPage);
        $this->render('products/product', ['products' => $products, 'totalPages' => $totalPages, 'currentPage' => $page]);
    }

    function productDetail($id): void
    {
        // $id = $_GET['id'];
        if (is_array($vars) && isset($vars['id'])) {
            $id = $vars['id'];
        }
        $data = $this->productModel->selectProductbyID($id);
        var_dump($data);
        $this->render('products/productDetail', ['product' => $data]);

    }
}