<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once 'db.php';

// Initialize database helper
$dbHelper = new DatabaseHelper();
$db = Database::getInstance();

// Get site settings
$settings = [];
$settingsResult = $db->fetchAll("SELECT setting_key, setting_value FROM settings");
foreach ($settingsResult as $setting) {
    $settings[$setting['setting_key']] = $setting['setting_value'];
}

// Get categories for navigation
$categories = $db->fetchAll("SELECT * FROM categories WHERE parent_id IS NULL AND is_active = 1 ORDER BY sort_order, name");

// Get cart count if user is logged in or has session cart
$cartCount = 0;
if (isset($_SESSION['user_id'])) {
    $cartResult = $db->fetchRow("
        SELECT SUM(ci.quantity) as total_items 
        FROM cart c 
        JOIN cart_items ci ON c.cart_id = ci.cart_id 
        WHERE c.user_id = :user_id", 
        ['user_id' => $_SESSION['user_id']]
    );
    $cartCount = $cartResult['total_items'] ?? 0;
} elseif (isset($_SESSION['cart_session_id'])) {
    $cartResult = $db->fetchRow("
        SELECT SUM(ci.quantity) as total_items 
        FROM cart c 
        JOIN cart_items ci ON c.cart_id = ci.cart_id 
        WHERE c.session_id = :session_id", 
        ['session_id' => $_SESSION['cart_session_id']]
    );
    $cartCount = $cartResult['total_items'] ?? 0;
}

// Get wishlist count for logged in users
$wishlistCount = 0;
if (isset($_SESSION['user_id'])) {
    $wishlistResult = $db->fetchRow("
        SELECT COUNT(*) as total_items 
        FROM wishlists 
        WHERE user_id = :user_id", 
        ['user_id' => $_SESSION['user_id']]
    );
    $wishlistCount = $wishlistResult['total_items'] ?? 0;
}

// Set page title if not already set
if (!isset($pageTitle)) {
    $pageTitle = $settings['site_name'] ?? 'Jutta Sansaar';
}

// Set meta description if not already set
if (!isset($metaDescription)) {
    $metaDescription = $settings['site_description'] ?? 'Premium shoe collection for every occasion';
}

