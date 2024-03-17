<?php

namespace App\Controllers\Client;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class UserController {
    public function render($page='home', $data=[]): void
    {
        $loader = new FilesystemLoader('../app/Views/Client');
        $twig = new Environment($loader);
        try {
            echo $twig->render("$page.twig", $data);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            print_r($e->getMessage());
        }
    }
}