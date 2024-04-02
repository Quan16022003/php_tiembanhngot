<?php

require_once '../vendor/autoload.php';

use FastRoute\RouteCollector;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use function FastRoute\simpleDispatcher;

// Define routes
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
//    ADMIN ROUTES START
    $r->addRoute('GET', '/admin', ['App\Controllers\Admin\HomeController', 'index']);
    $r->addRoute('GET', '/admin/login', ['App\Controllers\Admin\AuthController', 'index']);
    $r->addRoute('POST', '/admin/login', ['App\Controllers\Admin\AuthController', 'login']);
    $r->addRoute('GET', '/admin/logout', ['App\Controllers\Admin\AuthController', 'logout']);

    $r->addRoute('GET', '/admin/home', ['App\Controllers\Admin\HomeController', 'index']);

    $r->addRoute('GET', '/admin/user', ['App\Controllers\Admin\UserController', 'index']);
    $r->addRoute('GET', '/admin/user/add', ['App\Controllers\Admin\UserController', 'showAddUserPage']);
    $r->addRoute('POST', '/admin/user/add', ['App\Controllers\Admin\UserController', 'addUser']);
    $r->addRoute('POST', '/admin/user/search', ['App\Controllers\Admin\UserController', 'searchUser']);

    //    ADMIN ROUTES END
    $r->addRoute('GET', '/', ['App\Controllers\Client\HomeClientController', 'index']);
    $r->addRoute('GET', '/about-us', ['App\Controllers\Client\AboutUsClientController', 'index']);
    $r->addRoute('GET', '/contact', ['App\Controllers\Client\ContactClientController', 'index']);
    $r->addRoute('GET', '/products', ['App\Controllers\Client\ProductClientController', 'index']);
    $r->addRoute('GET', '/products/{id:\d+}', ['App\Controllers\Client\ProductClientController', 'index']);
    $r->addRoute('GET', '/search', ['App\Controllers\Client\SearchController', 'index']);
});


// Dispatch the request
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
// Handle the response
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
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