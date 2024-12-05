<?php
class Logger {
    private const LOG_LEVELS = [
        'DEBUG' => 0,
        'INFO' => 1,
        'WARNING' => 2,
        'ERROR' => 3,
        'CRITICAL' => 4
    ];

    private const LOG_COLORS = [
        'DEBUG' => '0;36', // Cyan
        'INFO' => '0;32',  // Green
        'WARNING' => '1;33', // Yellow
        'ERROR' => '0;31',   // Red
        'CRITICAL' => '1;31' // Bright Red
    ];

    private static $logFile;
    private static $logLevel;

    public static function init($logFile = null, $minLogLevel = 'DEBUG') {
        if ($logFile === null) {
            self::$logFile = __DIR__ . '/../logs/app.log';
        } else {
            self::$logFile = $logFile;
        }

        // Create logs directory if it doesn't exist
        $logDir = dirname(self::$logFile);
        if (!file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }

        self::$logLevel = self::LOG_LEVELS[$minLogLevel] ?? self::LOG_LEVELS['DEBUG'];
    }

    public static function log($level, $message, array $context = []) {
        if (!isset(self::LOG_LEVELS[$level]) || self::LOG_LEVELS[$level] < self::$logLevel) {
            return;
        }

        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = self::formatMessage($timestamp, $level, $message, $context);
        
        file_put_contents(
            self::$logFile,
            $formattedMessage . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }

    private static function formatMessage($timestamp, $level, $message, array $context) {
        $color = self::LOG_COLORS[$level] ?? '0';
        $contextStr = empty($context) ? '' : ' ' . json_encode($context);
        
        return sprintf(
            "\033[%sm[%s] %s: %s%s\033[0m",
            $color,
            $timestamp,
            str_pad($level, 8),
            $message,
            $contextStr
        );
    }

    public static function debug($message, array $context = []) {
        self::log('DEBUG', $message, $context);
    }

    public static function info($message, array $context = []) {
        self::log('INFO', $message, $context);
    }

    public static function warning($message, array $context = []) {
        self::log('WARNING', $message, $context);
    }

    public static function error($message, array $context = []) {
        self::log('ERROR', $message, $context);
    }

    public static function critical($message, array $context = []) {
        self::log('CRITICAL', $message, $context);
    }

    public static function getLogFile() {
        return self::$logFile;
    }

    public static function clearLog() {
        if (file_exists(self::$logFile)) {
            unlink(self::$logFile);
        }
    }
}
