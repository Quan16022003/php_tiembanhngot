<?php

namespace App\Controllers\Client;

class ContactController extends ClientController
{
    public function index(): void
    {
        parent::render(page:'contact');
    }
}