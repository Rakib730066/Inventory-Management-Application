<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Config\AppConfig;
use App\Core\Session;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

// Show errors in development
if (AppConfig::APP_DEBUG) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// Start the session
Session::start();

// Define all routes
$dispatcher = simpleDispatcher(function (RouteCollector $r): void {
    // Home
    $r->addRoute('GET', '/', ['App\Controllers\ProductController', 'index']);

    // Authentication
    $r->addRoute('GET', '/login', ['App\Controllers\AuthController', 'showLogin']);
    $r->addRoute('POST', '/login', ['App\Controllers\AuthController', 'login']);
    $r->addRoute('GET', '/register', ['App\Controllers\AuthController', 'showRegister']);
    $r->addRoute('POST', '/register', ['App\Controllers\AuthController', 'register']);
    $r->addRoute('POST', '/logout', ['App\Controllers\AuthController', 'logout']);
    $r->addRoute('GET', '/forgot-password', ['App\Controllers\AuthController', 'showForgotPassword']);
    $r->addRoute('POST', '/forgot-password', ['App\Controllers\AuthController', 'sendResetLink']);
    $r->addRoute('GET', '/reset-password', ['App\Controllers\AuthController', 'showResetPassword']);
    $r->addRoute('POST', '/reset-password', ['App\Controllers\AuthController', 'resetPassword']);

    // Products
    $r->addRoute('GET', '/products', ['App\Controllers\ProductController', 'index']);
    $r->addRoute('GET', '/products/create', ['App\Controllers\ProductController', 'create']);
    $r->addRoute('POST', '/products/create', ['App\Controllers\ProductController', 'store']);
    $r->addRoute('GET', '/products/{id:\d+}/edit', ['App\Controllers\ProductController', 'edit']);
    $r->addRoute('POST', '/products/{id:\d+}/edit', ['App\Controllers\ProductController', 'update']);
    $r->addRoute('POST', '/products/{id:\d+}/delete', ['App\Controllers\ProductController', 'delete']);
    $r->addRoute('GET', '/api/products/low-stock', ['App\Controllers\ApiController', 'lowStock']);
    // Categories
    $r->addRoute('GET', '/categories', ['App\Controllers\CategoryController', 'index']);
    $r->addRoute('GET', '/categories/create', ['App\Controllers\CategoryController', 'create']);
    $r->addRoute('POST', '/categories/create', ['App\Controllers\CategoryController', 'store']);
    $r->addRoute('GET', '/categories/{id:\d+}/edit', ['App\Controllers\CategoryController', 'edit']);
    $r->addRoute('POST', '/categories/{id:\d+}/edit', ['App\Controllers\CategoryController', 'update']);
    $r->addRoute('POST', '/categories/{id:\d+}/delete', ['App\Controllers\CategoryController', 'delete']);

    // Admin users
    $r->addRoute('GET', '/admin/users', ['App\Controllers\UserController', 'index']);
    $r->addRoute('GET', '/admin/users/{id:\d+}/edit', ['App\Controllers\UserController', 'edit']);
    $r->addRoute('POST', '/admin/users/{id:\d+}/edit', ['App\Controllers\UserController', 'update']);
    $r->addRoute('POST', '/admin/users/{id:\d+}/delete', ['App\Controllers\UserController', 'delete']);

    // Dashboard
    $r->addRoute('GET', '/dashboard', ['App\Controllers\DashboardController', 'index']);

    // Legal Pages
    $r->addRoute('GET', '/privacy', ['App\Controllers\LegalController', 'privacy']);
    $r->addRoute('GET', '/terms', ['App\Controllers\LegalController', 'terms']);


    // AJAX / API
    $r->addRoute('GET', '/api/products', ['App\Controllers\ApiController', 'products']);
    $r->addRoute('POST', '/api/products/{id:\d+}/quantity', ['App\Controllers\ApiController', 'updateQuantity']);
});

// Get method and URI
$httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

// Remove query string from URI
if (false !== $position = strpos($uri, '?')) {
    $uri = substr($uri, 0, $position);
}

// Remove trailing slash except for homepage
if ($uri !== '/' && str_ends_with($uri, '/')) {
    $uri = rtrim($uri, '/');
}


$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo '<h1 style="font-family:sans-serif;padding:2rem">404 - Page not found</h1>';
        break;

    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo '<h1 style="font-family:sans-serif;padding:2rem">405 - Method not allowed</h1>';
        break;

    case Dispatcher::FOUND:
        $controllerClass = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        $vars = $routeInfo[2] ?? [];

        if (!class_exists($controllerClass) || !method_exists($controllerClass, $method)) {
            http_response_code(500);
            echo '<h1 style="font-family:sans-serif;padding:2rem">500 - Controller or method not found</h1>';
            break;
        }

        $controller = new $controllerClass();
        $controller->$method($vars);
        break;
}