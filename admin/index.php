<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/session_validation.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

// Get counts from database
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get product count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $productCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Get order count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
    $orderCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Get user count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE is_admin = 0");
    $userCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Get low stock products
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products WHERE stock_quantity < 10");
    $lowStockCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

} catch(PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<?php require_once '../includes/admin_header.php'; ?>

<div class="container-fluid px-4 py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Dashboard Overview</h2>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4">
        <!-- Products Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Products</h6>
                            <h2 class="mb-0"><?php echo isset($productCount) ? $productCount : '0'; ?></h2>
                        </div>
                        <div class="icon bg-white text-primary">
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo BASE_URL; ?>/admin/manage_products.php">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Orders</h6>
                            <h2 class="mb-0"><?php echo isset($orderCount) ? $orderCount : '0'; ?></h2>
                        </div>
                        <div class="icon bg-white text-success">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo BASE_URL; ?>/admin/manage_orders.php">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Users Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Users</h6>
                            <h2 class="mb-0"><?php echo isset($userCount) ? $userCount : '0'; ?></h2>
                        </div>
                        <div class="icon bg-white text-info">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo BASE_URL; ?>/admin/manage_users.php">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Low Stock Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Low Stock Items</h6>
                            <h2 class="mb-0"><?php echo isset($lowStockCount) ? $lowStockCount : '0'; ?></h2>
                        </div>
                        <div class="icon bg-white text-warning">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo BASE_URL; ?>/admin/manage_products.php?filter=low_stock">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($error)): ?>
    <div class="alert alert-danger mt-4">
        <?php echo $error; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/admin_footer.php'; ?>
