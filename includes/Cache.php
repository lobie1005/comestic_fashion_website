<?php
class Cache {
    private static $instance = null;
    private $cache = [];
    private $cacheDir;
    private $defaultTTL = 3600; // 1 hour

    private function __construct() {
        $this->cacheDir = __DIR__ . '/../cache/';
        if (!file_exists($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get($key) {
        // Check memory cache first
        if (isset($this->cache[$key])) {
            $data = $this->cache[$key];
            if ($data['expires'] > time()) {
                return $data['value'];
            }
            unset($this->cache[$key]);
        }

        // Check file cache
        $filename = $this->getCacheFilename($key);
        if (file_exists($filename)) {
            $data = unserialize(file_get_contents($filename));
            if ($data['expires'] > time()) {
                $this->cache[$key] = $data;
                return $data['value'];
            }
            unlink($filename);
        }

        return null;
    }

    public function set($key, $value, $ttl = null) {
        $ttl = $ttl ?? $this->defaultTTL;
        $data = [
            'value' => $value,
            'expires' => time() + $ttl
        ];

        // Save to memory cache
        $this->cache[$key] = $data;

        // Save to file cache
        $filename = $this->getCacheFilename($key);
        file_put_contents($filename, serialize($data), LOCK_EX);
    }

    public function delete($key) {
        unset($this->cache[$key]);
        $filename = $this->getCacheFilename($key);
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    public function clear() {
        $this->cache = [];
        array_map('unlink', glob($this->cacheDir . '*'));
    }

    private function getCacheFilename($key) {
        return $this->cacheDir . md5($key) . '.cache';
    }

    public function setDefaultTTL($ttl) {
        $this->defaultTTL = $ttl;
    }

    public function has($key) {
        return $this->get($key) !== null;
    }

    public function remember($key, $callback, $ttl = null) {
        $value = $this->get($key);
        if ($value === null) {
            $value = $callback();
            $this->set($key, $value, $ttl);
        }
        return $value;
    }

    public function increment($key, $value = 1) {
        $current = $this->get($key);
        if (is_numeric($current)) {
            $new = $current + $value;
            $this->set($key, $new);
            return $new;
        }
        return false;
    }

    public function decrement($key, $value = 1) {
        return $this->increment($key, -$value);
    }
}
