<?php

namespace App\Controllers\Admin;

use App\Models\AdminDashboardModel;
use App\Models\CategoriesModel;
use Core\Controller;

class DashboardController extends AdminController
{
    private $adminDashboardModel;
    private $categoriesModel;
    private $years;
    private $selectedYear;
    private $startDate;
    private $endDate;
    private $selectedCategory;

    public function __construct()
    {
        parent::__construct();
        $this->adminDashboardModel = new AdminDashboardModel();
        $this->categoriesModel = new CategoriesModel();
        $this->initData();
    }

    private function initData(): void
    {
        // Kiểm tra và gán giá trị cho các biến
        $this->startDate = isset($_GET['start_date']) && $_GET['start_date'] ? date('Y-m-d', strtotime($_GET['start_date'])) : null;
        $this->endDate = isset($_GET['end_date']) && $_GET['end_date'] ? date('Y-m-d', strtotime($_GET['end_date'])) : null;
        $this->selectedCategory = isset($_GET['categories']) && $_GET['categories'] !== null ? $_GET['categories'] : null;
        // Gọi phương thức để lấy dữ liệu từ model và khởi tạo các biến khác
        $this->selectedYear = date('Y');
    }

    public function listProductSales(): void
    {
        try {
            if ($this->selectedCategory === 'all') {
                $this->selectedCategory = null;
            }
            // Lấy dữ liệu từ model
            $totalSoldByType = $this->adminDashboardModel->getTotalSoldProductsByType($this->startDate, $this->endDate, $this->selectedCategory);

            // Lấy danh mục từ model
            $categories = $this->categoriesModel->getAllCategories();

            // Render view với dữ liệu đã lấy được
            parent::render('dashboard/products', [
                'years' => $this->years,
                'selectedYear' => $this->selectedYear,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'selectedCategory' => $this->selectedCategory,
                'totalSoldByType' => $totalSoldByType,
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            // Xử lý ngoại lệ
            $this->render($e);
        }
    }

    // Các phương thức khác có thể sử dụng các biến years, selectedYear, startDate, endDate, selectedCategory tương tự như trong phương thức topProductsOfAWeek()

    // Các phương thức phụ trợ và xử lý ngoại lệ
}
