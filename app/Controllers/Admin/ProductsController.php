<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\AdminProductsModel;

class ProductsController extends Controller
{
    public function __construct()
    {
        parent::__construct('Admin');
    }

    public function getById($id): void
    {
        if (is_array($id) && isset($id['productID'])) {
            $id = $id['productID'];
        }

        $model = new AdminProductsModel();
        $product = $model->getProductByID($id);
        if ($product) {
            parent::render('products_edit', ['product' => $product]);
        } else {
            echo "Không tìm thấy sản phẩm!";
            var_dump(debug_backtrace());
        }
    }

    public function update(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $productId = $_POST["productId"];
            $productCategoryId = $_POST["productCategoryId"];
            $productContent = $_POST["productContent"];
            $productName = $_POST["productName"];
            $productPrice = $_POST["productPrice"];
            $productStock = $_POST["productStock"];
            $productImage = $_POST["productImage"];
            $model = new AdminProductsModel();
            $success = $model->update($productId, $productCategoryId, $productContent, $productName, $productPrice, $productStock, $productImage);
            if ($success) {
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            } else {
                echo "Cập nhật sản phẩm thất bại!";
            }
        }
    }

    public function index(): void
    {
        $model = new AdminProductsModel();
        $products = $model->index();

        if ($products === false) {
            echo "Không có sản phẩm nào được tìm thấy!";
            return;
        }
        $this->render('products', ['products' => $products]);
    }

    public function openAdd(): void
    {
        $this->render('products_add');
    }

    public function add(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $productId = $_POST["productId"];
            $productCategoryId = $_POST["productCategoryId"];
            $productName = $_POST["productName"];
            $productPrice = $_POST["productPrice"];
            $productQuantity = $_POST["productQuantity"];

            $model = new AdminProductsModel();
            $success = $model->insert($productId, $productCategoryId, $productName, $productPrice, $productQuantity);

            if ($success) {
                echo "Thêm sản phẩm thành công!";
                header("Location: /admin/products");
            } else {
                echo "Thêm sản phẩm thất bại!";
            }
        }
    }

    public function delete(): void
    {
        // Truy xuất tham số productID từ yêu cầu POST
        $productID = $_POST['productID'];

        // Tiếp tục xử lý xóa sản phẩm với productID nhận được
        $model = new AdminProductsModel();
        $success = $model->delete($productID);

        if ($success) {
            echo "Xóa sản phẩm thành công!";
            // Cập nhật lại giao diện hoặc thực hiện hành động cần thiết sau khi xóa thành công
            header("Location: /admin/products");
        } else {
            echo "Xóa sản phẩm thất bại!";
            // Xử lý lỗi nếu có
        }
    }

    public function search(): void
    {
        // Check if the 'search-text' field is set in the POST data
        $requestData = json_decode(file_get_contents('php://input'), true);

        if (!isset($requestData['searchText'])) {
            echo json_encode(['error' => 'Missing search parameters']);
            return;
        }

        // Lấy dữ liệu từ yêu cầu
        $text = $requestData['searchText'];
        $model = new AdminProductsModel();

        // Gọi model để thực hiện tìm kiếm
        $products = $model->search($text);

        // Trả về kết quả dưới dạng JSON
        echo json_encode($products);
    }


}
