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
    $email = trim(filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL));
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $errors = [];

    // Validate input
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain at least one lowercase letter";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one number";
    } elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = "Password must contain at least one special character";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    // Check if username or email already exists
    if (empty($errors)) {
        if ($user->findByUsername($username)) {
            $errors[] = "Username already exists";
        }
        if ($user->findByEmail($email)) {
            $errors[] = "Email already exists";
        }
    }

    // Create user if no errors
    if (empty($errors)) {
        if ($user->create($username, $email, $password)) {
            $_SESSION['success'] = "Registration successful! Please login.";
            header('Location: ' . BASE_URL . '/login');
            exit();
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }

    if (!empty($errors)) {
        $_SESSION['register_errors'] = $errors;
        header('Location: ' . BASE_URL . '/register');
        exit();
    }
} else {
    // Display registration form
    include_once __DIR__ . '/../views/register.php';
}
?>