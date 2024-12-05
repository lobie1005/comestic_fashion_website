<?php
require_once __DIR__ . '/../core/Model.php';

class Product extends Model {
    protected $table = 'products';
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Function to create a new product
    public function createProduct($data) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                INSERT INTO products (
                    product_name, description, price, stock_quantity, 
                    category_id, image_url, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $data['product_name'],
                $data['description'],
                $data['price'],
                $data['stock_quantity'],
                $data['category_id'],
                $data['image_url']
            ]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error creating product: " . $e->getMessage());
            return false;
        }
    }

    // Function to update a product
    public function updateProduct($product_id, $data) {
        try {
            $this->db->beginTransaction();

            $updates = [];
            $values = [];
            
            foreach ($data as $key => $value) {
                if (in_array($key, ['product_name', 'description', 'price', 'stock_quantity', 'category_id', 'image_url'])) {
                    $updates[] = "$key = ?";
                    $values[] = $value;
                }
            }
            
            if (empty($updates)) {
                return false;
            }

            $values[] = $product_id;
            $query = "UPDATE products SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE product_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute($values);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error updating product: " . $e->getMessage());
            return false;
        }
    }

    // Function to delete a product
    public function deleteProduct($product_id) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("DELETE FROM products WHERE product_id = ?");
            $stmt->execute([$product_id]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error deleting product: " . $e->getMessage());
            return false;
        }
    }

    // Function to get a product by ID
    public function getProductById($product_id) {
        try {
            $stmt = $this->db->prepare("
                SELECT p.*, c.category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.category_id 
                WHERE p.product_id = ?
            ");
            $stmt->execute([$product_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting product: " . $e->getMessage());
            return null;
        }
    }

    // Function to get all products
    public function getAllProducts($page = 1, $limit = 10) {
        try {
            $offset = ($page - 1) * $limit;
            $stmt = $this->db->prepare("
                SELECT p.*, c.category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.category_id 
                ORDER BY p.created_at DESC 
                LIMIT ? OFFSET ?
            ");
            $stmt->execute([$limit, $offset]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting all products: " . $e->getMessage());
            return [];
        }
    }

    // Function to get products by category
    public function getProductsByCategory($category_id, $page = 1, $limit = 10) {
        try {
            $offset = ($page - 1) * $limit;
            $stmt = $this->db->prepare("
                SELECT p.*, c.category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.category_id 
                WHERE p.category_id = ? 
                ORDER BY p.created_at DESC 
                LIMIT ? OFFSET ?
            ");
            $stmt->execute([$category_id, $limit, $offset]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting products by category: " . $e->getMessage());
            return [];
        }
    }

    // Function to search products
    public function searchProducts($search_term) {
        try {
            $search_term = "%$search_term%";
            $stmt = $this->db->prepare("
                SELECT p.*, c.category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.category_id 
                WHERE p.product_name LIKE ? OR p.description LIKE ?
                ORDER BY p.created_at DESC
            ");
            $stmt->execute([$search_term, $search_term]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error searching products: " . $e->getMessage());
            return [];
        }
    }

    // Function to get total products count
    public function getTotalProducts() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM products");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting total products: " . $e->getMessage());
            return 0;
        }
    }

    // Function to get low stock products
    public function getLowStockProducts($limit = 5) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM products 
                WHERE stock_quantity <= 10 
                ORDER BY stock_quantity ASC 
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting low stock products: " . $e->getMessage());
            return [];
        }
    }

    // Function to update product stock
    public function updateStock($product_id, $quantity) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                UPDATE products 
                SET stock_quantity = stock_quantity + ?, 
                    updated_at = NOW() 
                WHERE product_id = ?
            ");
            $stmt->execute([$quantity, $product_id]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error updating product stock: " . $e->getMessage());
            return false;
        }
    }
}
?>
