<?php
require_once __DIR__ . '/../includes/Logger.php';
require_once __DIR__ .'/../includes/Cache.php';

abstract class Controller {
    protected $db;
    protected $logger;
    protected $cache;
    protected $view;
    protected $model;

    public function __construct() {
        $this->initializeServices();
        $this->initializeModel();
    }

    protected function initializeServices() {
        // Initialize Database
        try {
            $this->db = (new Database())->connect();
        } catch (Exception $e) {
            $this->logError('Database connection failed', $e);
            $this->renderError(500, 'Database connection failed');
        }

        // Initialize Logger
        Logger::init();
        $this->logger = Logger::class;

        // Initialize Cache
        $this->cache = Cache::getInstance();
    }

    protected function initializeModel() {
        // Get the controller class name without 'Controller' suffix
        $modelName = str_replace('Controller', '', get_class($this));
        $modelClass = $modelName . 'Model';
        $modelFile = BASEPATH . '/models/' . $modelClass . '.php';

        if (file_exists($modelFile)) {
            require_once $modelFile;
            $this->model = new $modelClass($this->db);
        }
    }

    protected function render($view, $data = []) {
        try {
            $viewFile = BASEPATH . '/views/' . $view . '.php';
            if (!file_exists($viewFile)) {
                throw new Exception("View file not found: $view");
            }

            // Extract data for the view
            extract($data);

            // Start output buffering
            ob_start();
            include $viewFile;
            $content = ob_get_clean();

            // Include the layout if it exists
            $layoutFile = BASEPATH . '/views/layouts/main.php';
            if (file_exists($layoutFile)) {
                include $layoutFile;
            } else {
                echo $content;
            }
        } catch (Exception $e) {
            $this->logError('View rendering failed', $e);
            $this->renderError(500, 'View rendering failed');
        }
    }

    protected function renderJSON($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function renderError($code, $message) {
        http_response_code($code);
        $this->render('errors/error', [
            'code' => $code,
            'message' => $message
        ]);
        exit;
    }

    protected function logError($message, Exception $e) {
        $context = [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ];
        $this->logger::error($message . ': ' . $e->getMessage(), $context);
    }

    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function getParam($key, $default = null) {
        return $_GET[$key] ?? $default;
    }

    protected function postParam($key, $default = null) {
        return $_POST[$key] ?? $default;
    }

    protected function validateCSRF() {
        $token = $this->postParam('csrf_token');
        if (!$token || $token !== $_SESSION['csrf_token']) {
            $this->renderError(403, 'Invalid CSRF token');
        }
    }

    protected function setCacheData($key, $data, $ttl = null) {
        $this->cache->set($key, $data, $ttl);
    }

    protected function getCacheData($key) {
        return $this->cache->get($key);
    }

    protected function clearCache($key = null) {
        if ($key) {
            $this->cache->delete($key);
        } else {
            $this->cache->clear();
        }
    }
}
