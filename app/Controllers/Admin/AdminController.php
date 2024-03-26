<?php

namespace App\Controllers\Admin;

use Core\Controller;

abstract class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct('Admin');
        session_start();
        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: /admin/login');
            exit;
        }
    }
    abstract function index();
}