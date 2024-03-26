<?php

namespace App\Controllers\Client;

class AboutUsClientController extends ClientController
{
    public function index(): void
    {
        parent::render("about-us");
    }
}
