<?php
/**
 * Main Entry Point - Router
 * Website: newbiek.id.vn
 */

require_once __DIR__ . '/includes/runtime.php';

// Load config first to get timezone and session policy
$config = require __DIR__ . '/config/config.php';
$GLOBALS['config'] = $config;

configureSessionRuntime($_SERVER);

// Start session
session_start();

// Set timezone from config
date_default_timezone_set($config['timezone'] ?? 'Asia/Ho_Chi_Minh');

// Load core includes
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/turnstile.php';
require_once __DIR__ . '/includes/auth.php';

applySecurityHeadersRuntime($_SERVER);

// Auto-cancel expired orders (throttle to run once every 5 minutes)
if (!isset($_SESSION['last_cleanup']) || $_SESSION['last_cleanup'] < time() - 300) {
    cancelExpiredOrders();
    $_SESSION['last_cleanup'] = time();
}

// Get route from URL - handle both Apache mod_rewrite and PHP built-in server
$route = $_GET['route'] ?? '';
$route = trim($route, '/');

// If no route set via GET, parse from REQUEST_URI (for PHP built-in server)
if (empty($route)) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $route = trim(urldecode($uri), '/');
}

// Default to home
$route = $route ?: 'home';

// Define routes
$routes = [
    // Public pages
    'home' => 'pages/home.php',
    '' => 'pages/home.php',
    'about' => 'pages/about.php',
    'products' => 'pages/products.php',
    'product' => 'pages/product-detail.php',
    'blog' => 'pages/blog.php',
    'post' => 'pages/blog-detail.php',
    'contact' => 'pages/contact.php',
    'policy' => 'pages/policy.php',
    'cart' => 'pages/cart.php',
    'checkout' => 'pages/checkout.php',
    'payment-success' => 'pages/payment-success.php',
    'payment-failed' => 'pages/payment-failed.php',
    
    // Auth
    'login' => 'auth/login.php',
    'register' => 'auth/register.php',
    'forgot-password' => 'auth/forgot-password.php',
    'reset-password' => 'auth/reset-password.php',
    'logout' => 'auth/logout.php',
    
    // Account (require login)
    'account' => 'account/dashboard.php',
    'account/orders' => 'account/orders.php',
    'account/downloads' => 'account/downloads.php',
    'account/manual-delivery' => 'account/manual-delivery.php',
    'account/support' => 'account/support.php',
    
    // Admin (require admin)
    'admin' => 'admin/dashboard.php',
    'admin/products' => 'admin/products.php',
    'admin/products/add' => 'admin/product-edit.php',
    'admin/products/edit' => 'admin/product-edit.php',
    'admin/orders' => 'admin/orders.php',
    'admin/deliveries' => 'admin/deliveries.php',
    'admin/posts' => 'admin/posts.php',
    'admin/posts/add' => 'admin/post-edit.php',
    'admin/posts/edit' => 'admin/post-edit.php',
    'admin/messages' => 'admin/messages.php',
    'admin/coupons' => 'admin/coupons.php',
    'admin/about-edit' => 'admin/about-edit.php',
    'admin/site-settings' => 'admin/site-settings.php',
    'admin/popup-settings' => 'admin/popup-settings.php',
    
    // API
    'api/cart' => 'api/cart.php',
    'api/checkout' => 'api/checkout.php',
    'api/sepay-webhook' => 'api/sepay-webhook.php',
    'api/contact' => 'api/contact.php',
    'api/support' => 'api/support.php',
    'api/generate-cv' => 'api/generate-cv.php',
    'api/upload' => 'api/upload.php',
    'api/download' => 'api/download.php',
    'sitemap.xml' => 'api/sitemap.php',
];

// Check for dynamic routes (e.g., product/slug, post/slug)
$routeParts = explode('/', $route);
$baseRoute = $routeParts[0];

// Handle dynamic routes
if ($baseRoute === 'product' && isset($routeParts[1])) {
    $_GET['slug'] = $routeParts[1];
    $route = 'product';
} elseif ($baseRoute === 'post' && isset($routeParts[1])) {
    $_GET['slug'] = $routeParts[1];
    $route = 'post';
}

// Check if route exists
if (isset($routes[$route])) {
    $file = __DIR__ . '/' . $routes[$route];
    
    // Check access permissions
    if (strpos($route, 'account') === 0 && !isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $route;
        header('Location: /login');
        exit;
    }
    
    if (strpos($route, 'admin') === 0 && !isAdmin()) {
        header('Location: /login');
        exit;
    }
    
    // Load the page
    if (file_exists($file)) {
        require_once $file;
    } else {
        // File not found, show 404
        http_response_code(404);
        require_once __DIR__ . '/pages/404.php';
    }
} else {
    // Route not found, show 404
    http_response_code(404);
    require_once __DIR__ . '/pages/404.php';
}
