<?php

namespace App\Controllers\Client;

class AboutUsController extends ClientController
{
    public function index(): void
    {
        parent::render("about-us");
    }
}
