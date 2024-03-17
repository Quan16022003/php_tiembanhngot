<?php

require_once '../vendor/autoload.php';

use FastRoute\RouteCollector;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use function FastRoute\simpleDispatcher;

// Define routes
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
//    ADMIN ROUTES START
    $r->addRoute('GET', '/admin/login', ['App\Controllers\Admin\AdminController', 'index']);
//    ADMIN ROUTES END
    $r->addRoute('GET', '/', ['App\Controllers\Client\HomeController', 'index']);
    $r->addRoute('GET', '/about-us', ['App\Controllers\Client\AboutUsController', 'index']);
    $r->addRoute('GET', '/contact', ['App\Controllers\Client\ContactController', 'index']);
});

//session_start();
//$isAdminPage = str_starts_with($_SERVER['REQUEST_URI'], '/admin');
//if ($isAdminPage && !isset($_SESSION['user'])) {
//    header('Location: /admin/login');
//}

// Dispatch the request
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

// Handle the response
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
//        $loader = new FilesystemLoader('../app/Views/Client');
//        $twig = new Environment($loader);
//        echo $twig->render('404.twig');
        echo '404 - Page Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo '405 - Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        [$controllerClass, $method] = $routeInfo[1];
        $vars = $routeInfo[2];
        $controller = new $controllerClass();
        $controller->$method($vars);
        break;
}