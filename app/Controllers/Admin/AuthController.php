<?php

namespace App\Controllers\Admin;

use App\Models\AdminAuthModel;
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
        session_start();
        if (isset($_SESSION['admin_logged_in'])) {
            header('Location: /admin/home');
            exit;
        }

        $this->render('login');
    }
    public function login(): void
    {
        session_start();
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($row = $this->admin_login_model->verify($username, $password)) {
            // Login successful
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_name'] = $row['name'];
            header('Location: /admin/home');
        } else {
            // Login failed
            $this->render('login', ['error' => 'Tài khoản hoặc mật khẩu không đúng!']);
        }
    }
    #[NoReturn] public function logout():void
    {
        session_start();
        if (isset($_SESSION)) {
            session_unset();
            session_destroy();
        }
        header('Location: /admin/login');
        exit();
    }
}