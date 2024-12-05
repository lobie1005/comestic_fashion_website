<?php
session_start();
require_once __DIR__ . '/../includes/login_check.php';

// Require login for product pages
checkLoginStatus(true);

global $db;

$product = new Product($db);
$productImage = new ProductImage($db);

// Get category filter from URL if exists
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

// Get search query if exists
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Get products based on filters
$products = $product->getProducts($category_id, $search_query);

// Get all categories for filter
$categories = $product->getAllCategories();
?>

<div class="container py-5">
    <!-- Filter and Search Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="categoryFilter" data-bs-toggle="dropdown">
                    <?php echo $category_id ? 'Category: ' . htmlspecialchars($categories[$category_id]['category_name']) : 'All Categories'; ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?">All Categories</a></li>
                    <?php foreach ($categories as $category): ?>
                        <li><a class="dropdown-item" href="?category=<?php echo $category['category_id']; ?>">
                            <?php echo htmlspecialchars($category['category_name']); ?>
                        </a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <form class="d-flex" action="" method="GET">
                <?php if ($category_id): ?>
                    <input type="hidden" name="category" value="<?php echo $category_id; ?>">
                <?php endif; ?>
                <input class="form-control me-2" type="search" name="search" placeholder="Search products..." 
                       value="<?php echo htmlspecialchars($search_query); ?>">
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <?php foreach ($products as $product): ?>
            <div class="col">
                <div class="card h-100 product-card">
                    <img src="<?php echo BASE_URL; ?>/uploads/products/<?php echo htmlspecialchars($product['image_path'] ?? 'default.jpg'); ?>" 
                         class="card-img-top product-image" 
                         alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                        <p class="card-text text-truncate"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="card-text">
                            <strong>$<?php echo number_format($product['price'], 2); ?></strong>
                        </p>
                        <div class="d-grid">
                            <a href="<?php echo BASE_URL; ?>/product/<?php echo $product['product_id']; ?>" 
                               class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php if (empty($products)): ?>
    <div class="alert alert-info mt-4">No products found.</div>
<?php endif; ?>

<script src="<?php echo BASE_URL; ?>/assets/js/cart.js"></script>
<?php require_once 'includes/footer.php'; ?>