<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/constants.php';

try {
    echo "Attempting to connect to database...<br>";
    echo "Host: " . DB_HOST . "<br>";
    echo "Database: " . DB_NAME . "<br>";
    echo "User: " . DB_USER . "<br>";
    
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "Database connection successful!<br>";
    
    // Check if users table exists and has correct structure
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "Users table exists!<br>";
        
        // Check table structure
        $stmt = $pdo->query("DESCRIBE users");
        echo "Users table structure:<br>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo $row['Field'] . " - " . $row['Type'] . "<br>";
        }
        
        // Check if any users exist
        $stmt = $pdo->query("SELECT user_id, username, role_id FROM users");
        echo "<br>Existing users:<br>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "ID: " . $row['user_id'] . ", Username: " . $row['username'] . ", Role: " . $row['role_id'] . "<br>";
        }
    } else {
        echo "Users table does not exist! Creating it now...<br>";
        
        // Create users table
        $sql = "CREATE TABLE users (
            user_id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            role_id INT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $pdo->exec($sql);
        echo "Users table created successfully!<br>";
        
        // Create admin user
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password_hash, role_id) 
                VALUES ('admin', 'admin@example.com', ?, 2)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$admin_password]);
        echo "Admin user created with:<br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
        echo "Role: Administrator (2)<br>";
    }
    
} catch (PDOException $e) {
    echo "<div style='color: red; font-weight: bold;'>";
    echo "Database Error: " . $e->getMessage() . "<br>";
    echo "Error Code: " . $e->getCode() . "<br>";
    echo "</div>";
}
