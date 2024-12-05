<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Validate product_id and quantity
    if ($product_id <= 0 || $quantity <= 0) {
        header("Location: ../views/cart.php?error=Invalid product or quantity");
        exit();
    }

    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        $_SESSION['cart'][$product_id] += $quantity;
    }

    header("Location: ../views/cart.php");
    exit();
}

// Update cart item quantity
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Validate product_id and quantity
    if ($product_id <= 0 || $quantity < 0) {
        header("Location: ../views/cart.php?error=Invalid product or quantity");
        exit();
    }

    if ($quantity <= 0) {
        unset($_SESSION['cart'][$product_id]);
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    header("Location: ../views/cart.php");
    exit();
}

// Remove from cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'remove') {
    $product_id = intval($_POST['product_id']);

    // Validate product_id
    if ($product_id <= 0) {
        header("Location: ../views/cart.php?error=Invalid product");
        exit();
    }

    unset($_SESSION['cart'][$product_id]);

    header("Location: ../views/cart.php");
    exit();
}
?>