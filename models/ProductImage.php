<?php
class ProductImage {
    private $db;
    private $upload_path = '../uploads/products/';

    public function __construct($db) {
        $this->db = $db;
        
        // Create upload directory if it doesn't exist
        if (!file_exists($this->upload_path)) {
            mkdir($this->upload_path, 0777, true);
        }
    }

    private function isValidImage($image_file) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB

        // Check file type
        if (!in_array($image_file['type'], $allowed_types)) {
            return false;
        }

        // Check file size
        if ($image_file['size'] > $max_size) {
            return false;
        }

        return true;
    }

    public function addImage($product_id, $image_file, $is_primary = false) {
        // Validate image file
        if (!$this->isValidImage($image_file)) {
            return ['success' => false, 'error' => 'Invalid image file'];
        }

        // Generate unique filename
        $filename = uniqid() . '_' . basename($image_file['name']);
        $target_path = $this->upload_path . $filename;

        // Move uploaded file
        if (move_uploaded_file($image_file['tmp_name'], $target_path)) {
            // If this is primary image, reset other primary images
            if ($is_primary) {
                $this->resetPrimaryImages($product_id);
            }

            // Insert image record
            $query = "INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('isi', $product_id, $filename, $is_primary);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'image_id' => $this->db->insert_id,
                    'image_path' => $filename
                ];
            }
        }

        return ['success' => false, 'error' => 'Failed to upload image. Please try again later.'];
    }

    public function getProductImages($product_id) {
        $query = "SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, created_at ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPrimaryImage($product_id) {
        $query = "SELECT * FROM product_images WHERE product_id = ? AND is_primary = 1 LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    public function deleteImage($image_id) {
        // Get image info before deleting
        $query = "SELECT image_path FROM product_images WHERE image_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $image_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $image = $result->fetch_assoc();

        if ($image) {
            // Delete file from server
            $file_path = $this->upload_path . $image['image_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            // Delete record from database
            $query = "DELETE FROM product_images WHERE image_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $image_id);
            return $stmt->execute();
        }

        return false;
    }

    public function resetPrimaryImages($product_id) {
        $query = "UPDATE product_images SET is_primary = 0 WHERE product_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $product_id);
        return $stmt->execute();
    }

    public function setPrimaryImage($image_id, $product_id) {
        // First reset all primary images for this product
        $this->resetPrimaryImages($product_id);

        // Set new primary image
        $query = "UPDATE product_images SET is_primary = 1 WHERE image_id = ? AND product_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $image_id, $product_id);
        return $stmt->execute();
    }

    public function getImagePath() {
        return $this->upload_path;
    }
}
?>
