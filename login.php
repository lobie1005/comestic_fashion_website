<?php
// Include configuration first
require_once __DIR__ . '/config/constants.php';

// Then start session
session_start();

// Include other dependencies
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/auth_controller.php';

try {
    // Initialize database connection
    $database = new Database();
    $db = $database->connect();

    // Initialize auth controller
    $auth = new AuthController($db);

    // Handle login form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $auth->login();
    } else {
        // Show login form
        $auth->showLoginForm();
    }
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    die("An error occurred. Please try again later.");
}