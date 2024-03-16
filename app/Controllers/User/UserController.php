<?php

namespace App\Controllers\User;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class UserController {
    public function render($page='home', $data=[]): void
    {
        $loader = new FilesystemLoader('../app/Views/User');
        $twig = new Environment($loader);
        try {
            echo $twig->render("$page.twig", $data);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            print_r($e->getMessage());
        }
    }
}