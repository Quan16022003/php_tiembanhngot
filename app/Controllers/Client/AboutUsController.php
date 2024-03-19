<?php

namespace App\Controllers\Client;

class AboutUsController extends Controller
{
    public function index(): void
    {
        parent::render("about-us");
    }
}
