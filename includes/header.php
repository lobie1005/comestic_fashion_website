<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the current page from the URL
$current_page = basename($_SERVER['PHP_SELF']);
$is_auth_page = in_array($current_page, ['login.php', 'register.php']);
?>
<!-- Announcement Bar -->
<div class="announcement-bar bg-primary text-white py-2">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center text-info">
                <p class="mb-0">Free shipping on orders over $50! Use code: FREESHIP50</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Header with Logo and Search -->
<header class="header-main py-3">
    <div class="container">
        <div class="row align-items-center">
            <!-- Logo -->
            <div class="col-md-<?php echo $is_auth_page ? '12' : '3'; ?> text-center text-md-start mb-3 mb-md-0">
                <a href="<?php echo BASE_URL; ?>" class="text-decoration-none">
                    <h1 class="mb-0 text-primary">Cosmetics Fashion</h1>
                </a>
            </div>
            
            <?php if (!$is_auth_page): ?>
            <!-- Search Bar -->
            <div class="col-md-6 mb-3 mb-md-0">
                <form action="<?php echo BASE_URL; ?>/products" method="GET" class="search-form">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search for products...">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Cart -->
            <div class="col-md-3 text-end">
                <a href="<?php echo BASE_URL; ?>/cart" class="btn btn-outline-primary position-relative">
                    <i class="fas fa-shopping-cart"></i> Cart
                    <?php if (isset($_SESSION['cart_count']) && $_SESSION['cart_count'] > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo $_SESSION['cart_count']; ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</header>