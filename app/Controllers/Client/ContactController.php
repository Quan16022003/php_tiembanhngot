<?php

namespace App\Controllers\Client;

class ContactController extends Controller
{
    public function index(): void
    {
        parent::render(page:'contact');
    }
}