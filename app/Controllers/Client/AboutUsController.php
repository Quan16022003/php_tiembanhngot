<?php

namespace App\Controllers\Client;

class AboutUsController extends UserController
{
    public function index(): void
    {
        parent::render("about-us");
    }
}
