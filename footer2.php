<?php
// Ensure database connection is available
if (!isset($db)) {
    require_once 'db.php';
    $db = Database::getInstance();
}

// Get popular categories for footer
$popularCategories = $db->fetchAll("
    SELECT c.*, COUNT(p.product_id) as product_count 
    FROM categories c 
    LEFT JOIN products p ON c.category_id = p.category_id AND p.is_active = 1
    WHERE c.parent_id IS NULL AND c.is_active = 1 
    GROUP BY c.category_id 
    ORDER BY product_count DESC, c.name 
    LIMIT 6
");

// Get popular brands for footer
$popularBrands = $db->fetchAll("
    SELECT b.*, COUNT(p.product_id) as product_count 
    FROM brands b 
    LEFT JOIN products p ON b.brand_id = p.brand_id AND p.is_active = 1
    WHERE b.is_active = 1 
    GROUP BY b.brand_id 
    ORDER BY product_count DESC, b.name 
    LIMIT 8
");

// Get site settings if not already loaded
if (!isset($settings)) {
    $settings = [];
    $settingsResult = $db->fetchAll("SELECT setting_key, setting_value FROM settings");
    foreach ($settingsResult as $setting) {
        $settings[$setting['setting_key']] = $setting['setting_value'];
    }
}

// Handle newsletter subscription
$newsletterMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newsletter_email'])) {
    $email = filter_var($_POST['newsletter_email'], FILTER_VALIDATE_EMAIL);
    
    if ($email) {
        // Check if email already exists
        $existingSubscriber = $db->fetchRow(
            "SELECT subscriber_id FROM newsletter_subscribers WHERE email = :email",
            ['email' => $email]
        );
        
        if (!$existingSubscriber) {
            // Add new subscriber
            $db->insert(
                "INSERT INTO newsletter_subscribers (email, status, subscribed_at) VALUES (:email, 'subscribed', NOW())",
                ['email' => $email]
            );
            $newsletterMessage = '<div class="alert alert-success mt-2">Thank you for subscribing to our newsletter!</div>';
        } else {
            // Update existing subscriber status
            $db->update(
                "UPDATE newsletter_subscribers SET status = 'subscribed', subscribed_at = NOW(), unsubscribed_at = NULL WHERE email = :email",
                ['email' => $email]
            );
            $newsletterMessage = '<div class="alert alert-info mt-2">Welcome back! You\'re now subscribed to our newsletter.</div>';
        }
    } else {
        $newsletterMessage = '<div class="alert alert-danger mt-2">Please enter a valid email address.</div>';
    }
}
?>

    </main> <!-- Close main content -->

    <!-- Footer -->
    <footer class="footer">
        <!-- Newsletter Section -->
        <div class="newsletter-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="newsletter-content">
                            <h4><i class="fas fa-envelope-open me-2"></i>Stay In The Loop</h4>
                            <p class="mb-0">Get the latest updates on new arrivals, exclusive deals, and style tips!</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4">
                        <form method="POST" class="newsletter-form" id="newsletterForm">
                            <div class="input-group">
                                <input type="email" 
                                       name="newsletter_email" 
                                       class="form-control newsletter-input" 
                                       placeholder="Enter your email address" 
                                       required>
                                <button type="submit" class="btn newsletter-btn">
                                    <i class="fas fa-paper-plane"></i>
                                    <span class="d-none d-sm-inline ms-1">Subscribe</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php echo $newsletterMessage; ?>
            </div>
        </div>

        <!-- Main Footer Content -->
        <div class="footer-main">
            <div class="container">
                <div class="row">
                    <!-- Company Information -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="footer-section">
                            <h5 class="footer-title">
                                <i class="fas fa-shoe-prints me-2"></i>Jutta Sansaar
                            </h5>
                            <p class="footer-description">
                                Your premium destination for quality footwear. We believe every step should be comfortable, 
                                stylish, and confident. Discover shoes that match your lifestyle and express your personality.
                            </p>
                            
                            <!-- Contact Information -->
                            <div class="contact-info">
                                <div class="contact-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>123 Fashion Street, Style District<br>New York, NY 10001</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <a href="tel:+15551234567">+1 (555) 123-4567</a>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <a href="mailto:support@juttasansaar.com">support@juttasansaar.com</a>
                                </div>
                            </div>

                            <!-- Social Media Links -->
                            <div class="social-links">
                                <h6>Follow Us</h6>
                                <a href="#" class="social-link" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="social-link" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="social-link" title="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="social-link" title="Pinterest">
                                    <i class="fab fa-pinterest"></i>
                                </a>
                                <a href="#" class="social-link" title="YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <div class="footer-section">
                            <h5 class="footer-title">Quick Links</h5>
                            <ul class="footer-links">
                                <li><a href="/">Home</a></li>
                                <li><a href="/about.php">About Us</a></li>
                                <li><a href="/contact.php">Contact</a></li>
                                <li><a href="/size-guide.php">Size Guide</a></li>
                                <li><a href="/shipping-info.php">Shipping Info</a></li>
                                <li><a href="/returns.php">Returns & Exchanges</a></li>
                                <li><a href="/faq.php">FAQ</a></li>
                                <li><a href="/blog/">Blog</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <div class="footer-section">
                            <h5 class="footer-title">Shop Categories</h5>
                            <ul class="footer-links">
                                <?php foreach ($popularCategories as $category): ?>
                                    <li>
                                        <a href="/category/<?php echo htmlspecialchars($category['slug']); ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                            <span class="category-count">(<?php echo $category['product_count']; ?>)</span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                                <li><a href="/sale.php" class="sale-link">Sale Items</a></li>
                                <li><a href="/new-arrivals.php">New Arrivals</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Popular Brands -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <div class="footer-section">
                            <h5 class="footer-title">Popular Brands</h5>
                            <ul class="footer-links">
                                <?php foreach ($popularBrands as $brand): ?>
                                    <li>
                                        <a href="/brand/<?php echo htmlspecialchars($brand['slug']); ?>">
                                            <?php echo htmlspecialchars($brand['name']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                                <li><a href="/brands.php">View All Brands</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Customer Service -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <div class="footer-section">
                            <h5 class="footer-title">Customer Service</h5>
                            <ul class="footer-links">
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <li><a href="/account/">My Account</a></li>
                                    <li><a href="/account/orders.php">Order History</a></li>
                                    <li><a href="/account/addresses.php">Address Book</a></li>
                                    <li><a href="/wishlist.php">My Wishlist</a></li>
                                <?php else: ?>
                                    <li><a href="/login.php">Login</a></li>
                                    <li><a href="/register.php">Create Account</a></li>
                                <?php endif; ?>
                                <li><a href="/track-order.php">Track Your Order</a></li>
                                <li><a href="/gift-cards.php">Gift Cards</a></li>
                                <li><a href="/loyalty-program.php">Loyalty Program</a></li>
                                <li><a href="/student-discount.php">Student Discount</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="footer-features">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="feature-content">
                                <h6>Free Shipping</h6>
                                <p>On orders over $<?php echo number_format($settings['free_shipping_threshold'] ?? 75, 0); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-undo-alt"></i>
                            </div>
                            <div class="feature-content">
                                <h6>Easy Returns</h6>
                                <p>30-day return policy</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div class="feature-content">
                                <h6>Secure Payment</h6>
                                <p>SSL encrypted checkout</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div class="feature-content">
                                <h6>24/7 Support</h6>
                                <p>Customer service available</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="payment-methods">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="payment-title">We Accept</h6>
                        <div class="payment-icons">
                            <i class="fab fa-cc-visa" title="Visa"></i>
                            <i class="fab fa-cc-mastercard" title="Mastercard"></i>
                            <i class="fab fa-cc-amex" title="American Express"></i>
                            <i class="fab fa-cc-discover" title="Discover"></i>
                            <i class="fab fa-cc-paypal" title="PayPal"></i>
                            <i class="fab fa-apple-pay" title="Apple Pay"></i>
                            <i class="fab fa-google-pay" title="Google Pay"></i>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="security-badges">
                            <img src="/assets/images/ssl-badge.png" alt="SSL Secured" class="security-badge">
                            <img src="/assets/images/trustpilot-badge.png" alt="Trustpilot" class="security-badge">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="copyright">
                            &copy; <?php echo date('Y'); ?> Jutta Sansaar. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <ul class="footer-legal">
                            <li><a href="/privacy-policy.php">Privacy Policy</a></li>
                            <li><a href="/terms-of-service.php">Terms of Service</a></li>
                            <li><a href="/cookie-policy.php">Cookie Policy</a></li>
                            <li><a href="/accessibility.php">Accessibility</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top" title="Go to top">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Custom Footer Styles -->
    <style>
        .footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            margin-top: 50px;
        }

        .newsletter-section {
            background: var(--secondary-color);
            padding: 40px 0;
            position: relative;
            overflow: hidden;
        }

        .newsletter-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><pattern id="grain" width="100" height="20" patternUnits="userSpaceOnUse"><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="20" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .newsletter-content h4 {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .newsletter-content p {
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .newsletter-form {
            position: relative;
            z-index: 2;
        }

        .newsletter-input {
            border: none;
            border-radius: 25px 0 0 25px;
            padding: 12px 20px;
            font-size: 0.95rem;
        }

        .newsletter-input:focus {
            box-shadow: none;
            border-color: transparent;
        }

        .newsletter-btn {
            background: var(--primary-color);
            border: none;
            border-radius: 0 25px 25px 0;
            padding: 12px 20px;
            color: white;
            transition: all 0.3s ease;
        }

        .newsletter-btn:hover {
            background: #1a252f;
            transform: translateY(-1px);
        }

        .footer-main {
            padding: 60px 0 40px;
        }

        .footer-title {
            color: white;
            font-weight: 600;
            margin-bottom: 25px;
            font-size: 1.1rem;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 30px;
            height: 2px;
            background: var(--secondary-color);
        }

        .footer-description {
            opacity: 0.9;
            line-height: 1.7;
            margin-bottom: 25px;
            font-size: 0.95rem;
        }

        .contact-info {
            margin-bottom: 25px;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            font-size: 0.9rem;
        }

        .contact-item i {
            color: var(--secondary-color);
            width: 18px;
            margin-right: 12px;
            margin-top: 2px;
        }

        .contact-item a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-item a:hover {
            color: var(--secondary-color);
        }

        .social-links h6 {
            font-size: 0.9rem;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            color: white;
            border-radius: 50%;
            text-decoration: none;
            margin-right: 10px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .social-link:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 8px;
        }

        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .footer-links a:hover {
            color: var(--secondary-color);
            padding-left: 5px;
        }

        .category-count {
            font-size: 0.8rem;
            opacity: 0.7;
            margin-left: auto;
        }

        .sale-link {
            color: var(--accent-color) !important;
            font-weight: 500;
        }

        .footer-features {
            background: rgba(0,0,0,0.2);
            padding: 30px 0;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .feature-item {
            display: flex;
            align-items: center;
            text-align: left;
        }

        .feature-icon {
            flex-shrink: 0;
            width: 50px;
            height: 50px;
            background: var(--secondary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
        }

        .feature-content h6 {
            margin-bottom: 5px;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .feature-content p {
            margin: 0;
            opacity: 0.8;
            font-size: 0.85rem;
        }

        .payment-methods {
            background: rgba(0,0,0,0.2);
            padding: 25px 0;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .payment-title {
            margin-bottom: 15px;
            font-weight: 600;
        }

        .payment-icons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .payment-icons i {
            font-size: 2rem;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .payment-icons i:hover {
            opacity: 1;
        }

        .security-badge {
            height: 40px;
            margin-left: 10px;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .security-badge:hover {
            opacity: 1;
        }

        .footer-bottom {
            background: rgba(0,0,0,0.3);
            padding: 20px 0;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .copyright {
            margin: 0;
            opacity: 0.8;
            font-size: 0.9rem;
        }

        .footer-legal {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: end;
        }

        .footer-legal a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .footer-legal a:hover {
            color: var(--secondary-color);
        }

        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        }

        @media (max-width: 768px) {
            .newsletter-section {
                padding: 30px 0;
                text-align: center;
            }

            .newsletter-content {
                margin-bottom: 20px;
            }

            .footer-main {
                padding: 40px 0 30px;
            }

            .feature-item {
                text-align: center;
                flex-direction: column;
            }

            .feature-icon {
                margin-right: 0;
                margin-bottom: 10px;
            }

            .footer-legal {
                justify-content: center;
                margin-top: 15px;
            }

            .payment-icons {
                justify-content: center;
            }

            .back-to-top {
                bottom: 20px;
                right: 20px;
                width: 45px;
                height: 45px;
            }
        }

        @media (max-width: 576px) {
            .footer-legal {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }

            .payment-icons i {
                font-size: 1.8rem;
            }
        }
    </style>

    <!-- Footer JavaScript -->
    <script>
        // Back to top functionality
        window.addEventListener('scroll', function() {
            const backToTop = document.getElementById('backToTop');
            if (window.pageYOffset > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });

        document.getElementById('backToTop').addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Newsletter form enhancement
        document.getElementById('newsletterForm').addEventListener('submit', function(e) {
            const email = this.querySelector('input[name="newsletter_email"]').value;
            if (!email || !email.includes('@')) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('.newsletter-btn');
            const originalContent = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            submitBtn.disabled = true;
            
            // Reset after form submission (if using AJAX, handle accordingly)
            setTimeout(() => {
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
            }, 2000);
        });

        // Smooth scroll for footer links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="/assets/js/main.js"></script>
</body>
</html><?php
// End of footer.php
?>