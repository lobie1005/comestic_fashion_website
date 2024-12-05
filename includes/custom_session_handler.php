<?php
class CustomSessionHandler {
    private const SESSION_LIFETIME = 3600; // 1 hour
    private const REGENERATE_TIME = 300;   // 5 minutes

    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
            ini_set('session.cookie_samesite', 'Lax');
            ini_set('session.gc_maxlifetime', self::SESSION_LIFETIME);
            
            session_start();
        }

        if (!isset($_SESSION['last_regeneration'])) {
            self::regenerateSession();
        } else if (time() - $_SESSION['last_regeneration'] > self::REGENERATE_TIME) {
            self::regenerateSession();
        }
    }

    public static function regenerateSession() {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }

    public static function destroy() {
        $_SESSION = array();

        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        session_destroy();
    }

    public static function isAuthenticated() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public static function requireAuth() {
        if (!self::isAuthenticated()) {
            header('Location: ' . BASE_URL . '/login.php');
            exit();
        }
    }

    public static function setCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validateCSRFToken($token) {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            throw new Exception('CSRF token validation failed');
        }
        return true;
    }
}
