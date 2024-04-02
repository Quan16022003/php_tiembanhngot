<?php

namespace Core;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

class Controller
{
    private Environment $twig;
    public function __construct($r)
    {
        $loader = new FilesystemLoader('../app/Views/'.$r);
        $this->twig = new Environment($loader);
        $this->twig->addFilter(new TwigFilter('formatCurrency', function ($number) {
            return number_format($number, 0, '.', '.');
        }));
    }

    public function render($page, $data=[]): void
    {
        try {
            echo $this->twig->render("$page.twig", $data);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            print_r($e->getMessage());
        }
    }

}