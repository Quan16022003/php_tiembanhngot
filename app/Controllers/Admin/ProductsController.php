<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\AdminProductsModel;
use App\Models\CategoriesModel;

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
        $categoryModel = new CategoriesModel();

        $product = $model->getProductByID($id);
        $categories = $categoryModel->getAllCategories();

        if ($product) {
            parent::render('products/products_edit', ['product' => $product, 'categories' => $categories]);
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
            $productImage = $_FILES["productImage"] ?? null;

            // Kiểm tra xem người dùng đã tải lên hình ảnh mới hay không
            if ($productImage && $productImage['error'] === UPLOAD_ERR_OK) {
                // Lưu tên gốc của tệp hình ảnh
                $originalName = $productImage['name'];

                // Di chuyển tệp hình ảnh vào thư mục uploads với tên gốc
                $uploadDir = '../public/uploads/';
                $uploadedFilePath = $uploadDir . $originalName;
                move_uploaded_file($productImage['tmp_name'], $uploadedFilePath);

                // Lưu tên tệp gốc vào cơ sở dữ liệu
                $productImage = $originalName;
            } else {
                // Nếu không có hình ảnh mới được tải lên, giữ nguyên tên tệp hình ảnh cũ
                $productImage = $_POST["productImageOld"]; // Giả sử biến $_POST["productImageOld"] chứa tên tệp hình ảnh cũ
            }

            // Gọi phương thức update trong Model và chuyển các tham số cần thiết
            $model = new AdminProductsModel();
            $success = $model->updateProduct($productId, $productCategoryId, $productName, $productContent, $productImage, $productPrice, $productStock);

            if ($success) {
                // Set biến cờ thành true nếu truy vấn thành công
                $reloadPage = true;
            } else {
                echo "Cập nhật sản phẩm thất bại!";
            }
        }

        // Nếu biến cờ được set thành true, thực hiện reload trang
        if (isset($reloadPage) && $reloadPage) {
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }
    }


//    public function index(): void
//    {
//        $model = new AdminProductsModel();
//        $products = $model->index();
//
//        if ($products === false) {
//            echo "Không có sản phẩm nào được tìm thấy!";
//            return;
//        }
//        $this->render('products/products', ['products' => $products]);
//    }

    public function indexPage(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $productsPerPage = 10;
        $offset = ($page - 1) * $productsPerPage;
        $model = new AdminProductsModel();
        $categories = new CategoriesModel();
        $products = $model->getProducts($offset, $productsPerPage);
        $totalProducts = $model->getTotalProducts();
        $totalPages = ceil($totalProducts / $productsPerPage);
        $categories = $categories->getAllCategories();
        $this->render('Products/products', ['products' => $products, 'totalPages' => $totalPages, 'currentPage' => $page, 'categories' => $categories]);
    }

    public function openCreate(): void
    {
        $model = new AdminProductsModel();
        $productId = $model->generateProductId();
        $this->render('Products/products_create', ['productId' => $productId]);
    }

    public function openAdd(): void
    {
        $this->render('Products/products_add');
    }


    public function create(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Lấy thông tin sản phẩm từ request
            $productId = $_POST["productId"];
            $productCategoryId = $_POST["productCategoryId"];
            $productName = $_POST["productName"];
            $productPrice = $_POST["productPrice"];
            $productContent = $_POST["productContent"];

            // Kiểm tra xem có tệp được tải lên không
            if (!empty($_FILES['productImage']['name'])) {
                // Đường dẫn đến thư mục lưu trữ hình ảnh trên máy chủ
                $uploadDirectory = "/public/uploads/";

                // Tạo thư mục lưu trữ hình ảnh nếu nó không tồn tại
                if (!file_exists($uploadDirectory)) {
                    mkdir($uploadDirectory, 0777, true);
                }

                // Đặt tên cho hình ảnh mới dựa trên timestamp và tên tệp
                $imageName = time() . '_' . $_FILES['productImage']['name'];

                // Đường dẫn đầy đủ đến hình ảnh trên máy chủ
                $targetFilePath = $uploadDirectory . $imageName;

                // Di chuyển tệp tải lên vào thư mục lưu trữ
                if (move_uploaded_file($_FILES['productImage']['tmp_name'], $targetFilePath)) {
                    // Đường dẫn hình ảnh trong thư mục lưu trữ
                    $productImage = $uploadDirectory . $imageName;
                } else {
                    echo "Đã xảy ra lỗi khi tải lên hình ảnh.";
                    return;
                }
            } else {
                // Nếu không có tệp hình ảnh được tải lên, sử dụng một đường dẫn mặc định hoặc để trống
                $productImage = ""; // Đường dẫn mặc định hoặc rỗng
            }

            // Thực hiện thêm sản phẩm vào cơ sở dữ liệu
            $model = new AdminProductsModel();
            $productId = $model->generateProductId($productId);
            $success = $model->create($productId, $productCategoryId, $productName, $productPrice, $productContent, $productImage);

            if ($success) {
                echo "Thêm sản phẩm thành công!";
//                header("Location: /admin/products/edit/$productId");
                exit;
            } else {
                echo "Thêm sản phẩm thất bại!";
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

    public function api_getAllProducts()
    {
        $model = new AdminProductsModel();
        $products = $model->getAllProducts();
        echo json_encode(["products" => $products]);
    }

    public function deleteImage(): void
    {
        // Kiểm tra xem yêu cầu có phải là POST không
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Lấy productId từ dữ liệu POST
            $productId = $_POST['productId'];

            // Gọi phương thức xóa hình ảnh từ model
            $model = new AdminProductsModel();
            $success = $model->deleteImageByProductId($productId);

            // Trả về phản hồi JSON
            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to delete image']);
            }
        } else {
            // Trả về lỗi nếu yêu cầu không phải là POST
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
        }
    }

    public function addImage(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $target_dir = "public/uploads/";
            $target_file = $target_dir . basename($_FILES["file"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["file"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["file"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                    echo "The file " . basename($_FILES["file"]["name"]) . " has been uploaded.";
                    $model = new AdminProductsModel();
                    $productId = $_POST['productId'];
                    $model->saveImageLink($productId, $target_file);
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
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
