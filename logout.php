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

// Initialize auth controller and logout
$authController = new AuthController($db);
$authController->logout();
