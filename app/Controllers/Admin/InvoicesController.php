<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\AdminInvoicesModel;

class InvoicesController extends AdminController
{
    public function __construct()
    {
        parent::__construct('Admin');
    }

    public function getById($id): void
    {
        if (is_array($id) && isset($id['invoiceId'])) {
            $id = $id['invoiceId'];
        }

        $model = new AdminInvoicesModel();
        $invoice_detail = $model->getInvoiceDetailsByID($id);
        if ($invoice_detail) {
            parent::render('invoices/invoices_detail', ['invoice_detail' => $invoice_detail]);
        } else {
            echo "Không tìm thấy thông tin chi tiết hóa đơn!";
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
            $invoicestock = $_POST["invoicestock"];
            $productImage = $_POST["productImage"];
            var_dump($productId, $productCategoryId, $productContent, $productName, $productPrice, $invoicestock, $productImage);
            $model = new AdmininvoicesModel();
            $success = $model->update($productId, $productCategoryId, $productName, $productContent, $productImage, $productPrice, $invoicestock);
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
        $model = new AdmininvoicesModel();
        $invoices = $model->index();

        if ($invoices === false) {
            echo "Không có sản phẩm nào được tìm thấy!";
            return;
        }
        $this->render('invoices/invoices', ['invoices' => $invoices]);
    }

    public function indexPage(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $invoicesPerPage = 10;
        $offset = ($page - 1) * $invoicesPerPage;
        $model = new AdminInvoicesModel();
        $invoices = $model->getInvoices($offset, $invoicesPerPage);
        $totalInvoices = $model->getTotalInvoices();
        $totalPages = ceil($totalInvoices / $invoicesPerPage);
        $this->render('invoices/invoices', ['invoices' => $invoices, 'totalPages' => $totalPages, 'currentPage' => $page]);
    }

    public function create(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $productId = $_POST["productId"];
            $productCategoryId = $_POST["productCategoryId"];
            $productName = $_POST["productName"];
            $productPrice = $_POST["productPrice"];
            $productContent = $_POST["productContent"];

            $model = new AdmininvoicesModel();
            $success = $model->create($productId, $productCategoryId, $productName, $productPrice, $productContent);

            if ($success) {
                echo "Thêm sản phẩm thành công!";
                header("Location: /admin/invoices");
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

            $model = new AdmininvoicesModel();
            $success = $model->insert($productId, $productCategoryId, $productName, $productPrice, $productQuantity);

            if ($success) {
                echo "Thêm sản phẩm thành công!";
                header("Location: /admin/invoices");
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
        $model = new AdmininvoicesModel();
        $success = $model->delete($productID);

        if ($success) {
            echo "Xóa sản phẩm thành công!";
            // Cập nhật lại giao diện hoặc thực hiện hành động cần thiết sau khi xóa thành công
            header("Location: /admin/invoices");
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
        $model = new AdmininvoicesModel();

        // Gọi model để thực hiện tìm kiếm
        $invoices = $model->search($text);

        // Trả về kết quả dưới dạng JSON
        echo json_encode($invoices);
    }


}
