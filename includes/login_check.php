<?php
// Function to check if user is logged in
function checkLoginStatus($requireLogin = true) {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Get current page and action
    $currentPage = $_SERVER['REQUEST_URI'];
    $isIndexPage = $currentPage === '/' || $currentPage === '/index.php' || $currentPage === '/Web_ASM/' || $currentPage === '/Web_ASM/index.php';
    
    // Always allow access to index page and basic views
    if ($isIndexPage) {
        return true;
    }

    // Check if login is required and user is not logged in
    if ($requireLogin && !isset($_SESSION['user_id'])) {
        echo "<script>
            if (confirm('To access detailed information and features, you need to login. Would you like to login now?')) {
                window.location.href = '" . BASE_URL . "/login';
            } else {
                window.history.back();
            }
        </script>";
        exit();
    }

    return true;
}

// Function to check if action requires login
function requiresLogin($action = '') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([
            'status' => 'login_required',
            'message' => 'Please login to access this feature'
        ]);
        exit();
    }
    return true;
}
?>
