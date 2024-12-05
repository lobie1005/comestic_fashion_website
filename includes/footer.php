        <?php
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        ?>
        <style>
            .main-footer {
                color: #000000 !important;
                padding: 3rem 0;
            }

            .main-footer h5,
            .main-footer p,
            .main-footer a,
            .main-footer li {
                color: #000000 !important;
            }

            .main-footer .social-links a {
                color: #000000 !important;
                margin-right: 1rem;
                font-size: 1.2rem;
                transition: opacity 0.3s;
            }

            .main-footer .social-links a:hover {
                opacity: 0.8;
            }

            section.newsletter-section {
                margin-bottom: 1rem;
            }

            .newsletter-section .container {
                border-radius: 2rem;
            }
        </style>

        <!-- Pre-Footer Newsletter Section -->
        <section class="newsletter-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 text-center">
                        <h3 class="mb-4">Subscribe to Our Newsletter</h3>
                        <p class="text-muted mb-4">Stay updated with our latest products and special offers!</p>
                        <form class="newsletter-form" action="<?php echo BASE_URL; ?>/subscribe" method="POST">
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Enter your email address"
                                    required>
                                <button class="btn btn-primary" type="submit">Subscribe</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Footer -->
        <footer class="main-footer bg-primary text-dark">
            <div class="container">
                <div class="row">
                    <!-- About Column -->
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <h5>About Cosmetics Fashion</h5>
                        <p class="mb-4">Your premier destination for premium beauty products. We bring you the finest
                            selection of cosmetics from around the world.</p>
                        <div class="social-links">
                            <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" title="Pinterest"><i class="fab fa-pinterest"></i></a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                        <h5>Quick Links</h5>
                        <ul class="footer-links">
                            <li><a href="<?php echo BASE_URL; ?>/products">Shop Now</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/about">About Us</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/contact">Contact Us</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/blog">Blog</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/faq">FAQs</a></li>
                        </ul>
                    </div>

                    <!-- Categories -->
                    <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                        <h5>Categories</h5>
                        <ul class="footer-links">
                            <li><a href="<?php echo BASE_URL; ?>/category/skincare">Skincare</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/category/makeup">Makeup</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/category/haircare">Hair Care</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/category/fragrance">Fragrance</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/category/tools">Beauty Tools</a></li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div class="col-lg-4 col-md-6">
                        <h5>Contact Us</h5>
                        <ul class="contact-info">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span>123 Beauty Street, Fashion District<br>New York, NY 10001</span>
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <span>+1 (555) 123-4567</span>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <span>contact@cosmeticsfashion.com</span>
                            </li>
                            <li>
                                <i class="fas fa-clock"></i>
                                <span>Mon - Fri: 9:00 AM - 8:00 PM<br>Sat - Sun: 10:00 AM - 6:00 PM</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Footer Bottom -->
                <div class="footer-bottom text-center">
                    <p>&copy; <?php echo date('Y'); ?> Cosmetics Fashion. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <!-- Back to Top Button -->
        <a href="#" class="back-to-top" id="backToTop">
            <i class="fas fa-arrow-up"></i>
        </a>

        <!-- Add Back to Top Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var backToTop = document.getElementById('backToTop');

                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 300) {
                        backToTop.classList.add('show');
                    } else {
                        backToTop.classList.remove('show');
                    }
                });

                backToTop.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            });
        </script>

        <!-- Custom JS -->
        <script src="<?php echo JS_PATH; ?>/main.js"></script>
        </body>

        </html>