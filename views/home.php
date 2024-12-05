<?php
// Include necessary files for database connection and common header
include 'includes/header.php';
include 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Cosmetics Fashion</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <!-- Add Bootstrap or other libraries for responsiveness -->
    <link href="<?php echo BASE_URL; ?>/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navigation Bar -->
    <?php include 'includes/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section text-center py-5" style="background: #f8f9fa;">
        <div class="container">
            <h1>Welcome to Cosmetics Fashion</h1>
            <p>Discover the best in beauty products for your everyday needs.</p>
            <a href="product_list.php" class="btn btn-primary mt-3">Shop Now</a>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products py-5">
        <div class="container">
            <h2 class="text-center mb-4">Featured Products</h2>
            <div class="row">
                <?php
                // Fetch featured products from the database
                $query = "SELECT product_id, name, price, image_url FROM products WHERE stock_quantity > 0 LIMIT 4";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($product = $result->fetch_assoc()) {
                        echo '
                        <div class="col-md-3 mb-4">
                            <div class="card">
                                <img src="uploads/products/' . htmlspecialchars($product['image_url']) . '" class="card-img-top" alt="' . htmlspecialchars($product['name']) . '">
                                <div class="card-body text-center">
                                    <h5 class="card-title">' . htmlspecialchars($product['name']) . '</h5>
                                    <p class="card-text">$' . number_format($product['price'], 2) . '</p>
                                    <a href="product_detail.php?product_id=' . $product['product_id'] . '" class="btn btn-outline-primary btn-sm">View Details</a>
                                </div>
                            </div>
                        </div>
                        ';
                    }
                } else {
                    echo '<p class="text-center">No featured products available.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="<?php echo BASE_URL; ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>