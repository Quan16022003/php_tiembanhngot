<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\CategoriesModel;

class CategoriesController extends Controller
{
    public function __construct()
    {
        parent::__construct('Admin');
    }

    public function getById($id): void
    {
        if (is_array($id) && isset($id['categoryID'])) {
            $id = $id['categoryID'];
        }
        $model = new CategoriesModel();
        $category = $model->getCategoriesById($id);

        if ($category) {
            parent::render('categories/categories_edit', ['category' => $category]);
        } else {
            echo "Không tìm thấy danh mục!";
            var_dump(debug_backtrace());
        }
    }

    public function update(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $categoryId = $_POST["categoryId"];
            $categoryName = $_POST["categoryName"];

            $model = new CategoriesModel();
            $success = $model->updateCategory($categoryId, $categoryName);

            if ($success) {
                $reloadPage = true;
            } else {
                echo "Cập nhật danh mục thất bại!";
            }
        }

        if (isset($reloadPage) && $reloadPage) {
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function index(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $categoriesPerPage = 10;
        $offset = ($page - 1) * $categoriesPerPage;
        $model = new CategoriesModel();
        $categories = $model->getCategories($offset, $categoriesPerPage);
        $totalCategories = $model->getTotalCategories();
        $totalPages = ceil($totalCategories / $categoriesPerPage);
        $this->render('categories/categories', ['categories' => $categories, 'totalPages' => $totalPages, 'currentPage' => $page]);
    }

    public function openCreate(): void
    {
        $model = new CategoriesModel();
        $categoryId = $model->generateCategoryId();
        $this->render('categories/categories_create', ['categoryId' => $categoryId]);
    }

    public function create(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $categoryId = $_POST["categoryId"];
            $categoryName = $_POST["categoryName"];

            $model = new CategoriesModel();
            $success = $model->create($categoryId, $categoryName);

            if ($success) {
                header("Location: /admin/categories");
                echo "Thêm danh mục thành công!";
                exit;
            } else {
                echo "Thêm danh mục thất bại!";
            }
        }
    }

    public function delete(): void
    {
        $categoryId = $_POST['categoryId'];

        $model = new CategoriesModel();
        $success = $model->delete($categoryId);

        if ($success) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false]);
        }
    }


    public function getTotalCategories(): int
    {
        $query = "SELECT COUNT(*) as total FROM categories";
        $statement = $this->db->prepare($query);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return (int)$result['total'];
    }

    public function search(): void
    {
        $search = $_POST['search'];
        $model = new CategoriesModel();
        $categories = $model->search($search);
        $this->render('categories/categories', ['categories' => $categories]);
    }
}
