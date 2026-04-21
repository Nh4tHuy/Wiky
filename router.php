<?php
/**
 * Router for PHP Built-in Server
 * Usage: php -S localhost:8000 router.php
 */

// Get the request URI
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Check if requesting a static file that exists (not a directory)
if ($uri !== '/' && file_exists(__DIR__ . $uri) && !is_dir(__DIR__ . $uri)) {
    $ext = pathinfo($uri, PATHINFO_EXTENSION);
    
    // Don't serve PHP files directly (security)
    if ($ext === 'php') {
        return false; // Let PHP built-in server handle it
    }
    
    // Let the server handle static files (CSS, JS, images, etc.)
    return false;
}

// All other requests go to index.php
require __DIR__ . '/index.php';
