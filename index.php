<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Homepage</title>
</head>

<body>
    <?php
    require_once __DIR__ . '/config/constants.php';
    require_once __DIR__ . '/config/database.php';
    require_once __DIR__ . '/controllers/product_controller.php';
    require_once __DIR__ . '/includes/custom_session_handler.php';

    // Initialize session with security features
    CustomSessionHandler::init();

    try {
        // Initialize database connection
        $db = (new Database())->connect();

        // Get the current page and check login status
        $current_page = basename($_SERVER['PHP_SELF']);
        $is_index = $current_page === 'index.php';
        $is_logged_in = CustomSessionHandler::isAuthenticated();

        // Initialize product controller
        $productController = new ProductController($db);
    } catch (Exception $e) {
        error_log("Error in index.php: " . $e->getMessage());
        die("An error occurred. Please try again later.");
    }

    // Include header
    include 'includes/header.php';
    include_once __DIR__ . '/includes/navbar.php';

    // Featured Products Section
    ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Featured Products</h2>
        <div class="row">
            <?php
            // Example of implementing caching for featured products
            $cache_key = 'featured_products';
            $cache_time = 3600; // Cache for 1 hour
            $featured_products = apcu_fetch($cache_key);
            if ($featured_products === false) {
                $featured_products = $productController->getFeaturedProducts(4);
                apcu_store($cache_key, $featured_products, $cache_time);
            }
            
            if (!empty($featured_products)):
                foreach ($featured_products as $product):
                    $image_url = !empty($product['image_url']) ? $product['image_url'] : 'assets/images/placeholder.jpg';
            ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <img src="<?php echo $image_url; ?>" class="card-img-top product-img" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?></p>
                        <p class="card-text"><strong>Price: $<?php echo number_format($product['price'], 2); ?></strong></p>
                        <?php if ($is_logged_in): ?>
                            <a href="<?php echo BASE_URL; ?>/product/<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                        <?php else: ?>
                            <button class="btn btn-primary login-required" onclick="requireLogin()">View Details</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php 
                endforeach;
            else:
            ?>
            <div class="col-12 text-center">
                <p class="lead">No products available at the moment.</p>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if (!$is_logged_in): ?>
        <div class="text-center mt-4">
            <p class="lead">Login to see more products and access all features!</p>
            <a href="<?php echo BASE_URL; ?>/login" class="btn btn-lg btn-primary">Login Now</a>
        </div>
        <?php else: ?>
        <div class="text-center mt-4">
            <a href="<?php echo BASE_URL; ?>/products" class="btn btn-lg btn-primary">View All Products</a>
        </div>
        <?php endif; ?>
    </div>

    <script>
    function requireLogin() {
        if (confirm('To view product details and access more features, you need to login. Would you like to login now?')) {
            window.location.href = '<?php echo BASE_URL; ?>/login';
        }
    }
    </script>

    <!-- Add some basic CSS for product images -->
    <style>
    .product-img {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    </style>

    <?php
    // Include footer
    include 'includes/footer.php';
    ?>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>