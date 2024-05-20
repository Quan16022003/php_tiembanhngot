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
        $loader = new FilesystemLoader('../app/views/' . $r);
        $this->twig = new Environment($loader);
        $this->twig->addFilter(new TwigFilter('formatCurrency', function ($number) {
            return number_format($number, 0, '.', '.');
        }));
        $this->twig->addFilter(new TwigFilter('formatGender', function ($gender) {
            switch ($gender) {
                case 'male':
                    return 'Nam';
                case 'female':
                    return 'Nữ';
                default:
                    return 'Khác';
            }
        }));
    }

    public function render($page, $data = []): void
    {
        try {
            echo $this->twig->render("$page.twig", $data);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            print_r($e->getMessage());
        }
    }

}