<?php

namespace App\Controllers\Admin;

use App\Models\AdminAuthModel;
use App\Models\AdminUserModel;
use Core\Controller;
use JetBrains\PhpStorm\NoReturn;

class AuthController extends Controller
{
    private AdminAuthModel $admin_login_model;
    public function __construct()
    {
        parent::__construct('Admin');
        $this->admin_login_model = new AdminAuthModel();
    }

    public function index(): void
    {
        if (isset($_SESSION['admin_id'])) {
            header('Location: /admin/home');
            exit;
        }

        $this->render('login');
    }
    public function login(): void
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($row = $this->admin_login_model->verify($username, $password)) {
            // Login successful
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['permissions'] = (new AdminUserModel())->getPermissionByAdminId($row['id']);
            echo "success";
        } else {
            echo "Tài khoản hoặc mật khẩu không đúng";
        }
    }
    public function logout():void
    {
        if (isset($_SESSION)) {
            session_unset();
            session_destroy();
        }
        header('Location: /admin/login');
        exit();
    }

    public function showProfilePage()
    {
        $model = new AdminUserModel();
        $id = $_SESSION['admin_id'];
        $user = $model->getUserById($id);
        $this->render('Accounts/profile', ['user' => $user, 'admin' => $_SESSION]);
    }
}