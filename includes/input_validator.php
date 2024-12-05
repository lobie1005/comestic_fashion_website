<?php
class InputValidator {
    public static function sanitizeString($input) {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    public static function sanitizeEmail($email) {
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : false;
    }

    public static function sanitizeInt($input) {
        return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function sanitizeFloat($input) {
        return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    public static function validatePassword($password) {
        // At least 8 characters long
        // Contains at least one uppercase letter
        // Contains at least one lowercase letter
        // Contains at least one number
        // Contains at least one special character
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        return preg_match($pattern, $password);
    }

    public static function validateUsername($username) {
        // Alphanumeric characters and underscores only
        // 3-20 characters long
        return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
    }

    public static function sanitizeArray($array) {
        $sanitized = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = self::sanitizeArray($value);
            } else {
                $sanitized[$key] = self::sanitizeString($value);
            }
        }
        return $sanitized;
    }

    public static function validateFile($file, $allowedTypes = [], $maxSize = 5242880) {
        if (!isset($file['error']) || is_array($file['error'])) {
            return false;
        }

        if ($file['size'] > $maxSize) {
            return false;
        }

        if (!empty($allowedTypes) && !in_array($file['type'], $allowedTypes)) {
            return false;
        }

        return true;
    }

    public static function validateDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
