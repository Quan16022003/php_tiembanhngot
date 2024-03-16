<?php

namespace App\Controllers\Admin;
 use Twig\Environment;
 use Twig\Loader\FilesystemLoader;

 class AdminController
 {
     public function index(): void
     {
         $loader = new FilesystemLoader('../app/Views/Admin');
         $twig = new Environment($loader);
         echo $twig->render("login.twig");
     }
 }