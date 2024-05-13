<?php

require_once '../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

// Define routes
$dispatcher = simpleDispatcher(function (RouteCollector $r) {

    $r->addGroup('/admin', function (RouteCollector $r) {
        $r->addRoute('GET', '/', ['App\Controllers\Admin\HomeController', 'index']);

        // ACCOUNT
        $r->addRoute('GET', '/login', ['App\Controllers\Admin\AuthController', 'index']);
        $r->addRoute('POST', '/login', ['App\Controllers\Admin\AuthController', 'login']);
        $r->addRoute('GET', '/logout', ['App\Controllers\Admin\AuthController', 'logout']);
        $r->addRoute('GET', '/account/profile', ['App\Controllers\Admin\AuthController', 'showProfilePage']);

        // DASHBOARD
        $r->addRoute('GET', '/dashboard', ['App\Controllers\Admin\DashboardController', 'TopProductsOfAWeek']);

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

        // CATEGORIES
        $r->addGroup('/categories', function (RouteCollector $r) {
            $r->addRoute('GET', '', ['App\Controllers\Admin\CategoriesController', 'index']);
            $r->addRoute('GET', '/create', ['App\Controllers\Admin\CategoriesController', 'openCreate']);
            $r->addRoute('POST', '/create', ['App\Controllers\Admin\CategoriesController', 'create']);
            $r->addRoute('GET', '/add', ['App\Controllers\Admin\CategoriesController', 'openAdd']);
            $r->addRoute('POST', '/add', ['App\Controllers\Admin\CategoriesController', 'add']);
            $r->addRoute('POST', '/delete/{categoryID}', ['App\Controllers\Admin\CategoriesController', 'delete']);
            $r->addRoute('GET', '/edit/{categoryID}', ['App\Controllers\Admin\CategoriesController', 'getById']);
            $r->addRoute('POST', '/edit/{categoryID}', ['App\Controllers\Admin\CategoriesController', 'update']);
            $r->addRoute('POST', '/search', ['App\Controllers\Admin\CategoriesController', 'search']);
        });

        // INVOICES
        $r->addGroup('/invoices', function (RouteCollector $r) {
            $r->addRoute('GET', '', ['App\Controllers\Admin\InvoicesController', 'indexPage']);
            $r->addRoute('GET', '/view/{invoiceId}', ['App\Controllers\Admin\InvoicesController', 'getById']);
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
    });

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
            $r->addRoute('POST', '/account/register', ['App\Controllers\Client\AuthController', 'register']);
            $r->addRoute('POST', '/account/forgot', ['App\Controllers\Client\AuthController', 'forgotPassword']);
        });

        $r->addGroup('/cart', function (RouteCollector $r) {
            $r->addRoute('GET', '', ['App\Controllers\Client\CartController', 'indexPage']);
            $r->addRoute('POST', '/add', ['App\Controllers\Client\CartController', 'add']);
            $r->addRoute('POST', '/remove', ['App\Controllers\Client\CartController', 'remove']);
            $r->addRoute('POST', '/update', ['App\Controllers\Client\CartController', 'update']);
            $r->addRoute('POST', '/clear', ['App\Controllers\Client\CartController', 'clear']);
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