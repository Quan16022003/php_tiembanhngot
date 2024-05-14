<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\OrderModel;
use App\Models\OrderDetailsModel;
use App\Models\CartModel;

class OrdersController extends Controller
{
    public function __construct()
    {
        parent::__construct('Admin');
    }

    public function getById($id): void
    {
        if (is_array($id) && isset($id['orderId'])) {
            $id = $id['orderId'];
        }

        $model = new OrderModel();
        $order = $model->getOrderByID($id);
        if ($order) {
            parent::render('orders_update', ['order' => $order]);
        } else {
            echo "Không tìm thấy sản phẩm!";
            var_dump(debug_backtrace());
        }
    }

    public function update(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $orderId = $_POST["orderId"];
            $orderCategoryId = $_POST["orderCategoryId"];
            $orderContent = $_POST["orderContent"];
            $orderName = $_POST["orderName"];
            $orderPrice = $_POST["orderPrice"];
            $orderStock = $_POST["orderStock"];
            $orderImage = $_POST["orderImage"];
            $model = new OrderModel();
            $success = $model->update($orderId, $orderCategoryId, $orderContent, $orderName, $orderPrice, $orderStock, $orderImage);
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
        $model = new OrderModel();
        $orders = $model->index();

        if ($orders === false) {
            echo "Không có sản phẩm nào được tìm thấy!";
            return;
        }
        $this->render('orders', ['orders' => $orders]);
    }

    public function indexPage(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $ordersPerPage = 10;
        $offset = ($page - 1) * $ordersPerPage;
        $model = new OrderModel();
        $orders = $model->getAllOrders($offset, $ordersPerPage);
        $totalorders = $model->getTotalOrders();
        $totalPages = ceil($totalorders / $ordersPerPage);
        $this->render('orders', ['orders' => $orders, 'totalPages' => $totalPages, 'currentPage' => $page]);
    }

    public function openAdd(): void
    {
        $this->render('orders_add');
    }

    public function create(): void
    {
        $order = new OrderModel();
        $this->getOrderData($order);
        $order->save();
        $orderId = $order->getId();
        $this->getOrderDetailsFromCart($orderId);
//        header("Location: /");
    }

    private function getOrderData($order)
    {
        $order->setTotalPrice($_POST['total']);
        $order->setUserID($_POST['user_id']);
        $order->setAddress1($_POST['address1']);
        $order->setAddress2($_POST['address2']);
        $order->setPhoneNumber($_POST['phone']);
    }

    private function getOrderDetailsFromCart($orderId)
    {
        $model = new CartModel();
        $cartItems = $model->getCartByUserId($_POST['user_id']);
        var_dump($cartItems);
        foreach ($cartItems as $item) {
            $detail = new OrderDetailsModel();
            $detail->setOrderId($orderId);
            $detail->setProductId($item['product_id']);
            $detail->setQuantity($item['quantity']);
            $detail->save();
        }
    }

    public function delete(): void
    {
        // Truy xuất tham số orderID từ yêu cầu POST
        $orderID = $_POST['orderID'];

        // Tiếp tục xử lý xóa sản phẩm với orderID nhận được
        $model = new AdminordersModel();
        $success = $model->delete($orderID);

        if ($success) {
            echo "Xóa sản phẩm thành công!";
            // Cập nhật lại giao diện hoặc thực hiện hành động cần thiết sau khi xóa thành công
            header("Location: /admin/orders");
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
        $model = new AdminordersModel();

        // Gọi model để thực hiện tìm kiếm
        $orders = $model->search($text);

        // Trả về kết quả dưới dạng JSON
        echo json_encode($orders);
    }


}
