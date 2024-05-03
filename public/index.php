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
    $r->addRoute('POST', '/admin/products/edit/{productID}/delete-image', ['App\Controllers\Admin\ProductsController', 'deleteImage']);
    // INVOICES
    $r->addRoute('GET', '/admin/invoices', ['App\Controllers\Admin\InvoicesController', 'indexPage']);
    $r->addRoute('GET', '/admin/invoices/view/{invoiceId}', ['App\Controllers\Admin\InvoicesController', 'getById']);
    // HOMES
    $r->addRoute('GET', '/admin/home', ['App\Controllers\Admin\HomeController', 'index']);
    // CUSTOMERS
    $r->addRoute('GET', '/admin/customers', ['App\Controllers\Admin\CustomerController', 'index']);
    $r->addRoute('GET', '/admin/customers/create', ['App\Controllers\Admin\CustomerController', 'openCreate']);
    $r->addRoute('POST', '/admin/customers/create', ['App\Controllers\Admin\CustomerController', 'create']);
    $r->addRoute('GET', '/admin/customers/view/{customerId}', ['App\Controllers\Admin\CustomerController', 'getById']);
    // USERS
    $r->addRoute('GET', '/admin/user', ['App\Controllers\Admin\UserController', 'index']);
    $r->addRoute('GET', '/admin/user/add', ['App\Controllers\Admin\UserController', 'showAddUserPage']);
    $r->addRoute('POST', '/admin/user/add', ['App\Controllers\Admin\UserController', 'addUser']);
    $r->addRoute('GET', '/admin/user/edit', ['App\Controllers\Admin\UserController', 'showEditUserPage']);
    $r->addRoute('POST', '/admin/user/edit', ['App\Controllers\Admin\UserController', 'editUser']);
    $r->addRoute('POST', '/admin/user/delete', ['App\Controllers\Admin\UserController', 'deleteUser']);
    $r->addRoute('POST', '/admin/user/search', ['App\Controllers\Admin\UserController', 'searchUser']);
    // PERMISSIONS
    $r->addRoute('GET', '/admin/permissions', ['App\Controllers\Admin\UserController', 'showPermissionPage']);
    $r->addRoute('GET', '/admin/permissions/add', ['App\Controllers\Admin\UserController', 'showAddPermissionPage']);
    $r->addRoute('POST', '/admin/permissions/add', ['App\Controllers\Admin\UserController', 'addPermission']);
    $r->addRoute('GET', '/admin/permissions/edit', ['App\Controllers\Admin\UserController', 'showEditPermissionPage']);
    $r->addRoute('POST', '/admin/permissions/edit', ['App\Controllers\Admin\UserController', 'editPermission']);
    $r->addRoute('POST', '/admin/permissions/delete', ['App\Controllers\Admin\UserController', 'deletePermission']);

// ADMIN ROUTES END

// CLIENT ROUTES START
    $r->addRoute('GET', '/', ['App\Controllers\Client\HomeClientController', 'index']);
    $r->addRoute('GET', '/about-us', ['App\Controllers\Client\AboutUsController', 'index']);
    $r->addRoute('GET', '/contact', ['App\Controllers\Client\ContactController', 'index']);
    $r->addRoute('GET', '/products', ['App\Controllers\Client\ProductClientController', 'indexPage']);
    $r->addRoute('GET', '/products/{id:\d+}', ['App\Controllers\Client\ProductClientController', 'index']);
    $r->addRoute('GET', '/products', ['App\Controllers\Client\ProductController', 'index']);
    $r->addRoute('GET', '/products/{id:\d+}', ['App\Controllers\Client\ProductController', 'index']);
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