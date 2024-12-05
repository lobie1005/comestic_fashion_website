<?php
// Check if user is logged in
if (!isset($_SESSION[SESSION_USER_ID])) {
    header("Location: " . BASE_URL . "/login.php");
    exit();
}

$user = isset($userData) ? $userData : null;
?>

<div class="container py-5">
    <h2>Edit Profile</h2>
    <form action="<?php echo BASE_URL; ?>/controllers/update_profile.php" method="POST">
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required aria-label="Full Name">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required aria-label="Email">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" aria-label="Phone">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3" aria-label="Address"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>