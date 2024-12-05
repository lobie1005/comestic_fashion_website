<?php
// Session Configuration - Only set if session hasn't started
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
}

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Time Zone
date_default_timezone_set('UTC');

// Base URL - Adjust this based on your local setup
define('BASE_URL', '/Web_ASM');  // Simplified to relative path

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'cosmetics_fashion_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Directory Paths
define('BASEPATH', dirname(__DIR__));
define('ASSETS_PATH', BASE_URL . '/assets');
define('CSS_PATH', ASSETS_PATH . '/css');
define('JS_PATH', ASSETS_PATH . '/js');
define('IMAGES_PATH', ASSETS_PATH . '/images');
define('UPLOADS_PATH', BASE_URL . '/uploads');

// Session Keys
define('SESSION_USER_ID', 'user_id');
define('SESSION_USER_NAME', 'username');
define('SESSION_IS_ADMIN', 'is_admin');

// User Roles
define('ROLE_USER', 1);
define('ROLE_ADMIN', 2);

// Database Tables
define('TABLE_USERS', 'users');
define('TABLE_PRODUCTS', 'products');
define('TABLE_CATEGORIES', 'categories');
define('TABLE_ORDERS', 'orders');