<?php
session_start();

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/auth_controller.php';

// Initialize database connection
$database = new Database();
$db = $database->connect();

if (!$db) {
    die("Error: Could not connect to the database.");
}

// Initialize auth controller
$authController = new AuthController($db);

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController->register();
} else {
    $authController->showRegisterForm();
}
