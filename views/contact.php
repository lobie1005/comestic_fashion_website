<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Cosmetics Fashion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <style>
        .contact-info-card {
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .contact-info-card:hover {
            transform: translateY(-5px);
        }

        .contact-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .map-container {
            height: 400px;
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <?php include_once __DIR__ . '/../includes/navbar.php'; ?>

    <!-- Contact Header -->
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="display-4 mb-3">Contact Us</h1>
                    <p class="lead">We'd love to hear from you. Send us a message and we'll respond as soon as possible.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information Cards -->
    <div class="container py-5">
        <div class="row mb-5">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card h-100 text-center p-4 contact-info-card" tabindex="0">
                    <div class="card-body">
                        <i class="fas fa-map-marker-alt contact-icon"></i>
                        <h5 class="card-title">Visit Us</h5>
                        <p class="card-text">BTEC FPT <br>Trinh Van Bo Street, Nam Tu Liem, Ha Noi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card h-100 text-center p-4 contact-info-card" tabindex="0">
                    <div class="card-body">
                        <i class="fas fa-phone contact-icon"></i>
                        <h5 class="card-title">Call Us</h5>
                        <p class="card-text">+84 888-888-8888<br>Mon - Fri: 9:00 AM - 8:00 PM<br>Sat - Sun: 10:00 AM -
                            6:00 PM</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4 contact-info-card" tabindex="0">
                    <div class="card-body">
                        <i class="fas fa-envelope contact-icon"></i>
                        <h5 class="card-title">Email Us</h5>
                        <p class="card-text">contact@cosmeticsfashion.com<br>support@cosmeticsfashion.com</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form and Map -->
        <div class="row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="mb-4">Send us a Message</h3>
                        <?php if (isset($_SESSION['contact_success'])): ?>
                            <div class="alert alert-success">
                                <?php
                                echo $_SESSION['contact_success'];
                                unset($_SESSION['contact_success']);
                                ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['contact_error'])): ?>
                            <div class="alert alert-danger">
                                <?php
                                echo $_SESSION['contact_error'];
                                unset($_SESSION['contact_error']);
                                ?>
                            </div>
                        <?php endif; ?>

                        <form id="contact-form" action="<?php echo BASE_URL; ?>/contact/submit" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.8630462408178!2d105.74388971011204!3d21.038165180532584!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3134550dfe77114b%3A0x799dca0203fc6c74!2sBTEC%20FPT!5e0!3m2!1sen!2s!4v1733388654488!5m2!1sen!2s"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
    <script>
        const contactForm = document.getElementById('contact-form');

        contactForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(contactForm);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo BASE_URL; ?>/contact/submit');
            xhr.send(formData);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert('Message sent successfully! We will get back to you soon.');
                            contactForm.reset();
                        } else {
                            alert(response.message || 'An error occurred. Please try again.');
                        }
                    } else {
                        alert('An error occurred. Please try again later.');
                    }
                }
            };
        });
    </script>
</body>

</html>