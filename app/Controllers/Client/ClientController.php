<?php

namespace App\Controllers\Client;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

abstract class ClientController  extends \Core\Controller
{
    public function __construct()
    {
        parent::__construct('Client');
    }
}