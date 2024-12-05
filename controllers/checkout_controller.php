<?php
require_once '../models/order.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'checkout') {
    $user_id = $_SESSION['user_id'];
    $total_amount = floatval($_POST['total_amount']);
    $billing_address = trim($_POST['billing_address']);
    $shipping_address = trim($_POST['shipping_address']);
    $payment_method = trim($_POST['payment_method']);

    // Validate input
    if ($total_amount <= 0 || empty($billing_address) || empty($shipping_address) || empty($payment_method)) {
        $error = "Invalid checkout details. Please check your inputs.";
        include '../views/checkout.php';
        exit();
    }

    // Sanitize addresses
    $billing_address = filter_var($billing_address, FILTER_SANITIZE_STRING);
    $shipping_address = filter_var($shipping_address, FILTER_SANITIZE_STRING);

    // Create order and save order items
    $order_id = create_order($user_id, $total_amount, $billing_address, $shipping_address, $payment_method);

    if ($order_id) {
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            add_order_item($order_id, $product_id, $quantity);
        }

        // Clear cart after successful order
        unset($_SESSION['cart']);
        header("Location: ../views/order_confirmation.php");
        exit();
    } else {
        $error = "Failed to place order. Please try again later.";
        include '../views/checkout.php';
        exit();
    }
}

include '../views/checkout.php';
?>