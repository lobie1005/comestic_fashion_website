<?php
require_once __DIR__ . '/config/config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Method not allowed');
}

// Get form data
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

// Validate form data
if (!$name || !$email || !$subject || !$message) {
    $response = [
        'success' => false,
        'message' => 'Please fill in all fields'
    ];
    echo json_encode($response);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response = [
        'success' => false,
        'message' => 'Please enter a valid email address'
    ];
    echo json_encode($response);
    exit;
}

try {
    // Here you would typically:
    // 1. Save the message to database
    // 2. Send email notification
    // For now, we'll just simulate success
    
    $response = [
        'success' => true,
        'message' => 'Message sent successfully'
    ];
    echo json_encode($response);
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'An error occurred while sending your message. Please try again later.'
    ];
    echo json_encode($response);
}
