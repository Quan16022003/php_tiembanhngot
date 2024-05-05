<?php

namespace App\Controllers\Client;

use Core\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct('Client');
    }

    public function index(): void
    {
        session_start();
        if (isset($_SESSION['customer_id'])) {
            header('Location: /');
            exit;
        }

        $this->render('login');
    }
}