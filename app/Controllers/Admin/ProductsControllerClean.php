<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\AdminProductsModel;
use App\Models\CategoriesModel;
use App\Models\SupplierModel;

#FILE NAY DANG DUOC CLEAN CODE
class ProductsController extends Controller
{
    private $adminProductsModel;
    private $categoriesModel;
    private $id;
    private $categoryId;
    private $name;
    private $content;
    private $price;
    private $stock;
    private $image;
    private $supplierId;
    private $page;

    public function __construct()
    {
        parent::__construct('Admin');
        $this->adminProductsModel = new AdminProductsModel();
        $this->categoriesModel = new CategoriesModel();
        $this->initData();
    }

    public function openCreate(): void
    {
        $this->render('products/create');
    }

    public function openAdd(): void
    {
        $this->render('products/import');
    }

    private function initData(): void
    {
        $this->id = $_POST['id'] ?? null;
        $this->categoryId = $_POST['category_id'] ?? null;
        $this->name = $_POST['name'] ?? null;
        $this->content = $_POST['content'] ?? null;
        $this->price = $_POST['price'] ?? null;
        $this->stock = $_POST['stock'] ?? null;
        $this->image = $_POST['image'] ?? null;
        $this->supplierId = $_POST['supplier_id'] ?? null;
        $this->page = $_GET['page'] ?? 1;
    }

    public function getById($id): void
    {
        if (is_array($id) && isset($id['id'])) {
            $id = $id['id'];
        }

        $product = $this->adminProductsModel->getProductById($id);
        $categories = $this->categoriesModel->getAllCategories();

        if ($product) {
            $this->render('products/update', ['product' => $product, 'categories' => $categories]);
        } else {
            var_dump(json_encode(debug_backtrace()));
            echo "Không tìm thấy sản phẩm!";
        }
    }

    public function create(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $productId = $_POST["id"];
            $productCategoryId = $_POST["category_id"];
            $productName = $_POST["name"];
            $productPrice = $_POST["price"];
            $productContent = $_POST["content"];

            $productImage = $this->handleImageUpload('image', '/public/uploads/');

            $success = $this->adminProductsModel->create($productId, $productCategoryId, $productName, $productPrice, $productContent, $productImage);

            if ($success) {
                header("Location: /admin/products/edit/$productId");
                exit;
            } else {
                echo "Thêm sản phẩm thất bại!";
            }
        }
    }

    public function read(): void
    {
        $page = $this->page;
        $productsPerPage = 10;
        $offset = ($page - 1) * $productsPerPage;
        $products = $this->adminProductsModel->getProducts($offset, $productsPerPage);
        $totalProducts = $this->adminProductsModel->getTotalProducts();
        $totalPages = ceil($totalProducts / $productsPerPage);
        $categories = $this->categoriesModel->getAllCategories();
        $this->render('products/index', ['products' => $products, 'totalPages' => $totalPages, 'currentPage' => $page, 'categories' => $categories]);
    }

    public function update(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->image = $this->handleUpdateImageUpload();

            $success = $this->adminProductsModel->updateProduct(
                $this->id,
                $this->categoryId,
                $this->name,
                $this->content,
                $this->price,
                $this->stock,
                $this->image
            );

            if ($success) {
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            } else {
                echo "Cập nhật sản phẩm thất bại!";
            }
        }
    }

    public function delete(): void
    {
        $productID = $_POST['productID'];
        $success = $this->adminProductsModel->delete($productID);

        if ($success) {
            echo "Xóa sản phẩm thành công!";
            header("Location: /admin/products");
        } else {
            echo "Xóa sản phẩm thất bại!";
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

            $success = $this->adminProductsModel->insert($productId, $productCategoryId, $productName, $productPrice, $productQuantity);

            if ($success) {
                echo "Thêm sản phẩm thành công!";
                header("Location: /admin/products");
            } else {
                echo "Thêm sản phẩm thất bại!";
            }
        }
    }

    public function search(): void
    {
        $requestData = json_decode(file_get_contents('php://input'), true);

        if (!isset($requestData['searchText'])) {
            echo json_encode(['error' => 'Missing search parameters']);
            return;
        }

        $text = $requestData['searchText'];
        $products = $this->adminProductsModel->search($text);
        echo json_encode($products);
    }

    public function api_getAllProducts(): void
    {
        $products = $this->adminProductsModel->getAllProducts();
        echo json_encode(["products" => $products]);
    }

    public function uploadCsv(): void
    {
        if (isset($_FILES['csv'])) {
            $tmpName = $_FILES['csv']['tmp_name'];
            $this->adminProductsModel->insertFromCsv($tmpName);
        }
    }

    public function exportToCsv(): void
    {
        $products = $this->adminProductsModel->getAllProducts();
        $csvData = $this->adminProductsModel->exportToCsv($products);
        $fileName = 'exported_file.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $fileName);

        echo $csvData;
    }

    private function handleImageUpload($inputName, $uploadDirectory): ?string
    {
        if (!empty($_FILES[$inputName]['name']) && !empty($_FILES[$inputName]['tmp_name'])) {
            $imageName = time() . '_' . $_FILES[$inputName]['name'];
            $targetFilePath = __DIR__ . '/../../../public/uploads/' . $imageName;
            if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFilePath)) {
                return $imageName;
            } else {
                echo "Failed to upload image.";
                return null;
            }
        }
        return null;
    }

    private function handleUpdateImageUpload(): ?string
    {
        if (!empty($_FILES["image"]["name"]) && !empty($_FILES["image"]["tmp_name"])) {
            $imageName = time() . '_' . $_FILES["image"]["name"];
            $targetFilePath = __DIR__ . '/../../../public/uploads/' . $imageName;
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                return $imageName;
            } else {
                echo "Failed to upload image.";
                return null;
            }
        } elseif (!empty($_POST["deleteImage"])) {
            $currentImage = $_POST["current_image"];
            $filePath = __DIR__ . '/../../../public/uploads/' . $currentImage;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            return "";
        } else {
            return $_POST["current_image"];
        }
    }
}
