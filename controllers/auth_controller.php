<?php
class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function showLoginForm() {
        include __DIR__ . '/../views/login.php';
    }

    public function showRegisterForm() {
        include __DIR__ . '/../views/register.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
            $password = $_POST['password'];

            try {
                // Get user data
                $query = "SELECT * FROM users WHERE username = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$username]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password_hash'])) {
                    // Regenerate session ID to prevent session fixation
                    session_regenerate_id(true);
                    
                    // Set session variables
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['is_admin'] = ($user['role_id'] == 2); // Convert role_id to is_admin boolean
                    $_SESSION['last_activity'] = time();
                    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
                    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                    
                    // Set session timeout to 30 minutes
                    ini_set('session.gc_maxlifetime', 1800);
                    session_set_cookie_params(1800);

                    // Show success message and redirect based on role
                    echo "<script>
                        alert('Login successful!');
                        window.location.href = '" . BASE_URL . ($_SESSION['is_admin'] ? "/admin" : "") . "';
                    </script>";
                    exit();
                } else {
                    // Show error message
                    echo "<script>alert('Invalid username or password!');</script>";
                    $error = "Invalid username or password";
                    include __DIR__ . '/../views/login.php';
                }
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                $error = "An error occurred. Please try again.";
                include __DIR__ . '/../views/login.php';
            }
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
            $email = trim(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // Validate inputs
            if (!$username || !$email) {
                $error = "Invalid username or email";
                include __DIR__ . '/../views/register.php';
                return;
            }

            if ($password !== $confirm_password) {
                $error = "Passwords do not match";
                include __DIR__ . '/../views/register.php';
                return;
            }

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $query = "INSERT INTO users (username, email, password_hash, role_id) VALUES (?, ?, ?, 1)";
            $stmt = $this->db->prepare($query);
            
            try {
                $stmt->execute([$username, $email, $hashed_password]);
                
                // Set success message in session
                $_SESSION['success_message'] = "Registration successful! Please login.";
                
                // Redirect to login page after a short delay
                echo "<script>
                    alert('Registration successful!');
                    window.location.href = '" . BASE_URL . "/login.php';
                </script>";
                exit();
            } catch (PDOException $e) {
                $error = "Registration failed. Please try again.";
                include __DIR__ . '/../views/register.php';
            }
        }
    }

    public function logout() {
        session_destroy();
        header("Location: " . BASE_URL);
        exit();
    }
}
