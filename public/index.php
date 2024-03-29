<?php

require_once '../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

// Define routes
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    // ADMIN ROUTES START
    // LOGIN/LOGOUT
    $r->addRoute('GET', '/admin/login', ['App\Controllers\Admin\AuthController', 'index']);
    $r->addRoute('POST', '/admin/login', ['App\Controllers\Admin\AuthController', 'login']);
    $r->addRoute('GET', '/admin/logout', ['App\Controllers\Admin\AuthController', 'logout']);
    // DASHBOARD
    $r->addRoute('GET', '/admin/dashboard', ['App\Controllers\Admin\DashboardController', 'index']);
    // PRODUCTS
    $r->addRoute('GET', '/admin/products', ['App\Controllers\Admin\ProductsController', 'index']);
    $r->addRoute('POST', '/admin/products/add', ['App\Controllers\Admin\ProductsController', 'add']);
    $r->addRoute('POST', '/admin/products/delete/{productID}', ['App\Controllers\Admin\ProductsController', 'delete']);
    $r->addRoute('POST', '/admin/products/get/{productID}', ['App\Controllers\Admin\ProductsController', 'getAll']);

    // ADMIN ROUTES END

    // CLIENT ROUTES START
    $r->addRoute('GET', '/', ['App\Controllers\Client\HomeController', 'index']);
    $r->addRoute('GET', '/about-us', ['App\Controllers\Client\AboutUsController', 'index']);
    $r->addRoute('GET', '/contact', ['App\Controllers\Client\ContactController', 'index']);
    // CLIENT ROUTES END
});

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