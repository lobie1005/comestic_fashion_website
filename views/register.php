<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Cosmetics Fashion</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/style.css">
</head>

<body>

    <?php include_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h1 class="text-center mb-4">Create Account</h1>

                        <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php 
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                            ?>
                        </div>
                        <?php endif; ?>

                        <form action="<?php echo BASE_URL; ?>/register" method="POST" id="register-form">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required aria-label="Username">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required aria-label="Email">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required aria-label="Password">
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password"
                                    name="confirm_password" required aria-label="Confirm Password">
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required aria-label="Terms and Conditions">
                                <label class="form-check-label" for="terms">I agree to the <a
                                        href="<?php echo BASE_URL; ?>/terms">Terms and Conditions</a></label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="mb-0">Already have an account? <a href="<?php echo BASE_URL; ?>/login">Login
                                    here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/../includes/footer.php'; ?>

    <script src="<?php echo BASE_URL; ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
    <script>
    const registerForm = document.getElementById('register-form');

    registerForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(registerForm);
        const username = formData.get('username');
        const email = formData.get('email');
        const password = formData.get('password');
        const confirmPassword = formData.get('confirm_password');
        const terms = formData.get('terms');

        if (!username || !email || !password || !confirmPassword || !terms) {
            alert('Please fill in all fields');
            return;
        }

        if (password !== confirmPassword) {
            alert('Password and confirm password do not match');
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo BASE_URL; ?>/register');
        xhr.send(formData);

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);

                if (response.success) {
                    alert('Registration successful');
                    window.location.href = '<?php echo BASE_URL; ?>/';
                } else {
                    alert(response.message);
                }
            }
        };
    });
    </script>
</body>

</html>