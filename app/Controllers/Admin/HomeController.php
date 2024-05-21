<?php

namespace App\Controllers\Admin;

class HomeController extends AdminController
{

    function index(): void
    {
        parent::render('home');
    }

    function showBlankPage() {
        parent::render('blank-page');
    }
}