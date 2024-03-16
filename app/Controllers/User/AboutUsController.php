<?php

namespace App\Controllers\User;

class AboutUsController extends UserController
{
    public function index(): void
    {
        parent::render("about-us");
    }
}
