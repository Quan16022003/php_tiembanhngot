<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\AdminProductsModel;
use App\Models\CategoriesModel;
use App\Models\SupplierModel;

class ProductsController extends Controller
{
    public function __construct()
    {
        parent::__construct('Admin');
    }

    public function openCreate(): void
    {
        $categories = (new CategoriesModel())->getAllCategories();
        $suppliers = (new SupplierModel())->getAllSuppliers();
        $data = ['categories' => $categories, 'suppliers' => $suppliers];
//        var_dump(json_encode($data));
        $this->render('products/create', $data);
    }

    public function openAdd(): void
    {
        $this->render('products/import');
    }

    public function getById($id): void
    {

        if (is_array($id) && isset($id['id'])) {
            $id = $id['id'];
        }
        $model = new AdminProductsModel();
        $categoryModel = new CategoriesModel();

        $product = $model->getProductByID($id);
        $categories = $categoryModel->getAllCategories();
        $suppliers = (new SupplierModel())->getAllSuppliers();

        if ($product) {
            parent::render('products/update', ['product' => $product, 'categories' => $categories, 'suppliers' => $suppliers]);
        } else {
            echo "Không tìm thấy sản phẩm!";
            var_dump(json_encode(debug_backtrace()));
        }
    }

    public function create(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Lấy thông tin sản phẩm từ request
            $productCategoryId = $_POST["category_id"];
            $productName = $_POST["name"];
            $productPrice = $_POST["price"];
            $productContent = $_POST["content"];
            $supplierId = $_POST["supplier_id"];
            $productImage = null;

            // Kiểm tra xem người dùng đã chọn tùy chọn xóa hình ảnh hay không
            if (!empty($_FILES["image"]["name"]) && !empty($_FILES["image"]["tmp_name"])) {
                // Di chuyển và lưu trữ tệp hình ảnh mới
                $productImage = $_FILES["image"]["name"];
                $productImageTemp = $_FILES["image"]["tmp_name"];
                $folder = __DIR__ . '/../../../public/uploads/' . $productImage;
                if (move_uploaded_file($productImageTemp, $folder)) {
                    echo "Uploaded";
                } else {
                    echo "Failed";
                }
            } else {
                // Nếu không có tệp hình ảnh mới và không chọn xóa, sử dụng tên hình ảnh hiện tại
                $productImage = $_POST["current_image"];
            }

            // Thực hiện thêm sản phẩm vào cơ sở dữ liệu
            $model = new AdminProductsModel();
            $success = $model->create($productCategoryId, $productName, $productPrice, $productContent, $productImage, $supplierId);

            if ($success) {
                header("Location: /admin/products");
                exit;
            } else {
                echo "Thêm sản phẩm thất bại!";
            }
        }
    }

    public function read(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $productsPerPage = 10;
        $offset = ($page - 1) * $productsPerPage;
        $productsModel = new AdminProductsModel();
        $categoriesModel = new CategoriesModel();
        $products = $productsModel->getProducts($offset, $productsPerPage);
        $totalProducts = $productsModel->getTotalProducts();
        $totalPages = ceil($totalProducts / $productsPerPage);
        $categories = $categoriesModel->getAllCategories();
        $this->render('products/index', ['products' => $products, 'totalPages' => $totalPages, 'currentPage' => $page, 'categories' => $categories]);
    }

    public function update(): void
    {
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $productId = $_POST["id"];
            $productCategoryId = $_POST["category_id"];
            $productContent = $_POST["content"];
            $productName = $_POST["name"];
            $productPrice = $_POST["price"];
            $productStock = $_POST["stock"];
            $productSupplier = $_POST["supplier_id"];
            $productImage = null;

            // Kiểm tra xem người dùng đã chọn tùy chọn xóa hình ảnh hay không
            if (!empty($_FILES["image"]["name"]) && !empty($_FILES["image"]["tmp_name"])) {
                // Di chuyển và lưu trữ tệp hình ảnh mới
                $productImage = $_FILES["image"]["name"];
                $productImageTemp = $_FILES["image"]["tmp_name"];
                $folder = __DIR__ . '/../../../public/uploads/' . $productImage;
                if (move_uploaded_file($productImageTemp, $folder)) {
                    echo "Uploaded";
                } else {
                    echo "Failed";
                }
            } elseif (!empty($_POST["deleteImage"])) {
                // Xóa hình ảnh hiện tại từ máy chủ
                $currentImage = $_POST["current_image"];
                $filePath = __DIR__ . '/../../../public/uploads/' . $currentImage;
                if (file_exists($filePath)) {
                    unlink($filePath); // Xóa tệp hình ảnh từ máy chủ
                }
            } else {
                // Nếu không có tệp hình ảnh mới và không chọn xóa, sử dụng tên hình ảnh hiện tại
                $productImage = $_POST["current_image"];
            }

            $model = new AdminProductsModel();
            $success = $model->updateProduct($productId, $productCategoryId, $productName, $productContent, $productPrice, $productStock, $productImage, $productSupplier);
            if ($success) {
                $_SESSION['message'] = 'Cập nhật sản phẩm thành công!';
            } else {
                $_SESSION['message'] = 'Cập nhật sản phẩm thất bại!';
            }
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function delete(): void
    {
        $productID = $_POST['id'];
        $model = new AdminProductsModel();
        $success = $model->delete($productID);

        if ($success) {
            echo "Xóa sản phẩm thành công!";
            header("Location: /admin/products");
        } else {
            echo "Xóa sản phẩm thất bại!";
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

    public function api_getAllProducts()
    {
        $model = new AdminProductsModel();
        $products = $model->getAllProducts();
        echo json_encode(["products" => $products]);
    }

    public function uploadCsv()
    {
        $model = new AdminProductsModel();
        if (isset($_FILES['csv'])) {
            $tmpName = $_FILES['csv']['tmp_name'];
            $product = $model->insertFromCsv($tmpName);
        }
    }

    public function exportToCsv()
    {
        $model = new AdminProductsModel();
        $products = $model->getAllProducts(); // Ví dụ: phương thức để lấy tất cả sản phẩm
        $csvData = $model->exportToCsv($products);
        $fileName = 'exported_file.csv';

        // Thiết lập header để tải về file CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $fileName);

        echo $csvData;
    }
}