<?php
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the current page from the URL
$current_page = basename($_SERVER['REQUEST_URI']);
if (strpos($current_page, '?') !== false) {
    $current_page = substr($current_page, 0, strpos($current_page, '?'));
}

// Check if we're on login or register page
$is_auth_page = in_array($current_page, ['login', 'register']);
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
            <img src="<?php echo IMAGES_PATH; ?>/logo.png" alt="Cosmetics Fashion" height="40">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === '' || $current_page === 'index.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'products' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/products">Products</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown">
                        Categories
                    </a>
                    <ul class="dropdown-menu">
                        <?php
                        // TODO: Fetch categories from database
                        $categories = ['Skincare', 'Makeup', 'Fragrance', 'Hair Care'];
                        foreach ($categories as $category) {
                            echo '<li><a class="dropdown-item" href="' . BASE_URL . '/products?category=' . urlencode($category) . '">' . $category . '</a></li>';
                        }
                        ?>
                    </ul>
                </li>
            </ul>

            <?php if (!$is_auth_page): ?>
            <form class="d-flex me-3" action="<?php echo BASE_URL; ?>/products" method="GET">
                <input class="form-control me-2" type="search" placeholder="Search products..." name="search">
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>
            <?php endif; ?>

            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo BASE_URL; ?>/cart" class="btn btn-outline-primary me-2">
                        <i class="fas fa-shopping-cart"></i>
                        <?php
                        if (isset($_SESSION['cart_count']) && $_SESSION['cart_count'] > 0) {
                            echo '<span class="badge bg-danger">' . $_SESSION['cart_count'] . '</span>';
                        }
                        ?>
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userMenu"
                            data-bs-toggle="dropdown">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/profile">My Profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/orders">My Orders</a></li>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 2): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin">Admin Dashboard</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/logout">Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <?php if ($current_page !== 'login'): ?>
                        <a href="<?php echo BASE_URL; ?>/login" class="btn <?php echo $current_page === 'register' ? 'btn-primary' : 'btn-outline-primary'; ?> me-2">Login</a>
                    <?php endif; ?>
                    <?php if ($current_page !== 'register'): ?>
                        <a href="<?php echo BASE_URL; ?>/register" class="btn <?php echo $current_page === 'login' ? 'btn-primary' : 'btn-outline-primary'; ?>">Register</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>