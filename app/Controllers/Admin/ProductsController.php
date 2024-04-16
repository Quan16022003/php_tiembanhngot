<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\AdminProductsModel;

class ProductsController extends Controller
{
    public function __construct($page = NULL)
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
            parent::render('Products/products_edit', ['product' => $product]);
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
            var_dump($productId, $productCategoryId, $productContent, $productName, $productPrice, $productStock, $productImage);
            $model = new AdminProductsModel();
            $success = $model->update($productId, $productCategoryId, $productName, $productContent, $productImage, $productPrice, $productStock);
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
        $this->render('products/products', ['products' => $products]);
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
        $this->render('Products/products', ['products' => $products, 'totalPages' => $totalPages, 'currentPage' => $page]);
    }

    public function openCreate(): void
    {
        $this->render('Products/products_create');
    }

    public function openAdd(): void
    {
        $this->render('Products/products_add');
    }


    public function create(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $productId = $_POST["productId"];
            $productCategoryId = $_POST["productCategoryId"];
            $productName = $_POST["productName"];
            $productPrice = $_POST["productPrice"];
            $productContent = $_POST["productContent"];

            $model = new AdminProductsModel();
            $success = $model->create($productId, $productCategoryId, $productName, $productPrice, $productContent);

            if ($success) {
                echo "Thêm sản phẩm thành công!";
                header("Location: /admin/products");
            } else {
                echo "Thêm sản phẩm thất bại!";
            }
        }
    }

    public function addExcel(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Kiểm tra xem tệp có phải là tệp Excel không
            if ($fileType != "xlsx" && $fileType != "xls") {
                echo "Chỉ cho phép tải lên các tệp Excel (XLSX hoặc XLS).";
                $uploadOk = 0;
            }

            // Kiểm tra nếu có lỗi xảy ra khi tải lên
            if ($uploadOk == 0) {
                echo "Tệp của bạn không được tải lên.";
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    echo "Tệp " . basename($_FILES["fileToUpload"]["name"]) . " đã được tải lên thành công.";
                } else {
                    echo "Có lỗi xảy ra khi tải lên tệp của bạn.";
                }
            }
        }
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
