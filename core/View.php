<?php

namespace Core;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class View
{
    protected Environment $twig;
    public function __construct($role='Client')
    {
        $loader = new FilesystemLoader('../app/Views/'.$role);
        $this->twig = new Environment($loader);
    }
    public function render($view, $data=[]): void
    {
        try {
            $this->twig->render($view, $data);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            print_r($e->getMessage());
        }
    }
}