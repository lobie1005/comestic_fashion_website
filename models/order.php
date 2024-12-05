<?php
require_once __DIR__ . '/../core/Model.php';

class Order extends Model {
    protected $table = 'orders';
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Function to create a new order
    public function createOrder($user_id, $total_amount, $billing_address, $shipping_address, $payment_method) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                INSERT INTO orders (user_id, total_amount, billing_address, shipping_address, payment_method, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$user_id, $total_amount, $billing_address, $shipping_address, $payment_method]);
            $order_id = $this->db->lastInsertId();

            $this->db->commit();
            return $order_id;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error creating order: " . $e->getMessage());
            return false;
        }
    }

    // Function to add an item to an order
    public function addOrderItem($order_id, $product_id, $quantity, $price) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price, created_at) 
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$order_id, $product_id, $quantity, $price]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error adding order item: " . $e->getMessage());
            return false;
        }
    }

    // Function to get all orders
    public function getAllOrders() {
        try {
            $stmt = $this->db->query("SELECT * FROM orders");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting all orders: " . $e->getMessage());
            return [];
        }
    }

    // Function to update order status
    public function updateOrderStatus($order_id, $new_status) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE order_id = ?");
            $stmt->execute([$new_status, $order_id]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error updating order status: " . $e->getMessage());
            return false;
        }
    }

    // Function to get all orders for a user
    public function getUserOrders($user_id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = ?");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting user orders: " . $e->getMessage());
            return [];
        }
    }

    // Function to get total orders
    public function getTotalOrders() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM orders");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting total orders: " . $e->getMessage());
            return 0;
        }
    }

    // Function to get total revenue
    public function getTotalRevenue() {
        try {
            $stmt = $this->db->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status != 'canceled'");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting total revenue: " . $e->getMessage());
            return 0;
        }
    }

    // Function to get recent orders
    public function getRecentOrders($limit = 5) {
        try {
            $stmt = $this->db->prepare("
                SELECT o.*, u.username 
                FROM orders o 
                JOIN users u ON o.user_id = u.user_id 
                ORDER BY o.created_at DESC 
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting recent orders: " . $e->getMessage());
            return [];
        }
    }
}
?>
