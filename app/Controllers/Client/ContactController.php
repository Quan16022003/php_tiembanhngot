<?php

namespace App\Controllers\Client;

class ContactController extends UserController
{
    public function index(): void
    {
        parent::render(page:'contact');
    }
}