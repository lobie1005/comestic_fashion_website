<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/ProductImage.php';

class ProductController extends Controller {
    private $productImage;

    public function __construct() {
        parent::__construct();
        $this->productImage = new ProductImage($this->db);
    }

    public function index() {
        try {
            // Get products from cache or database
            $products = $this->cache->remember('all_products', function() {
                return $this->model->getAllProducts();
            }, 3600); // Cache for 1 hour

            $this->render('products/index', ['products' => $products]);
        } catch (Exception $e) {
            $this->logError('Error fetching products', $e);
            $this->renderError(500, 'Error loading products');
        }
    }

    public function getFeaturedProducts($limit = 8) {
        try {
            return $this->cache->remember('featured_products_' . $limit, function() use ($limit) {
                return $this->model->getFeaturedProducts($limit);
            }, 1800); // Cache for 30 minutes
        } catch (Exception $e) {
            $this->logError('Error fetching featured products', $e);
            return [];
        }
    }

    public function handlePost() {
        try {
            if (!$this->isPost()) {
                $this->renderError(405, 'Method not allowed');
            }

            if (!CustomSessionHandler::isAuthenticated() || $_SESSION['role'] != ROLE_ADMIN) {
                $this->renderError(403, 'Unauthorized access');
            }

            $this->validateCSRF();

            $action = $this->postParam('action');
            switch ($action) {
                case 'add':
                    $this->handleAdd();
                    break;
                case 'edit':
                    $this->handleEdit();
                    break;
                case 'delete':
                    $this->handleDelete();
                    break;
                default:
                    $this->renderError(400, 'Invalid action');
            }
        } catch (Exception $e) {
            $this->logError('Error handling product action', $e);
            $this->renderError(500, 'Error processing request');
        }
    }

    private function handleAdd() {
        try {
            $data = $this->validateProductData();
            
            $this->db->beginTransaction();
            
            $product = $this->model->create($data);
            
            if (isset($_FILES['image'])) {
                $this->handleImageUpload($product['id']);
            }
            
            $this->db->commit();
            $this->refreshProductCache();
            
            $this->renderJSON(['success' => true, 'product' => $product]);
        } catch (Exception $e) {
            $this->db->rollback();
            $this->logError('Error adding product', $e);
            $this->renderJSON(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    private function handleEdit() {
        try {
            $id = $this->postParam('id');
            if (!$id) {
                throw new Exception('Product ID is required');
            }

            $data = $this->validateProductData();
            
            $this->db->beginTransaction();
            
            $product = $this->model->update($id, $data);
            
            if (isset($_FILES['image'])) {
                $this->handleImageUpload($id);
            }
            
            $this->db->commit();
            $this->refreshProductCache();
            
            $this->renderJSON(['success' => true, 'product' => $product]);
        } catch (Exception $e) {
            $this->db->rollback();
            $this->logError('Error updating product', $e);
            $this->renderJSON(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    private function handleDelete() {
        try {
            $id = $this->postParam('id');
            if (!$id) {
                throw new Exception('Product ID is required');
            }

            $this->db->beginTransaction();
            
            $this->model->delete($id);
            
            $this->db->commit();
            $this->refreshProductCache();
            
            $this->renderJSON(['success' => true]);
        } catch (Exception $e) {
            $this->db->rollback();
            $this->logError('Error deleting product', $e);
            $this->renderJSON(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    private function validateProductData() {
        $required = ['name', 'price', 'description', 'category_id'];
        $data = [];
        
        foreach ($required as $field) {
            $value = $this->postParam($field);
            if (!$value) {
                throw new Exception("Field {$field} is required");
            }
            $data[$field] = InputValidator::sanitizeString($value);
        }
        
        $data['price'] = InputValidator::sanitizeFloat($data['price']);
        if ($data['price'] <= 0) {
            throw new Exception('Price must be greater than 0');
        }
        
        return $data;
    }

    protected function handleImageUpload($productId) {
        if (!InputValidator::validateFile($_FILES['image'], ['image/jpeg', 'image/png'], 5242880)) {
            throw new Exception('Invalid image file');
        }

        $result = $this->productImage->uploadImage($_FILES['image'], $productId);
        if (!$result['success']) {
            throw new Exception($result['error']);
        }
    }

    private function clearProductCache() {
        $this->cache->forget('all_products');
        $this->cache->forget('featured_products_4');
        // Add more if there are other cache keys for products
    }

    private function refreshProductCache() {
        $products = $this->model->getAllProducts();
        $this->cache->put('all_products', $products, 3600);
        $featuredProducts = $this->model->getFeaturedProducts(4);
        $this->cache->put('featured_products_4', $featuredProducts, 1800);
    }

    protected function clearCache($key = null) {
        if ($key !== null) {
            $this->cache->delete($key);
            return;
        }
        
        // Clear all product-related caches
        $this->clearProductCache();
        
        // Clear category-specific caches if they exist
        for ($i = 1; $i <= 10; $i++) { // Assuming we have up to 10 categories
            $this->cache->delete('products_category_' . $i);
        }
    }
}