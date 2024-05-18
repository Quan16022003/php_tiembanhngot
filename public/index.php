<?php

require_once '../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

define('INC_ROOT', __DIR__);

session_start();

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
        $r->addRoute('GET', '/dashboard', ['App\Controllers\Admin\DashboardController', 'listProductSales']);

        // PRODUCTS
        $r->addGroup('/products', function (RouteCollector $r) {
            $r->addRoute('GET', '', ['App\Controllers\Admin\ProductsController', 'read']);
            $r->addRoute('GET', '/create', ['App\Controllers\Admin\ProductsController', 'openCreate']);
            $r->addRoute('POST', '/create', ['App\Controllers\Admin\ProductsController', 'create']);
            $r->addRoute('GET', '/add', ['App\Controllers\Admin\ProductsController', 'openAdd']);
            $r->addRoute('POST', '/add', ['App\Controllers\Admin\ProductsController', 'uploadCSV']);
            $r->addRoute('GET', '/exportToCSV', ['App\Controllers\Admin\ProductsController', 'exportToCSV']);
            $r->addRoute('POST', '/delete/{productID}', ['App\Controllers\Admin\ProductsController', 'delete']);
            $r->addRoute('GET', '/edit/{id}', ['App\Controllers\Admin\ProductsController', 'getById']);
            $r->addRoute('POST', '/edit/{id}', ['App\Controllers\Admin\ProductsController', 'update']);
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
            $r->addRoute('GET', '', ['App\Controllers\Admin\PurchaseOrdersController', 'index']);
            $r->addRoute('GET', '/new', ['App\Controllers\Admin\PurchaseOrdersController', 'create']);
            $r->addRoute('POST', '/store', ['App\Controllers\Admin\PurchaseOrdersController', 'store']);
            $r->addRoute('GET', '/{id:\d+}', ['App\Controllers\Admin\PurchaseOrdersController', 'show']);
            $r->addRoute('GET', '/{id:\d+}/edit', ['App\Controllers\Admin\PurchaseOrdersController', 'edit']);
            $r->addRoute('POST', '/{id:\d+}/update', ['App\Controllers\Admin\PurchaseOrdersController', 'update']);
            $r->addRoute('GET', '/{id:\d+}/update_status', ['App\Controllers\Admin\PurchaseOrdersController', 'updateStatus']);
        });

        // ORDERS
        $r->addGroup(('/orders'), function (RouteCollector $r) {
            $r->addRoute('GET', '', ['App\Controllers\Admin\OrdersController', 'index']);
            $r->addRoute('GET', '/{id:\d+}', ['App\Controllers\Admin\OrdersController', 'show']);
            $r->addRoute('POST', '/{id:\d+}/update_status', ['App\Controllers\Admin\OrdersController', 'updateStatus']);
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
            $r->addRoute('GET', '/get_all_products', ['App\Controllers\Admin\ProductsController', 'api_getAllProducts']);
        });
    });


    // CLIENT
    $r->addGroup('', function (RouteCollector $r) {
        $r->addRoute('GET', '/', ['App\Controllers\Client\HomeController', 'index']);
        $r->addRoute('GET', '/about-us', ['App\Controllers\Client\AboutUsController', 'index']);
        $r->addRoute('GET', '/contact', ['App\Controllers\Client\ContactController', 'index']);
        $r->addRoute('GET', '/products', ['App\Controllers\Client\ProductController', 'index']);
        // $r->addRoute('GET', '/products/{id:\d+}', ['App\Controllers\Client\ProductController', 'index']);
        $r->addRoute('GET', '/search', ['App\Controllers\Client\SearchController', 'index']);
        $r->addRoute('GET', '/products/{productsId}', ['App\Controllers\Client\ProductController', 'productDetail']);

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
        // cart
        $r->addRoute('GET', '/cart/addtocart', ['App\Controllers\Client\CartController', 'addToCart']);
        $r->addRoute('GET', '/cart', ['App\Controllers\Client\CartController', 'index']);
        $r->addRoute('POST', '/cart/add', ['App\Controllers\Client\CartController', 'addToCart']);

        // checkout
        $r->addRoute('GET', '/checkout/{cartId}', ['App\Controllers\Client\CheckOutController', 'showCheckOutPage']);
        $r->addRoute('POST', '/checkout/submit', ['App\Controllers\Admin\OrdersController', 'create']);

        // ORDERS
        $r->addGroup(('/orders'), function (RouteCollector $r) {
            $r->addRoute('GET', '/{id}', ['App\Controllers\Client\OrderController', 'index']);
            $r->addRoute('GET', '/details/{id}', ['App\Controllers\Client\OrderController', 'show']);
            $r->addRoute('POST', '/{id}/update_status', ['App\Controllers\Client\OrderController', 'updateStatus']);
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