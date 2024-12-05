<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session_validation.php';
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/User.php';

// Initialize database connection
$database = new Database();
$db = $database->connect();

if (!$db) {
    die("Error: Could not connect to the database.");
}

// Ensure the user is an admin
require_admin();

// Initialize models
$order = new Order($db);
$product = new Product($db);
$user = new User($db);

// Get statistics
$totalOrders = $order->getTotalOrders();
$totalRevenue = $order->getTotalRevenue();
$totalProducts = $product->getTotalProducts();
$totalCustomers = $user->getTotalCustomers();

// Get recent orders
$recentOrders = $order->getRecentOrders(5);

// Get low stock products
$lowStockProducts = $product->getLowStockProducts(5);
?>

<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Total Orders</h6>
                            <h3 class="mb-0"><?php echo number_format($totalOrders); ?></h3>
                        </div>
                        <div class="icon bg-white text-primary rounded-circle">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Total Revenue</h6>
                            <h3 class="mb-0">$<?php echo number_format($totalRevenue, 2); ?></h3>
                        </div>
                        <div class="icon bg-white text-success rounded-circle">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Total Products</h6>
                            <h3 class="mb-0"><?php echo number_format($totalProducts); ?></h3>
                        </div>
                        <div class="icon bg-white text-info rounded-circle">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Total Customers</h6>
                            <h3 class="mb-0"><?php echo number_format($totalCustomers); ?></h3>
                        </div>
                        <div class="icon bg-white text-warning rounded-circle">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders and Low Stock Products -->
    <div class="row">
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Orders</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentOrders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['order_id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['username']); ?></td>
                                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td>
                                        <span class="badge status-<?php echo $order['status']; ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Low Stock Products</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lowStockProducts as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                    <td>
                                        <span class="badge bg-danger">
                                            <?php echo $product['stock_quantity']; ?> left
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<a href="manage_products.php">Manage Products</a>
<a href="manage_users.php">Manage Users</a>
<a href="manage_orders.php">Manage Orders</a>

<?php require_once 'includes/admin_footer.php'; ?>