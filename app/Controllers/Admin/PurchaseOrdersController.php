<?php

namespace App\Controllers\Admin;

use App\Models\AdminProductsModel;
use App\Models\ProductModel;
use App\Models\PurchaseOrderDetailModel;
use App\Models\PurchaseOrderModel;
use App\Models\SupplierModel;

class PurchaseOrdersController extends AdminController
{
    public function __construct()
    {
        parent::__construct('Admin');
    }

    public function index()
    {
        $status = $_GET['status'] ?? '';

        $pos = match ($status) {
            '' => PurchaseOrderModel::getAllPurchaseOrders(),
            default => PurchaseOrderModel::getAllPurchaseOrdersByStatus($status),
        };

        $data = [
            'all_status' => PurchaseOrderModel::getAllPOStatus(),
            'status_active' => $status,
            'purchase_orders' => $pos
        ];
        parent::render('PurchaseOrders/index', $data);
    }

    public function create()
    {
        $suppliers = SupplierModel::getAllSuppliers();
        parent::render('PurchaseOrders/create', ['suppliers' => $suppliers]);
    }

    public function store()
    {
        $po = new PurchaseOrderModel();
        $this->setPurchaseOrderData($po);
        $po->save();
        $poID = $po->getId();
        $this->storePurchaseOrderDetails($poID);
        header("Location: /admin/purchase_orders/$poID");
    }

    public function show($var)
    {
        $id = $var['id'];
        $po = PurchaseOrderModel::getPurchaseOrderByID($id);
        $details = PurchaseOrderDetailModel::getAllPurchaseOrderDetailByPoID($id);

        $nextAction = match((new PurchaseOrderModel($po))->getStatus()) {
            1 => "Xác nhân đặt hàng",
            2 => "Xác nhận giao hàng",
            3 => "Đã nhận được hàng",
            4 => "Đóng đơn hàng",
            default => ""
        };

        $data = [
            'PO' => $po,
            'products' => $details,
            'nextAction' => $nextAction
        ];
        parent::render('PurchaseOrders/show', $data);
    }

    public function edit($var)
    {
        $id = $var['id'];
        $po = PurchaseOrderModel::getPurchaseOrderByID($id);
        $details = PurchaseOrderDetailModel::getAllPurchaseOrderDetailByPoID($id);
        $productSelects = (new AdminProductsModel())->getAllProducts();
        $data = [
            'PO' => $po,
            'products' => $details,
            'productSelects' => $productSelects,
        ];
        parent::render('PurchaseOrders/edit', $data);
    }

    public function update($var)
    {
        $id = $var['id'];
        $po = new PurchaseOrderModel(PurchaseOrderModel::getPurchaseOrderById($id));
        $this->setPurchaseOrderData($po);
        $po->save();
        if ($po->getStatus() == 1) {
            PurchaseOrderDetailModel::deletePODByPOId($po->getId());
            $this->storePurchaseOrderDetails($po->getId());
        }
        header("Location: /admin/purchase_orders/{$po->getId()}");
    }

    private function setPurchaseOrderData($po)
    {
        if (isset($_POST['supplier_name'])) {
            $po->setSupplierCompanyName($_POST['supplier_name']);
            $po->setSupplierContactName($_POST['supplier_contact_name']);
            $po->setSupplierEmail($_POST['supplier_email']);
            $po->setSupplierPhone($_POST['supplier_phone']);
            $po->setSupplierAddress($_POST['supplier_address']);
        }
        $po->setDeliveryDate($_POST['delivery_date']);
        $po->setPaymentMethod($_POST['payment_method']);
        $po->setShippingMethod($_POST['shipping_method']);
        $po->setShippingAddress("123 abc");
        $po->setShippingTerms($_POST['shipping_terms']);
        $po->setShippingFee($_POST['shipping_fee']);
        $po->setTax($_POST['tax']);
        $po->setNotes($_POST['notes']);
    }

    private function storePurchaseOrderDetails($poID)
    {
        $productIDList = $_POST['product_id'];
        $productPriceList = $_POST['product_price'];
        $productQuantityList = $_POST['product_quantity'];
        for ($i = 0; $i < count($productIDList); $i++) {
            $detail = new PurchaseOrderDetailModel();
            $detail->setPoId($poID);
            $detail->setProductId($productIDList[$i]);
            $detail->setQuantity($productQuantityList[$i]);
            $detail->setUnitPrice($productPriceList[$i]);
            $detail->setTotalPrice($productQuantityList[$i] * $productPriceList[$i]);
            $detail->save();
        }
    }

    public function updateStatus($var) {
        $id = $var['id'];
        $po = new PurchaseOrderModel(PurchaseOrderModel::getPurchaseOrderById($id));

        PurchaseOrderModel::updatePOStatus($id, $po->getStatus() + 1);
        echo "OK";
    }
}
