<?php
require_once '../models/order.php';
require_once '../models/user.php';
session_start();

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    die("Unauthorized access.");
}

// Fetch data for admin dashboard
$orders = get_all_orders();
$users = get_all_users();
$products = get_all_products();

include '../admin/dashboard.php';
?>