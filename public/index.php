<?php

require_once '../vendor/autoload.php';

use FastRoute\RouteCollector;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use function FastRoute\simpleDispatcher;

$loader = new FilesystemLoader('../app/Views');
$twig = new Environment($loader);

// Define routes
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\Controllers\PageController', 'index']);
    $r->addRoute('GET', '/about', ['App\Controllers\PageController', 'about']);
    $r->addRoute('GET', '/contact', ['App\Controllers\PageController', 'contact']);
});

// Dispatch the request
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

// Handle the response
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo '404 - Not Found';
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