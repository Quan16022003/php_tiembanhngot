<?php

namespace App\Controllers\Client;

class ContactClientController extends ClientController
{
    public function index(): void
    {
        parent::render(page:'contact');
    }
}