<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Create a new user with role
    public function create($username, $email, $password, $role_id = 1) {
        try {
            // Check if role exists
            $roleQuery = "SELECT role_id FROM info_role WHERE role_id = ?";
            $roleStmt = $this->db->prepare($roleQuery);
            $roleStmt->bind_param('i', $role_id);
            $roleStmt->execute();
            $roleResult = $roleStmt->get_result();
            
            if (!$roleResult->fetch_assoc()) {
                throw new Exception("Invalid role specified");
            }

            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Begin transaction
            $this->db->begin_transaction();

            try {
                // Insert into users table
                $query = "INSERT INTO users (username, email, password_hash, role_id) VALUES (?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param('sssi', $username, $email, $password_hash, $role_id);
                $stmt->execute();
                
                $user_id = $this->db->insert_id;

                // Insert into info_user table
                $infoQuery = "INSERT INTO info_user (user_id) VALUES (?)";
                $infoStmt = $this->db->prepare($infoQuery);
                $infoStmt->bind_param('i', $user_id);
                $infoStmt->execute();

                // Commit transaction
                $this->db->commit();
                return true;
            } catch (Exception $e) {
                $this->db->rollback();
                throw $e;
            }
        } catch (Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            throw new Exception("Failed to create user. Please try again later.");
        }
    }

    // Find user by email
    public function findByEmail($email) {
        try {
            $query = "SELECT u.*, r.role_name 
                     FROM users u 
                     JOIN info_role r ON u.role_id = r.role_id 
                     WHERE u.email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error finding user by email: " . $e->getMessage());
            return null;
        }
    }

    // Find user by username
    public function findByUsername($username) {
        try {
            $query = "SELECT u.*, r.role_name 
                     FROM users u 
                     JOIN info_role r ON u.role_id = r.role_id 
                     WHERE u.username = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error finding user by username: " . $e->getMessage());
            return null;
        }
    }

    // Find user by ID with additional info
    public function findById($id) {
        try {
            $query = "SELECT u.*, r.role_name, i.*
                     FROM users u 
                     JOIN info_role r ON u.role_id = r.role_id 
                     LEFT JOIN info_user i ON u.user_id = i.user_id
                     WHERE u.user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error finding user by ID: " . $e->getMessage());
            return null;
        }
    }

    // Verify password
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    // Update user's password
    public function updatePassword($userId, $newPassword) {
        try {
            $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $query = "UPDATE users SET password_hash = ? WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('si', $password_hash, $userId);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error updating password: " . $e->getMessage());
            return false;
        }
    }

    // Update user info
    public function updateInfo($userId, $data) {
        try {
            $allowedFields = [
                'first_name', 'last_name', 'phone_number', 
                'address_line_1', 'address_line_2', 'city', 
                'state', 'zip_code', 'country', 
                'profile_picture_url', 'date_of_birth'
            ];

            $updates = [];
            $types = '';
            $values = [];

            foreach ($data as $key => $value) {
                if (in_array($key, $allowedFields)) {
                    $updates[] = "$key = ?";
                    $types .= 's';
                    $values[] = $value;
                }
            }

            if (empty($updates)) {
                return false;
            }

            $values[] = $userId;
            $types .= 'i';

            $query = "UPDATE info_user SET " . implode(', ', $updates) . " WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param($types, ...$values);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error updating user info: " . $e->getMessage());
            return false;
        }
    }

    // Get user's role
    public function getUserRole($userId) {
        try {
            $query = "SELECT r.role_name 
                     FROM users u 
                     JOIN info_role r ON u.role_id = r.role_id 
                     WHERE u.user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            return $result ? $result['role_name'] : null;
        } catch (Exception $e) {
            error_log("Error getting user role: " . $e->getMessage());
            return null;
        }
    }

    // Check if user has specific role
    public function hasRole($userId, $roleName) {
        return $this->getUserRole($userId) === $roleName;
    }

    // Get total customers
    public function getTotalCustomers() {
        $sql = "SELECT COUNT(*) as total FROM users WHERE role_id = 1"; 
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
?>
