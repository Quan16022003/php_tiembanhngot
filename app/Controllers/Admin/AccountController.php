<?php

namespace App\Controllers\Admin;

class AccountController extends AdminController
{
    private $accModel;
    public function __construct()
    {
        parent::__construct();
    }

    #[\Override] function index(): void
    {
        parent::render('account');
    }
}