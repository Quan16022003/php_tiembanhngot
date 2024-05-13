<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\AdminDashboardModel;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct('Admin');

    }

    public function TopProductsOfAWeek(): void
    {
        $model = new AdminDashboardModel();
        $years = $model->getYearsFromDatabase();

        $startDate = null;
        $endDate = null;
        if (isset($_GET['week']) && $_GET['week'] !== 'all') {
            [$startDate, $endDate] = explode('|', $_GET['week']);
            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));

        }
        $totalSoldByType = $model->getTotalSoldProductsByType($startDate, $endDate);

//        if (empty($totalSoldByType)) {
//            echo "Không có dữ liệu tổng số lượng sản phẩm đã bán theo loại!";
//            return;
//        }

        // Mặc định chọn năm hiện tại
        $selectedYear = date('Y');

        // Pass dữ liệu vào view
        $data = [
            'years' => $years,
            'selectedYear' => $selectedYear,
            'totalSoldByType' => $totalSoldByType
        ];
//        echo json_encode($data);
        parent::render('dashboard/dashboard', $data);
    }
}
