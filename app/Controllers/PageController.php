<?php

namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class PageController
{
    public function index(): void
    {
        global $twig;

        // Render the template
        echo $twig->render('home.twig');
    }
    public function about(): void
    {
        global $twig;

        // Render the template
        echo $twig->render('about.twig');
    }
    public function contact(): void
    {
        global $twig;

        // Render the template
        echo $twig->render('contact.twig');
    }
}
