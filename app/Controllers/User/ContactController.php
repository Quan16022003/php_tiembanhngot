<?php

namespace App\Controllers\User;

class ContactController extends UserController
{
    public function index(): void
    {
        parent::render(page:'contact');
    }
}