<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function validate_session() {
    // Check if essential session variables exist
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
        return false;
    }

    // Check session timeout (30 minutes)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_destroy();
        return false;
    }

    // Validate IP address and user agent to prevent session hijacking
    if (isset($_SESSION['ip_address']) && isset($_SESSION['user_agent'])) {
        if ($_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR'] || 
            $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
            session_destroy();
            return false;
        }
    }

    // Update last activity time
    $_SESSION['last_activity'] = time();
    return true;
}

// Check if user has admin privileges
function validate_admin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

// Function to require login
function require_login() {
    if (!validate_session()) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

// Function to require admin access
function require_admin() {
    require_login();
    if (!validate_admin()) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}
