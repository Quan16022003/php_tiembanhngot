<?php

namespace App\Controllers\Admin;

class HomeController extends AdminController
{

    #[\Override] function index(): void
    {
        parent::render('home');
    }

    function showBlankPage() {
        parent::render('blank-page');
    }
}