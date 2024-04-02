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

    public function getAll($productID)
    {
        // Gọi phương thức trong Model để lấy thông tin sản phẩm
        $model = new AdminProductsModel();
        $product = $model->getProductByID($productID); // Giả sử có phương thức getProductByID trong Model

        // Chuyển đổi thông tin sản phẩm thành định dạng JSON và trả về
        echo json_encode($product);
    }


    public function index(): void
    {
        // Tạo một thể hiện của model
        $model = new AdminProductsModel();
        // Lấy danh sách sản phẩm từ cơ sở dữ liệu
        $products = $model->index();

        if ($products === false) {
            echo "Không có sản phẩm nào được tìm thấy!";
            return;
        }

        // Truyền danh sách sản phẩm vào view
        $this->render('products', ['products' => $products]);

//        echo "<h1>Danh sách sản phẩm:</h1>";
//        echo "<ul>";
//        foreach ($products as $product) {
//            echo "<li>{$product['id']} - {$product['category_id']} - {$product['name']} - {$product['content']} - {$product['price']} - {$product['discount']} - {$product['image_link']} - {$product['stock']} - {$product['created_at']} - {$product['view']}</li>";
//        }
//        echo "</ul>";
    }

    public function add(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Lấy dữ liệu từ form
            $productID = $_POST["productID"];
            $productCategoryID = $_POST["productCategoryID"];
            $productName = $_POST["productName"];
            $productPrice = $_POST["productPrice"];
            $productQuantity = $_POST["productQuantity"];

            // Kiểm tra dữ liệu và thực hiện thêm vào cơ sở dữ liệu
            $model = new AdminProductsModel();
            $success = $model->insert($productID, $productCategoryID, $productName, $productPrice, $productQuantity);

            // Kiểm tra kết quả và điều hướng
            if ($success) {
                // Thêm sản phẩm thành công, bạn có thể điều hướng hoặc hiển thị thông báo thành công ở đây
                echo "Thêm sản phẩm thành công!";
                header("Location: /admin/products");
            } else {
                // Thêm sản phẩm thất bại, bạn có thể điều hướng hoặc hiển thị thông báo lỗi ở đây
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


}
