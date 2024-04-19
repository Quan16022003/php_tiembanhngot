<?php

require_once '../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

// Define routes
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
// ADMIN ROUTES START
    $r->addRoute('GET', '/admin', ['App\Controllers\Admin\HomeController', 'index']);
    // LOGIN/LOGOUT
    $r->addRoute('GET', '/admin/login', ['App\Controllers\Admin\AuthController', 'index']);
    $r->addRoute('POST', '/admin/login', ['App\Controllers\Admin\AuthController', 'login']);
    $r->addRoute('GET', '/admin/logout', ['App\Controllers\Admin\AuthController', 'logout']);
    // DASHBOARD
    $r->addRoute('GET', '/admin/dashboard', ['App\Controllers\Admin\DashboardController', 'index']);
    // PRODUCTS
    $r->addRoute('GET', '/admin/products', ['App\Controllers\Admin\ProductsController', 'indexPage']);
    $r->addRoute('GET', '/admin/products/create', ['App\Controllers\Admin\ProductsController', 'openCreate']);
    $r->addRoute('POST', '/admin/products/create', ['App\Controllers\Admin\ProductsController', 'create']);
    $r->addRoute('GET', '/admin/products/add', ['App\Controllers\Admin\ProductsController', 'openAdd']);
    $r->addRoute('POST', '/admin/products/add', ['App\Controllers\Admin\ProductsController', 'add']);
    $r->addRoute('POST', '/admin/products/delete/{productID}', ['App\Controllers\Admin\ProductsController', 'delete']);
    $r->addRoute('GET', '/admin/products/edit/{productID}', ['App\Controllers\Admin\ProductsController', 'getById']);
    $r->addRoute('POST', '/admin/products/edit/{productID}', ['App\Controllers\Admin\ProductsController', 'update']);
    $r->addRoute('POST', '/admin/products/search', ['App\Controllers\Admin\ProductsController', 'search']);
    // INVOICES
    $r->addRoute('GET', '/admin/invoices', ['App\Controllers\Admin\InvoicesController', 'indexPage']);
    // HOMES
    $r->addRoute('GET', '/admin/home', ['App\Controllers\Admin\HomeController', 'index']);
    // USERS
    $r->addRoute('GET', '/admin/user', ['App\Controllers\Admin\UserController', 'index']);
    $r->addRoute('GET', '/admin/users/add', ['App\Controllers\Admin\UserController', 'showAddUserPage']);
    $r->addRoute('POST', '/admin/users/add', ['App\Controllers\Admin\UserController', 'addUser']);
    $r->addRoute('POST', '/admin/users/search', ['App\Controllers\Admin\UserController', 'searchUser']);

// ADMIN ROUTES END

// CLIENT ROUTES START
    $r->addRoute('GET', '/', ['App\Controllers\Client\HomeClientController', 'index']);
    $r->addRoute('GET', '/about-us', ['App\Controllers\Client\AboutUsController', 'index']);
    $r->addRoute('GET', '/contact', ['App\Controllers\Client\ContactController', 'index']);
    $r->addRoute('GET', '/products', ['App\Controllers\Client\ProductClientController', 'indexPage']);
    $r->addRoute('GET', '/products/{id:\d+}', ['App\Controllers\Client\ProductClientController', 'index']);
    $r->addRoute('GET', '/search', ['App\Controllers\Client\SearchController', 'index']);
// CLIENT ROUTES END
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