<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jutta Sansaar - Premium Footwear Collection</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #fafafa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(45deg, #fff, #ecf0f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 25px;
        }

        .nav-links a:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }

        .cart-icon {
            position: relative;
            background: rgba(255,255,255,0.2);
            padding: 0.8rem;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .cart-icon:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><rect fill="%23f8f9fa" width="1200" height="600"/><polygon fill="%233498db" points="0,600 400,200 800,400 1200,100 1200,600"/><polygon fill="%232c3e50" points="0,600 300,300 600,500 900,200 1200,400 1200,600"/></svg>');
            background-size: cover;
            background-position: center;
            height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            animation: fadeInUp 1s ease;
        }

        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            animation: fadeInUp 1s ease 0.2s both;
        }

        .cta-btn {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            animation: fadeInUp 1s ease 0.4s both;
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
        }

        .cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(231, 76, 60, 0.4);
        }

        /* Filter Section */
        .filters {
            background: white;
            padding: 2rem 0;
            border-bottom: 1px solid #eee;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .filter-container {
            display: flex;
            gap: 2rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-group label {
            font-weight: 600;
            color: #2c3e50;
        }

        .filter-group select, .filter-group input {
            padding: 0.5rem 1rem;
            border: 2px solid #ecf0f1;
            border-radius: 25px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .filter-group select:focus, .filter-group input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        /* Products Grid */
        .products {
            padding: 4rem 0;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            color: #2c3e50;
            font-weight: 700;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }

        .product-image {
            width: 100%;
            height: 250px;
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: #6c757d;
            position: relative;
            overflow: hidden;
        }

        .product-image::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: rotate(45deg);
            transition: all 0.6s ease;
            opacity: 0;
        }

        .product-card:hover .product-image::before {
            opacity: 1;
            animation: shine 0.6s ease;
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .product-category {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #e74c3c;
            margin-bottom: 1rem;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .stars {
            color: #f39c12;
        }

        .add-to-cart {
            width: 100%;
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 0.8rem;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-to-cart:hover {
            background: linear-gradient(45deg, #2980b9, #21618c);
            transform: translateY(-2px);
        }

        /* Cart Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            backdrop-filter: blur(5px);
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 20px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #7f8c8d;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #ecf0f1;
        }

        .cart-total {
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            margin: 1rem 0;
            color: #2c3e50;
        }

        .checkout-btn {
            width: 100%;
            background: linear-gradient(45deg, #27ae60, #229954);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkout-btn:hover {
            background: linear-gradient(45deg, #229954, #1e8449);
        }

        /* Footer */
        footer {
            background: #2c3e50;
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 4rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            color: #3498db;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section ul li a {
            color: #ecf0f1;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-section ul li a:hover {
            color: #3498db;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #34495e;
            color: #bdc3c7;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .filter-container {
                flex-direction: column;
                align-items: stretch;
            }
            
            .products-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="container">
            <div class="logo">Jutta Sansaar</div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#products">Products</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <div class="cart-icon" onclick="toggleCart()">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count" id="cartCount">0</span>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Step Into Elegance</h1>
            <p>Discover our premium collection of handcrafted footwear designed for the modern lifestyle</p>
            <a href="#products" class="cta-btn">Shop Now</a>
        </div>
    </section>

    <!-- Filters -->
    <section class="filters">
        <div class="container">
            <div class="filter-container">
                <div class="filter-group">
                    <label for="category">Category:</label>
                    <select id="category" onchange="filterProducts()">
                        <option value="all">All Categories</option>
                        <option value="sneakers">Sneakers</option>
                        <option value="boots">Boots</option>
                        <option value="formal">Formal</option>
                        <option value="casual">Casual</option>
                        <option value="heels">Heels</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="priceRange">Max Price:</label>
                    <input type="range" id="priceRange" min="0" max="500" value="500" onchange="filterProducts()">
                    <span id="priceValue">$500</span>
                </div>
                <div class="filter-group">
                    <label for="size">Size:</label>
                    <select id="size" onchange="filterProducts()">
                        <option value="all">All Sizes</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="search">Search:</label>
                    <input type="text" id="search" placeholder="Search products..." onkeyup="filterProducts()">
                </div>
            </div>
        </div>
    </section>

    <!-- Products -->
    <section class="products" id="products">
        <div class="container">
            <h2 class="section-title">Featured Collection</h2>
            <div class="products-grid" id="productsGrid">
                <!-- Products will be dynamically generated -->
            </div>
        </div>
    </section>

    <!-- Cart Modal -->
    <div id="cartModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="toggleCart()">&times;</span>
            <h2>Shopping Cart</h2>
            <div id="cartItems"></div>
            <div class="cart-total" id="cartTotal">Total: $0.00</div>
            <button class="checkout-btn" onclick="checkout()">Proceed to Checkout</button>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About Jutta Sansaar</h3>
                    <p>We craft premium footwear that combines traditional craftsmanship with modern design, ensuring every step you take is comfortable and stylish.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#products">Products</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="#">Shipping Info</a></li>
                        <li><a href="#">Returns & Exchanges</a></li>
                        <li><a href="#">Size Guide</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Connect With Us</h3>
                    <ul>
                        <li><a href="#"><i class="fab fa-facebook"></i> Facebook</a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i> Instagram</a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i> Twitter</a></li>
                        <li><a href="#"><i class="fab fa-youtube"></i> YouTube</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Jutta Sansaar. All rights reserved. | Privacy Policy | Terms of Service</p>
            </div>
        </div>
    </footer>

    <script>
        // Product Data
        const products = [
            {
                id: 1,
                name: "Classic Leather Oxford",
                category: "formal",
                price: 189.99,
                size: ["7", "8", "9", "10", "11"],
                rating: 4.8,
                icon: "👞"
            },
            {
                id: 2,
                name: "Urban Runner Pro",
                category: "sneakers",
                price: 129.99,
                size: ["6", "7", "8", "9", "10", "11", "12"],
                rating: 4.6,
                icon: "👟"
            },
            {
                id: 3,
                name: "Elegant Suede Pumps",
                category: "heels",
                price: 159.99,
                size: ["6", "7", "8", "9", "10"],
                rating: 4.7,
                icon: "👠"
            },
            {
                id: 4,
                name: "Adventure Hiking Boots",
                category: "boots",
                price: 199.99,
                size: ["7", "8", "9", "10", "11", "12"],
                rating: 4.9,
                icon: "🥾"
            },
            {
                id: 5,
                name: "Casual Canvas Slip-ons",
                category: "casual",
                price: 79.99,
                size: ["6", "7", "8", "9", "10", "11"],
                rating: 4.4,
                icon: "👟"
            },
            {
                id: 6,
                name: "Executive Loafers",
                category: "formal",
                price: 169.99,
                size: ["7", "8", "9", "10", "11"],
                rating: 4.6,
                icon: "👞"
            },
            {
                id: 7,
                name: "Athletic Performance Runners",
                category: "sneakers",
                price: 149.99,
                size: ["6", "7", "8", "9", "10", "11", "12"],
                rating: 4.8,
                icon: "👟"
            },
            {
                id: 8,
                name: "Vintage Ankle Boots",
                category: "boots",
                price: 139.99,
                size: ["6", "7", "8", "9", "10"],
                rating: 4.5,
                icon: "👢"
            },
            {
                id: 9,
                name: "Strappy Sandal Heels",
                category: "heels",
                price: 119.99,
                size: ["6", "7", "8", "9", "10"],
                rating: 4.3,
                icon: "👡"
            },
            {
                id: 10,
                name: "Comfort Walking Shoes",
                category: "casual",
                price: 89.99,
                size: ["6", "7", "8", "9", "10", "11"],
                rating: 4.7,
                icon: "👟"
            },
            {
                id: 11,
                name: "Steel Toe Work Boots",
                category: "boots",
                price: 179.99,
                size: ["7", "8", "9", "10", "11", "12"],
                rating: 4.6,
                icon: "🥾"
            },
            {
                id: 12,
                name: "Designer Platform Heels",
                category: "heels",
                price: 229.99,
                size: ["6", "7", "8", "9", "10"],
                rating: 4.8,
                icon: "👠"
            }
        ];

        let cart = [];
        let filteredProducts = [...products];

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            displayProducts(filteredProducts);
            updatePriceDisplay();
        });

        // Display products
        function displayProducts(productsToShow) {
            const grid = document.getElementById('productsGrid');
            grid.innerHTML = '';

            productsToShow.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                productCard.innerHTML = `
                    <div class="product-image">
                        ${product.icon}
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">${product.name}</h3>
                        <p class="product-category">${product.category.charAt(0).toUpperCase() + product.category.slice(1)}</p>
                        <div class="product-price">$${product.price.toFixed(2)}</div>
                        <div class="product-rating">
                            <div class="stars">
                                ${'★'.repeat(Math.floor(product.rating))}${'☆'.repeat(5 - Math.floor(product.rating))}
                            </div>
                            <span>(${product.rating})</span>
                        </div>
                        <button class="add-to-cart" onclick="addToCart(${product.id})">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </div>
                `;
                grid.appendChild(productCard);
            });
        }

        // Filter products
        function filterProducts() {
            const category = document.getElementById('category').value;
            const maxPrice = parseInt(document.getElementById('priceRange').value);
            const size = document.getElementById('size').value;
            const searchTerm = document.getElementById('search').value.toLowerCase();

            filteredProducts = products.filter(product => {
                const matchesCategory = category === 'all' || product.category === category;
                const matchesPrice = product.price <= maxPrice;
                const matchesSize = size === 'all' || product.size.includes(size);
                const matchesSearch = product.name.toLowerCase().includes(searchTerm) || 
                                    product.category.toLowerCase().includes(searchTerm);

                return matchesCategory && matchesPrice && matchesSize && matchesSearch;
            });

            displayProducts(filteredProducts);
        }

        // Update price display
        function updatePriceDisplay() {
            const priceRange = document.getElementById('priceRange');
            const priceValue = document.getElementById('priceValue');
            
            priceRange.addEventListener('input', function() {
                priceValue.textContent = `$${this.value}`;
            });
        }

        // Add to cart
        function addToCart(productId) {
            const product = products.find(p => p.id === productId);
            const existingItem = cart.find(item => item.id === productId);

            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({...product, quantity: 1});
            }

            updateCartDisplay();
            
            // Show success animation
            const button = event.target;
            button.style.background = 'linear-gradient(45deg, #27ae60, #229954)';
            button.innerHTML = '<i class="fas fa-check"></i> Added!';
            
            setTimeout(() => {
                button.style.background = 'linear-gradient(45deg, #3498db, #2980b9)';
                button.innerHTML = '<i class="fas fa-cart-plus"></i> Add to Cart';
            }, 1000);
        }

        // Update cart display
        function updateCartDisplay() {
            const cartCount = document.getElementById('cartCount');
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartCount.textContent = totalItems;

            const cartItems = document.getElementById('cartItems');
            const cartTotal = document.getElementById('cartTotal');

            cartItems.innerHTML = '';
            let total = 0;

            cart.forEach(item => {
                total += item.price * item.quantity;
                const cartItem = document.createElement('div');
                cartItem.className = 'cart-item';
                cartItem.innerHTML = `
                    <div>
                        <strong>${item.name}</strong><br>
                        $${item.price.toFixed(2)} x ${item.quantity}
                    </div>
                    <div>
                        <button onclick="removeFromCart(${item.id})" style="background: #e74c3c; color: white; border: none; padding: 0.5rem; border-radius: 5px; cursor: pointer;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                cartItems.appendChild(cartItem);
            });

            cartTotal.textContent = `Total: $${total.toFixed(2)}`;
        }

        // Remove from cart
        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCartDisplay();
        }

        // Toggle cart modal
        function toggleCart() {
            const modal = document.getElementById('cartModal');
            modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
        }

        // Checkout
        function checkout() {
            if (cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }

            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            alert(`Thank you for your purchase! Total: $${total.toFixed(2)}\n\nYour order will be processed shortly.`);
            
            cart = [];
            updateCartDisplay();
            toggleCart();
        }

        // Smooth scrolling for navigation links
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

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('cartModal');
            if (event.target === modal) {
                toggleCart();
            }
        });
    </script>
</body>
</html>