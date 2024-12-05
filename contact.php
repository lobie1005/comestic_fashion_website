<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define the base path if not already defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__);
}

// Include configuration first
require_once __DIR__ . '/config/constants.php';

// Start session
session_start();

// Include database connection
require_once __DIR__ . '/config/database.php';

// Include the view file
require_once __DIR__ . '/views/contact.php';
