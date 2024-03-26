<?php

namespace App\Controllers\Admin;

use Core\Controller;
use Override;

class DashboardController extends AdminController
{
    #[Override] public function index(): void
    {
        parent::render('index', ['name' => $_SESSION['admin_name']]);
    }
}