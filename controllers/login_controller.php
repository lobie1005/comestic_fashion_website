<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

// Initialize database connection
$database = new Database();
$db = $database->connect();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(filter_var($_POST['username'] ?? '', FILTER_SANITIZE_STRING));
    $password = $_POST['password'] ?? '';
    $errors = [];

    // Validate input
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if (empty($errors)) {
        // Find user by username
        $userData = $user->findByUsername($username);

        if ($userData && $user->verifyPassword($password, $userData['password_hash'])) {
            // Set session variables
            $_SESSION['user_id'] = $userData['user_id'];
            $_SESSION['username'] = $userData['username'];
            $_SESSION['role'] = $userData['role_name'];

            // Redirect based on role
            if ($userData['role_name'] === 'admin') {
                header('Location: ' . BASE_URL . '/admin/dashboard');
            } else {
                header('Location: ' . BASE_URL);
            }
            exit();
        } else {
            $errors[] = "Invalid username or password";
        }
    }

    if (!empty($errors)) {
        $_SESSION['login_errors'] = $errors;
        header('Location: ' . BASE_URL . '/login');
        exit();
    }
} else {
    // Display login form
    include_once __DIR__ . '/../views/login.php';
}
?>