<?php

namespace App\Controllers\Client;

abstract class Controller  extends \Core\Controller
{
    public function __construct()
    {
        parent::__construct('Client');
    }
}