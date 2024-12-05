<?php
session_start();
require_once '../models/user.php';

// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    die("Unauthorized access.");
}

// Fetch all users
$users = get_all_users();

include '../includes/admin_header.php';
?>

<h1>Manage Users</h1>

<table>
    <thead>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['user_id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo ($user['role_id'] == 1) ? 'Customer' : 'Admin'; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/admin_footer.php'; ?>