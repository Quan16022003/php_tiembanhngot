<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\AdminProductsModel;

class PaginationController extends Controller
{
    public function __construct()
    {
        parent::__construct('Admin');
    }

    public function loadProducts(): void
    {
        // Xử lý yêu cầu AJAX
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Lấy trang được yêu cầu từ dữ liệu POST
            $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;

            // Số lượng sản phẩm trên mỗi trang
            $productsPerPage = 10;

            // Tính offset
            $offset = ($page - 1) * $productsPerPage;

            // Tạo một instance của AdminProductsModel
            $model = new AdminProductsModel();

            // Lấy danh sách sản phẩm cho trang hiện tại
            $products = $model->getProducts($offset, $productsPerPage);

            // Trả về dữ liệu dưới dạng JSON
            echo json_encode($products);
        } else {
            // Nếu không phải là yêu cầu POST, chuyển hướng hoặc xử lý lỗi
        }
    }
}

