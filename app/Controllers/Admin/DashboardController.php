<?php

namespace App\Controllers\Admin;

use Core\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct('Admin');
    }
    public function index(): void
    {
        session_start();
        if (!$this->isLoggedIn()) {
            header('Location: /admin/login');
            exit;
        }
        parent::render('dashboard', ['name' => $_SESSION['admin_name']]);
    }
    public function isLoggedIn(): bool
    {
        return isset($_SESSION['admin_logged_in']);
    }
}