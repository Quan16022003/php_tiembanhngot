<?php

require_once '../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
define('INC_ROOT', __DIR__);

// Define routes
$dispatcher = simpleDispatcher(function (RouteCollector $r) {

    $r->addGroup('/admin', function (RouteCollector $r) {
        $r->addRoute('GET', '', ['App\Controllers\Admin\HomeController', 'index']);

        // ACCOUNT
        $r->addRoute('GET', '/login', ['App\Controllers\Admin\AuthController', 'index']);
        $r->addRoute('POST', '/login', ['App\Controllers\Admin\AuthController', 'login']);
        $r->addRoute('GET', '/logout', ['App\Controllers\Admin\AuthController', 'logout']);
        $r->addRoute('GET', '/account/profile', ['App\Controllers\Admin\AuthController', 'showProfilePage']);

        // DASHBOARD
        $r->addRoute('GET', '/dashboard', ['App\Controllers\Admin\DashboardController', 'index']);

        // PRODUCTS
        $r->addGroup('/products', function (RouteCollector $r) {
            $r->addRoute('GET', '', ['App\Controllers\Admin\ProductsController', 'indexPage']);
            $r->addRoute('GET', '/create', ['App\Controllers\Admin\ProductsController', 'openCreate']);
            $r->addRoute('POST', '/create', ['App\Controllers\Admin\ProductsController', 'create']);
            $r->addRoute('GET', '/add', ['App\Controllers\Admin\ProductsController', 'openAdd']);
            $r->addRoute('POST', '/add', ['App\Controllers\Admin\ProductsController', 'add']);
            $r->addRoute('POST', '/delete/{productID}', ['App\Controllers\Admin\ProductsController', 'delete']);
            $r->addRoute('GET', '/edit/{productID}', ['App\Controllers\Admin\ProductsController', 'getById']);
            $r->addRoute('POST', '/edit/{productID}', ['App\Controllers\Admin\ProductsController', 'update']);
            $r->addRoute('POST', '/search', ['App\Controllers\Admin\ProductsController', 'search']);
        });

        // INVOICES
        $r->addGroup('/invoices', function (RouteCollector $r) {
            $r->addRoute('GET', '', ['App\Controllers\Admin\InvoicesController', 'indexPage']);
            $r->addRoute('GET', '/view/{invoiceId}', ['App\Controllers\Admin\InvoicesController', 'getById']);
        });

        // SUPPLIERS
        $r->addGroup('/suppliers', function (RouteCollector $r) {
            $r->addRoute('GET', '', ['App\Controllers\Admin\SupplierController', 'index']);
            $r->addRoute('GET', '/create', ['App\Controllers\Admin\SupplierController', 'create']);
            $r->addRoute('POST', '/store', ['App\Controllers\Admin\SupplierController', 'store']);
            $r->addRoute('GET', '/{id:\d+}', ['App\Controllers\Admin\SupplierController', 'show']);
            $r->addRoute('GET', '/{id:\d+}/edit', ['App\Controllers\Admin\SupplierController', 'edit']);
            $r->addRoute('POST', '/{id:\d+}/update', ['App\Controllers\Admin\SupplierController', 'update']);
            $r->addRoute('GET', '/{id:\d+}/delete', ['App\Controllers\Admin\SupplierController', 'delete']);
        });

        // PURCHASE ORDERS
        $r->addGroup('/purchase_orders', function (RouteCollector $r) {
            $r->addRoute('GET', '', ['App\Controllers\Admin\PurchaseOrders', 'index']);
            $r->addRoute('GET', '/new', ['App\Controllers\Admin\PurchaseOrders', 'showCreatePOPage']);

        });

        // HOMES
        $r->addRoute('GET', '/home', ['App\Controllers\Admin\HomeController', 'index']);
        $r->addRoute('GET', '/blank', ['App\Controllers\Admin\HomeController', 'showBlankPage']);

        // CUSTOMERS
        $r->addGroup('/customers', function (RouteCollector $r) {
            $r->addRoute('GET', '', ['App\Controllers\Admin\CustomerController', 'index']);
            $r->addRoute('GET', '/create', ['App\Controllers\Admin\CustomerController', 'openCreate']);
            $r->addRoute('POST', '/create', ['App\Controllers\Admin\CustomerController', 'create']);
            $r->addRoute('GET', '/view/{customerId}', ['App\Controllers\Admin\CustomerController', 'getById']);
        });

        // USERS
        $r->addGroup('/user', function (RouteCollector $r) {
            $r->addRoute('GET', '', ['App\Controllers\Admin\UserController', 'index']);
            $r->addRoute('GET', '/add', ['App\Controllers\Admin\UserController', 'showAddUserPage']);
            $r->addRoute('POST', '/add', ['App\Controllers\Admin\UserController', 'addUser']);
            $r->addRoute('GET', '/edit', ['App\Controllers\Admin\UserController', 'showEditUserPage']);
            $r->addRoute('POST', '/edit', ['App\Controllers\Admin\UserController', 'editUser']);
            $r->addRoute('POST', '/delete', ['App\Controllers\Admin\UserController', 'deleteUser']);
            $r->addRoute('GET', '/view/{userId}', ['App\Controllers\Admin\UserController', 'showViewUserPage']);
        });

        // PERMISSIONS
        $r->addGroup('/permissions', function (RouteCollector $r) {
            $r->addRoute('GET', '', ['App\Controllers\Admin\UserController', 'showPermissionPage']);
            $r->addRoute('GET', '/add', ['App\Controllers\Admin\UserController', 'showAddPermissionPage']);
            $r->addRoute('POST', '/add', ['App\Controllers\Admin\UserController', 'addPermission']);
            $r->addRoute('GET', '/edit', ['App\Controllers\Admin\UserController', 'showEditPermissionPage']);
            $r->addRoute('POST', '/edit', ['App\Controllers\Admin\UserController', 'editPermission']);
            $r->addRoute('POST', '/delete', ['App\Controllers\Admin\UserController', 'deletePermission']);
        });

        $r->addGroup('/api', function (RouteCollector $r) {
            $r->addRoute('GET', '/get_all_suppliers', ['App\Controllers\Admin\SupplierController', 'api_getAllSuppliers']);
            $r->addRoute('GET', '/get_supplier_by_id/{id}', ['App\Controllers\Admin\SupplierController', 'api_getSupplierById']);

        });
    });


    // CLIENT
    $r->addGroup('', function (RouteCollector $r) {
        $r->addRoute('GET', '/', ['App\Controllers\Client\HomeController', 'index']);
        $r->addRoute('GET', '/about-us', ['App\Controllers\Client\AboutUsController', 'index']);
        $r->addRoute('GET', '/contact', ['App\Controllers\Client\ContactController', 'index']);
        $r->addRoute('GET', '/products', ['App\Controllers\Client\ProductController', 'index']);
        $r->addRoute('GET', '/products/{id:\d+}', ['App\Controllers\Client\ProductController', 'index']);
        $r->addRoute('GET', '/search', ['App\Controllers\Client\SearchController', 'index']);

        $r->addGroup('/account', function (RouteCollector $r) {
            $r->addRoute('GET', '/login', ['App\Controllers\Client\AuthController', 'showLoginPage']);
            $r->addRoute('GET', '/logout', ['App\Controllers\Client\AuthController', 'logout']);
            $r->addRoute('GET', '/resetPassword', ['App\Controllers\Client\AuthController', 'resetPassword']);
            $r->addRoute('POST', '/resetPassword', ['App\Controllers\Client\AuthController', 'resetPassword']);

        });

        $r->addGroup('/api', function (RouteCollector $r) {
            $r->addRoute('POST', '/account/login', ['App\Controllers\Client\AuthController', 'login']);
            $r->addRoute('POST', '/account/register', ['App\Controllers\Client\AuthController','register']);
            $r->addRoute('POST', '/account/forgot', ['App\Controllers\Client\AuthController', 'forgotPassword']);
        });
    });

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