<?php
require_once __DIR__ . '/../core/Model.php';

class ProductModel extends Model {
    protected $table = 'products';
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'stock_quantity',
        'image_url',
        'featured'
    ];

    public function getAllProducts() {
        try {
            $sql = "SELECT p.*, c.name as category_name, pi.image_url 
                   FROM {$this->table} p 
                   LEFT JOIN categories c ON p.category_id = c.id 
                   LEFT JOIN product_images pi ON p.id = pi.product_id 
                   GROUP BY p.id 
                   ORDER BY p.created_at DESC";
            
            $stmt = $this->db->query($sql);
            return array_map([$this, 'processResult'], $stmt->fetchAll());
        } catch (Exception $e) {
            Logger::error('Error getting all products', ['error' => $e->getMessage()]);
            throw new Exception('Failed to fetch products');
        }
    }

    public function getFeaturedProducts($limit = 8) {
        try {
            $sql = "SELECT p.*, c.name as category_name, pi.image_url 
                   FROM {$this->table} p 
                   LEFT JOIN categories c ON p.category_id = c.id 
                   LEFT JOIN product_images pi ON p.id = pi.product_id 
                   WHERE p.featured = 1 
                   GROUP BY p.id 
                   ORDER BY p.created_at DESC 
                   LIMIT :limit";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return array_map([$this, 'processResult'], $stmt->fetchAll());
        } catch (Exception $e) {
            Logger::error('Error getting featured products', ['error' => $e->getMessage()]);
            throw new Exception('Failed to fetch featured products');
        }
    }

    public function findProductById($id) {
        try {
            $sql = "SELECT p.*, c.name as category_name, pi.image_url 
                   FROM {$this->table} p 
                   LEFT JOIN categories c ON p.category_id = c.id 
                   LEFT JOIN product_images pi ON p.id = pi.product_id 
                   WHERE p.id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $this->processResult($stmt->fetch());
        } catch (Exception $e) {
            Logger::error('Error finding product', ['id' => $id, 'error' => $e->getMessage()]);
            throw new Exception('Failed to find product');
        }
    }

    public function updateStock($productId, $quantity) {
        try {
            $this->db->beginTransaction();

            $sql = "UPDATE {$this->table} 
                   SET stock_quantity = stock_quantity + :quantity 
                   WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->execute();

            $this->db->commit();
            return $this->find($productId);
        } catch (Exception $e) {
            $this->db->rollBack();
            Logger::error('Error updating stock', [
                'product_id' => $productId,
                'quantity' => $quantity,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Failed to update stock');
        }
    }

    protected function processResult($result) {
        if (!$result) {
            return null;
        }

        // Format price to 2 decimal places
        if (isset($result['price'])) {
            $result['price'] = number_format($result['price'], 2, '.', '');
        }

        // Add default image URL if missing
        if (empty($result['image_url'])) {
            $result['image_url'] = 'assets/images/placeholder.jpg';
        }

        // Add formatted date
        if (isset($result['created_at'])) {
            $result['formatted_date'] = date('F j, Y', strtotime($result['created_at']));
        }

        return $result;
    }

    public function searchProducts($query, $categoryId = null) {
        try {
            $params = ['query' => "%{$query}%"];
            $sql = "SELECT p.*, c.name as category_name, pi.image_url 
                   FROM {$this->table} p 
                   LEFT JOIN categories c ON p.category_id = c.id 
                   LEFT JOIN product_images pi ON p.id = pi.product_id 
                   WHERE (p.name LIKE :query OR p.description LIKE :query)";
            
            if ($categoryId) {
                $sql .= " AND p.category_id = :category_id";
                $params['category_id'] = $categoryId;
            }
            
            $sql .= " GROUP BY p.id ORDER BY p.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return array_map([$this, 'processResult'], $stmt->fetchAll());
        } catch (Exception $e) {
            Logger::error('Error searching products', [
                'query' => $query,
                'category_id' => $categoryId,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Failed to search products');
        }
    }
}