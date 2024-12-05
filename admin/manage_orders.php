<?php
session_start();
require_once '../models/order.php';

// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    die("Unauthorized access.");
}

// Update order status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_status') {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['status'];
    update_order_status($order_id, $new_status);
}

// Fetch all orders
$orders = get_all_orders();

include '../includes/admin_header.php';
?>

<h1>Manage Orders</h1>

<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>User ID</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td><?php echo $order['order_id']; ?></td>
            <td><?php echo $order['user_id']; ?></td>
            <td><?php echo $order['total_amount']; ?></td>
            <td><?php echo $order['status']; ?></td>
            <td>
                <form action="manage_orders.php" method="post">
                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                    <select name="status">
                        <option value="pending" <?php if ($order['status'] == 'pending') echo 'selected'; ?>>Pending
                        </option>
                        <option value="processing" <?php if ($order['status'] == 'processing') echo 'selected'; ?>>
                            Processing</option>
                        <option value="shipped" <?php if ($order['status'] == 'shipped') echo 'selected'; ?>>Shipped
                        </option>
                        <option value="completed" <?php if ($order['status'] == 'completed') echo 'selected'; ?>>
                            Completed</option>
                        <option value="canceled" <?php if ($order['status'] == 'canceled') echo 'selected'; ?>>Canceled
                        </option>
                    </select>
                    <input type="hidden" name="action" value="update_status">
                    <button type="submit">Update</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/admin_footer.php'; ?>