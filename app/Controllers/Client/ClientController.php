<?php

namespace App\Controllers\Client;

abstract class ClientController  extends \Core\Controller
{
    public function __construct()
    {
        parent::__construct('Client');
    }

    public function render($page, $data = []): void
    {
        $data['session'] = $_SESSION;
        parent::render($page, $data);
    }
}