// Current page for navigation highlighting
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta name="keywords" content="shoes, footwear, sneakers, boots, sandals, formal shoes, athletic shoes">
    <meta name="author" content="Jutta Sansaar">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:site_name" content="Jutta Sansaar">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <link rel="apple-touch-icon" href="/assets/images/apple-touch-icon.png">
    
    <!-- CSS Files -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    
    <!-- Custom CSS for header -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #e74c3c;
            --accent-color: #f39c12;
            --text-color: #333;
            --light-bg: #f8f9fa;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
        }
        
        .top-bar {
            background-color: var(--primary-color);
            color: white;
            font-size: 0.9rem;
            padding: 8px 0;
        }
        
        .main-header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .logo {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .logo:hover {
            color: var(--secondary-color);
            text-decoration: none;
        }
        
        .search-container {
            position: relative;
            max-width: 500px;
        }
        
        .search-input {
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 12px 50px 12px 20px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25);
        }
        
        .search-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--secondary-color);
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-btn:hover {
            background: #c0392b;
            transform: translateY(-50%) scale(1.05);
        }
        
        .header-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .header-icon {
            position: relative;
            color: var(--text-color);
            font-size: 1.3rem;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .header-icon:hover {
            color: var(--secondary-color);
        }
        
        .badge-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--secondary-color);
            color: white;
            border-radius: 50%;
            font-size: 0.7rem;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .main-nav {
            background-color: var(--light-bg);
            padding: 12px 0;
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            justify-content: center;
            gap: 40px;
        }
        
        .nav-item {
            position: relative;
        }
        
        .nav-link {
            color: var(--text-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 8px 16px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: var(--secondary-color);
            background-color: white;
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .nav-item:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-item {
            display: block;
            padding: 10px 16px;
            color: var(--text-color);
            text-decoration: none;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }
        
        .dropdown-item:hover {
            background-color: var(--light-bg);
            color: var(--secondary-color);
        }
        
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-color);
            cursor: pointer;
        }
        
        .user-menu {
            position: relative;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-color);
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--secondary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
        }
        
        @media (max-width: 768px) {
            .top-bar {
                text-align: center;
            }
            
            .main-header .container {
                padding: 0 15px;
            }
            
            .search-container {
                max-width: 100%;
                margin: 15px 0;
            }
            
            .header-icons {
                gap: 15px;
            }
            
            .mobile-menu-toggle {
                display: block;
            }
            
            .nav-menu {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                border-top: 1px solid #e9ecef;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                padding: 20px;
                gap: 0;
            }
            
            .nav-menu.show {
                display: flex;
            }
            
            .nav-item {
                width: 100%;
                border-bottom: 1px solid #e9ecef;
            }
            
            .nav-link {
                display: block;
                padding: 15px 0;
                border-radius: 0;
            }
            
            .dropdown-menu {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                box-shadow: none;
                border: none;
                background: var(--light-bg);
                margin-top: 10px;
            }
        }
        
        .announcement-bar {
            background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
            color: white;
            text-align: center;
            padding: 8px 0;
            font-size: 0.9rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <!-- Announcement Bar -->
    <div class="announcement-bar">
        <div class="container">
            <i class="fas fa-shipping-fast me-2"></i>
            Free shipping on orders over $<?php echo number_format($settings['free_shipping_threshold'] ?? 75, 0); ?>!
        </div>
    </div>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span><i class="fas fa-phone me-2"></i>+1 (555) 123-4567</span>
                    <span class="ms-3"><i class="fas fa-envelope me-2"></i>support@juttasansaar.com</span>
                </div>
                <div class="col-md-6 text-md-end">
                    <span><i class="fas fa-truck me-2"></i>Track Your Order</span>
                    <span class="ms-3"><i class="fas fa-gift me-2"></i>Gift Cards Available</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-lg-3 col-md-4 col-6">
                    <a href="/" class="logo">
                        <i class="fas fa-shoe-prints me-2"></i>Jutta Sansaar
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="col-lg-5 col-md-8 col-12 order-md-2 order-lg-2">
                    <div class="search-container">
                        <form action="/search.php" method="GET" class="d-flex">
                            <input type="text" 
                                   name="q" 
                                   class="form-control search-input" 
                                   placeholder="Search for shoes, brands, or styles..." 
                                   value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>"
                                   autocomplete="off">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Header Icons -->
                <div class="col-lg-4 col-md-12 col-6 order-md-3 order-lg-3">
                    <div class="header-icons justify-content-end">
                        <!-- User Account -->
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="user-menu">
                                <a href="/account/" class="user-info">
                                    <div class="user-avatar">
                                        <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                                    </div>
                                    <span class="d-none d-md-inline">
                                        <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Account'); ?>
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="/account/" class="dropdown-item">
                                        <i class="fas fa-user me-2"></i>My Account
                                    </a>
                                    <a href="/account/orders.php" class="dropdown-item">
                                        <i class="fas fa-box me-2"></i>My Orders
                                    </a>
                                    <a href="/account/addresses.php" class="dropdown-item">
                                        <i class="fas fa-map-marker-alt me-2"></i>Addresses
                                    </a>
                                    <a href="/account/settings.php" class="dropdown-item">
                                        <i class="fas fa-cog me-2"></i>Settings
                                    </a>
                                    <hr class="dropdown-divider">
                                    <a href="/logout.php" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="/login.php" class="header-icon" title="Login">
                                <i class="fas fa-user"></i>
                            </a>
                        <?php endif; ?>

                        <!-- Wishlist -->
                        <a href="/wishlist.php" class="header-icon" title="Wishlist">
                            <i class="fas fa-heart"></i>
                            <?php if ($wishlistCount > 0): ?>
                                <span class="badge-count"><?php echo $wishlistCount; ?></span>
                            <?php endif; ?>
                        </a>

                        <!-- Shopping Cart -->
                        <a href="/cart.php" class="header-icon" title="Shopping Cart">
                            <i class="fas fa-shopping-cart"></i>
                            <?php if ($cartCount > 0): ?>
                                <span class="badge-count"><?php echo $cartCount; ?></span>
                            <?php endif; ?>
                        </a>

                        <!-- Mobile Menu Toggle -->
                        <button class="mobile-menu-toggle d-lg-none" onclick="toggleMobileMenu()">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Navigation -->
    <nav class="main-nav">
        <div class="container">
            <div class="position-relative">
                <ul class="nav-menu" id="mainMenu">
                    <li class="nav-item">
                        <a href="/" class="nav-link <?php echo $currentPage === 'index' ? 'active' : ''; ?>">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    
                    <!-- Dynamic Categories -->
                    <?php foreach ($categories as $category): ?>
                        <?php
                        // Get subcategories
                        $subcategories = $db->fetchAll(
                            "SELECT * FROM categories WHERE parent_id = :parent_id AND is_active = 1 ORDER BY sort_order, name",
                            ['parent_id' => $category['category_id']]
                        );
                        ?>
                        <li class="nav-item">
                            <a href="/category/<?php echo htmlspecialchars($category['slug']); ?>" 
                               class="nav-link <?php echo $currentPage === 'category' && isset($_GET['slug']) && $_GET['slug'] === $category['slug'] ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                                <?php if (!empty($subcategories)): ?>
                                    <i class="fas fa-chevron-down ms-1" style="font-size: 0.8rem;"></i>
                                <?php endif; ?>
                            </a>
                            
                            <?php if (!empty($subcategories)): ?>
                                <div class="dropdown-menu">
                                    <a href="/category/<?php echo htmlspecialchars($category['slug']); ?>" class="dropdown-item">
                                        <strong>All <?php echo htmlspecialchars($category['name']); ?></strong>
                                    </a>
                                    <hr class="dropdown-divider">
                                    <?php foreach ($subcategories as $subcategory): ?>
                                        <a href="/category/<?php echo htmlspecialchars($subcategory['slug']); ?>" class="dropdown-item">
                                            <?php echo htmlspecialchars($subcategory['name']); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                    
                    <li class="nav-item">
                        <a href="/brands.php" class="nav-link <?php echo $currentPage === 'brands' ? 'active' : ''; ?>">
                            <i class="fas fa-tags me-1"></i>Brands
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="/sale.php" class="nav-link <?php echo $currentPage === 'sale' ? 'active' : ''; ?>" style="color: var(--secondary-color);">
                            <i class="fas fa-percent me-1"></i>Sale
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="/contact.php" class="nav-link <?php echo $currentPage === 'contact' ? 'active' : ''; ?>">
                            <i class="fas fa-envelope me-1"></i>Contact
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- JavaScript for mobile menu and search suggestions -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mainMenu');
            menu.classList.toggle('show');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mainMenu');
            const toggle = document.querySelector('.mobile-menu-toggle');
            
            if (!menu.contains(event.target) && !toggle.contains(event.target)) {
                menu.classList.remove('show');
            }
        });

        // Search suggestions (basic implementation)
        document.querySelector('.search-input').addEventListener('input', function(e) {
            const query = e.target.value;
            if (query.length > 2) {
                // You can implement AJAX search suggestions here
                console.log('Search query:', query);
            }
        });

        // Update cart count dynamically (for AJAX operations)
        function updateCartCount(count) {
            const cartIcon = document.querySelector('a[href="/cart.php"] .badge-count');
            if (count > 0) {
                if (cartIcon) {
                    cartIcon.textContent = count;
                } else {
                    const badge = document.createElement('span');
                    badge.className = 'badge-count';
                    badge.textContent = count;
                    document.querySelector('a[href="/cart.php"]').appendChild(badge);
                }
            } else {
                if (cartIcon) {
                    cartIcon.remove();
                }
            }
        }
    </script>

    <!-- Main Content Starts Here -->
    <main class="main-content"><?php
// End of header.php - main content will be added by including pages
?>