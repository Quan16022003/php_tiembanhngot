<?php

namespace App\Controllers\Admin;

use App\Models\OrderModel;
use App\Models\UserModel;
use Core\Controller;
use App\Models\AdminCustomerModel;

class CustomerController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
    }

//    public function index(): void
//    {
//        $model = new AdminCustomerModel();
//        $customer = $model->index();
//
//        if ($customer === false) {
//            echo "Không có sản phẩm nào được tìm thấy!";
//            return;
//        }
//        $this->render('customers/customers', ['customer' => $customer]);
//    }

    public function index(): void
    {
        $customers = $this->userModel->getCustomerPurchaseSummary();
        $data = [
            'customers' => $customers
        ];
        parent::render('customers/index', $data);
    }

    public function show($vars) {
        $id = $vars['id'];
        $customer = $this->userModel->getCustomerSummary($id);
        $orders = OrderModel::getAllOrdersByUserId($id);
        $statuses = OrderModel::getAllStatusOrder();

        $data = [
            'customer' => $customer,
            'orders' => $orders,
            'statueses' => $statuses
        ];
        parent::render('customers/view', $data);
    }

    public function create(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $customerId = $_POST["customerId"];
            $customerCategoryId = $_POST["customerCategoryId"];
            $customerName = $_POST["customerName"];
            $customerPrice = $_POST["customerPrice"];
            $customerContent = $_POST["customerContent"];

            $model = new AdminCustomerModel();
            $success = $model->create($customerId, $customerCategoryId, $customerName, $customerPrice, $customerContent);

            if ($success) {
                echo "Thêm sản phẩm thành công!";
                header("Location: /admin/Customer");
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
            $customerId = $_POST["customerId"];
            $customerCategoryId = $_POST["customerCategoryId"];
            $customerName = $_POST["customerName"];
            $customerPrice = $_POST["customerPrice"];
            $customerQuantity = $_POST["customerQuantity"];

            $model = new AdminCustomerModel();
            $success = $model->insert($customerId, $customerCategoryId, $customerName, $customerPrice, $customerQuantity);

            if ($success) {
                echo "Thêm sản phẩm thành công!";
                header("Location: /admin/Customer");
            } else {
                echo "Thêm sản phẩm thất bại!";
            }
        }
    }

    public function delete(): void
    {
        // Truy xuất tham số customerId từ yêu cầu POST
        $customerId = $_POST['customerId'];

        // Tiếp tục xử lý xóa sản phẩm với customerId nhận được
        $model = new AdminCustomerModel();
        $success = $model->delete($customerId);

        if ($success) {
            echo "Xóa sản phẩm thành công!";
            // Cập nhật lại giao diện hoặc thực hiện hành động cần thiết sau khi xóa thành công
            header("Location: /admin/Customer");
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
        $model = new AdminCustomerModel();

        // Gọi model để thực hiện tìm kiếm
        $Customer = $model->search($text);

        // Trả về kết quả dưới dạng JSON
        echo json_encode($Customer);
    }


}
