<?php
// Initialize session
session_start();

// Define products
 $products = [
    1 => [
        'name' => 'Premium Headphones',
        'price' => 79.99,
        'hidden'=> false,
        'description' => 'High-quality wireless headphones with noise cancellation.',
        'image' => 'https://picsum.photos/seed/headphones/400/300.jpg'
    ],
    2 => [
        'name' => 'Smart Watch',
        'price' => 199.99,
        'hidden'=> false,
        'description' => 'Track your fitness and stay connected with our latest smartwatch.',
        'image' => 'https://picsum.photos/seed/smartwatch/400/300.jpg'
    ],
    3 => [
        'name' => 'Laptop Stand',
        'price' => 39.99,
        'hidden'=> false,
        'description' => 'Ergonomic aluminum laptop stand for better posture.',
        'image' => 'https://picsum.photos/seed/laptopstand/400/300.jpg'
    ],
    4 => [
        'name' => 'Wireless Mouse',
        'price' => 29.99,
        'hidden'=> false,
        'description' => 'Precision wireless mouse with long battery life.',
        'image' => 'https://picsum.photos/seed/mouse/400/300.jpg'
    ],
    5 => [
        'name' => 'USB-C Hub',
        'price' => 49.99,
        'hidden'=> false,
        'description' => 'Multi-port USB-C hub with HDMI, USB 3.0, and SD card reader.',
        'image' => 'https://picsum.photos/seed/usbhub/400/300.jpg'
    ],
    6 => [
        'name' => 'Mechanical Keyboard',
        'price' => 89.99,
        'hidden'=> false,
        'description' => 'RGB mechanical keyboard with customizable keys.',
        'image' => 'https://picsum.photos/seed/keyboard/400/300.jpg'
    ],
    7 => [
        'name' => 'Webcam HD',
        'price' => 59.99,
        'hidden'=> false,
        'description' => '1080p HD webcam with auto-focus and noise reduction.',
        'image' => 'https://picsum.photos/seed/webcam/400/300.jpg'
    ],
    8 => [
        'name' => 'Phone Stand',
        'price' => 15.99,
        'hidden'=> false,
        'description' => 'Adjustable phone stand for desk and table use.',
        'image' => 'https://picsum.photos/seed/phonestand/400/300.jpg'
    ],
    9 => [
        'name' => 'Cable Organizer',
        'price' => 12.99,
        'hidden'=> false,
        'description' => 'Keep your cables tidy with our magnetic organizer.',
        'image' => 'https://picsum.photos/seed/cableorg/400/300.jpg'
    ],
    10 => [
        'name' => 'Portable Charger',
        'price' => 34.99,
        'hidden'=> false,
        'description' => '10000mAh portable charger with fast charging.',
        'image' => 'https://picsum.photos/seed/charger/400/300.jpg'
    ],
    11 => [
        'name' => 'Bluetooth Speaker',
        'price' => 45.99,
        'hidden'=> false,
        'description' => 'Waterproof Bluetooth speaker with 12-hour battery life.',
        'image' => 'https://picsum.photos/seed/speaker/400/300.jpg'
    ],
    12 => [
        'name' => 'Tablet Case',
        'price' => 24.99,
        'hidden'=> false,
        'description' => 'Protective tablet case with built-in stand.',
        'image' => 'https://picsum.photos/seed/tabletcase/400/300.jpg'
    ],
    // Hidden product with flag - not in the normal range
    209 => [
        'name' => 'Secret Product',
        'price' => 999.99,
        'hidden'=> true,
        'description' => 'This product contains a secret: ' . getenv("FLAG"),
        'image' => 'https://picsum.photos/seed/secret/400/300.jpg'
    ]
];

// Function to get product by MD5 hash
function getProductByHash($hash) {
    global $products;
    foreach ($products as $id => $product) {
        if (md5($id) === $hash) {
            return ['id' => $id, 'data' => $product];
        }
    }
    return null;
}

// Handle API requests
if (isset($_GET['api'])) {
    header('Content-Type: application/json');
    
    if ($_GET['api'] === 'products') {
        // Return all visible products (excluding the hidden one)
        $visibleProducts = [];
        foreach ($products as $id => $product) {
            if (!$product['hidden']) { // Skip the hidden product
                $visibleProducts[md5($id)] = array_merge(['id' => $id], $product);
            }
        }
        echo json_encode($visibleProducts);
    } elseif ($_GET['api'] === 'product' && isset($_GET['id'])) {
        $product = getProductByHash($_GET['id']);
        if ($product) {
            echo json_encode($product);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
        }
    }
    exit;
}

// Handle page routing
 $page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechGear - Your Tech Store</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f8f8;
            color: #333;
        }
        
        header {
            background-color: #ff7b00;
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 1.5rem;
        }
        
        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s;
        }
        
        nav ul li a:hover {
            opacity: 0.8;
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .page {
            display: none;
        }
        
        .page.active {
            display: block;
        }
        
        .promo-banner {
            background: linear-gradient(135deg, #ff7b00, #ff9500);
            color: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .promo-banner h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .promo-banner p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        
        .promo-products {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }
        
        .promo-product {
            background: rgba(255,255,255,0.2);
            padding: 1rem;
            border-radius: 8px;
            width: 200px;
            text-align: center;
        }
        
        .promo-product img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 0.5rem;
        }
        
        .promo-product h3 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .promo-product .price {
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .product-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .product-image {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }
        
        .product-info {
            padding: 1.5rem;
        }
        
        .product-id {
            font-size: 0.8rem;
            color: #888;
            margin-bottom: 0.5rem;
            font-family: monospace;
        }
        
        .product-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .product-price {
            color: #ff7b00;
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        
        .product-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        .btn {
            background-color: #ff7b00;
            color: white;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
            margin-top: 1rem;
        }
        
        .btn:hover {
            background-color: #e66a00;
        }
        
        .product-detail {
            display: flex;
            gap: 2rem;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .product-detail-image {
            flex: 1;
            max-width: 500px;
        }
        
        .product-detail-image img {
            width: 100%;
            border-radius: 8px;
        }
        
        .product-detail-info {
            flex: 1;
        }
        
        .product-detail-id {
            font-size: 0.9rem;
            color: #888;
            margin-bottom: 0.5rem;
            font-family: monospace;
        }
        
        .product-detail-name {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .product-detail-price {
            font-size: 1.5rem;
            color: #ff7b00;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        
        .product-detail-description {
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .admin-panel {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .admin-panel h2 {
            margin-bottom: 1.5rem;
            color: #ff7b00;
        }
        
        .admin-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-width: 500px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-group input, .form-group textarea {
            padding: 0.7rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ff7b00;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 4px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            display: none;
            z-index: 1000;
        }
        
        .notification.show {
            display: block;
            animation: fadeIn 0.3s, fadeOut 0.3s 2.7s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-20px); }
        }
        
        .hint {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        .credentials {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            font-family: monospace;
        }
        
        .search-container {
            margin-bottom: 2rem;
            position: relative;
        }
        
        .search-input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 4px 4px;
            max-height: 300px;
            overflow-y: auto;
            display: none;
            z-index: 100;
        }
        
        .search-result-item {
            padding: 0.8rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }
        
        .search-result-item:hover {
            background-color: #f8f8f8;
        }
        
        .search-result-item:last-child {
            border-bottom: none;
        }
        
        footer {
            background-color: #333;
            color: white;
            padding: 2rem;
            margin-top: 3rem;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
        }
        
        .footer-section h3 {
            margin-bottom: 1rem;
            color: #ff7b00;
        }
        
        .footer-section ul {
            list-style: none;
        }
        
        .footer-section ul li {
            margin-bottom: 0.5rem;
        }
        
        .footer-section ul li a {
            color: #ccc;
            text-decoration: none;
        }
        
        .footer-section ul li a:hover {
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">TechGear</div>
            <nav>
                <ul>
                    <li><a href="#" data-page="home">Home</a></li>
                    <li><a href="#" data-page="products">Products</a></li>
                    <li><a href="#" data-page="about">About</a></li>
                    <li><a href="#" data-page="contact">Contact</a></li>
                    <li><a href="#" data-page="admin">Admin</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="container">
        <!-- Home Page -->
        <div id="home" class="page active">
            <h1>Welcome to TechGear</h1>
            <p>Your one-stop shop for the latest tech gadgets and accessories.</p>
            
            <!-- Promo Banner -->
            <div class="promo-banner">
                <h2>🔥 Special Weekend Sale! 🔥</h2>
                <p>Get up to 30% off on selected items. Limited time offer!</p>
                <div class="promo-products">
                    <div class="promo-product">
                        <img src="https://picsum.photos/seed/headphones/200/120.jpg" alt="Premium Headphones">
                        <h3>Premium Headphones</h3>
                        <div class="price">$79.99</div>
                    </div>
                    <div class="promo-product">
                        <img src="https://picsum.photos/seed/smartwatch/200/120.jpg" alt="Smart Watch">
                        <h3>Smart Watch</h3>
                        <div class="price">$199.99</div>
                    </div>
                    <div class="promo-product">
                        <img src="https://picsum.photos/seed/keyboard/200/120.jpg" alt="Mechanical Keyboard">
                        <h3>Mechanical Keyboard</h3>
                        <div class="price">$89.99</div>
                    </div>
                </div>
            </div>
            
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search for products...">
                <div class="search-results"></div>
            </div>
            
            <h2>Featured Products</h2>
            <div class="product-grid" id="featured-products">
                <!-- Products will be loaded here -->
            </div>
        </div>
        
        <!-- Products Page -->
        <div id="products" class="page">
            <h1>Our Products</h1>
            <div class="product-grid" id="all-products">
                <!-- Products will be loaded here -->
            </div>
        </div>
        
        <!-- Product Detail Page -->
        <div id="product" class="page">
            <div id="product-detail-container">
                <!-- Product details will be loaded here -->
            </div>
        </div>
        
        <!-- About Page -->
        <div id="about" class="page">
            <h1>About TechGear</h1>
            <p>TechGear was founded in 2020 with a simple mission: to provide high-quality tech products at affordable prices. We carefully curate our selection to ensure that every product we sell meets our high standards for quality and performance.</p>
            <p>Our team is passionate about technology and we're always on the lookout for the latest innovations to bring to our customers. Whether you're a tech enthusiast or just looking for a reliable gadget, we've got you covered.</p>
        </div>
        
        <!-- Contact Page -->
        <div id="contact" class="page">
            <h1>Contact Us</h1>
            <p>Have a question or feedback? We'd love to hear from you!</p>
            
            <div class="admin-form" style="margin-top: 2rem;">
                <div class="form-group">
                    <label for="contact-name">Name</label>
                    <input type="text" id="contact-name">
                </div>
                <div class="form-group">
                    <label for="contact-email">Email</label>
                    <input type="email" id="contact-email">
                </div>
                <div class="form-group">
                    <label for="contact-message">Message</label>
                    <textarea id="contact-message" rows="5"></textarea>
                </div>
                <button class="btn" id="send-contact">Send Message</button>
            </div>
        </div>
        
        <!-- Admin Page -->
        <div id="admin" class="page">
            <div class="admin-panel">
                <h2>Admin Panel</h2>
                
                <div class="credentials">
                    <strong>Default Credentials:</strong><br>
                    Username: admin<br>
                    Password: techgear123
                </div>
                
                <div class="hint">
                    <strong>Hint:</strong> Some products might be hidden. Try to find them by exploring the API endpoints. Product IDs are now visible in the product cards!
                </div>
                
                <div class="admin-form">
                    <div class="form-group">
                        <label for="admin-username">Username</label>
                        <input type="text" id="admin-username">
                    </div>
                    <div class="form-group">
                        <label for="admin-password">Password</label>
                        <input type="password" id="admin-password">
                    </div>
                    <button class="btn" id="admin-login">Login</button>
                </div>
                
                <div id="admin-content" style="display: none; margin-top: 2rem;">
                    <h3>Product Management</h3>
                    <div class="form-group">
                        <label for="product-name">Product Name</label>
                        <input type="text" id="product-name">
                    </div>
                    <div class="form-group">
                        <label for="product-price">Price</label>
                        <input type="text" id="product-price">
                    </div>
                    <div class="form-group">
                        <label for="product-desc">Description</label>
                        <textarea id="product-desc" rows="3"></textarea>
                    </div>
                    <button class="btn" id="add-product">Add Product</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="notification" id="notification"></div>
    
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>TechGear</h3>
                <p>Your trusted source for quality tech products.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#" data-page="home">Home</a></li>
                    <li><a href="#" data-page="products">Products</a></li>
                    <li><a href="#" data-page="about">About</a></li>
                    <li><a href="#" data-page="contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Customer Service</h3>
                <ul>
                    <li><a href="#">Shipping Info</a></li>
                    <li><a href="#">Returns</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Connect</h3>
                <ul>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">LinkedIn</a></li>
                </ul>
            </div>
        </div>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load products from API
            function loadProducts() {
                $.get('?api=products', function(data) {
                    // Load featured products
                    const featuredContainer = $('#featured-products');
                    featuredContainer.empty();
                    
                    let count = 0;
                    for (const hash in data) {
                        if (count >= 3) break; // Only show 3 featured products
                        
                        const product = data[hash];
                        const productCard = createProductCard(hash, product);
                        featuredContainer.append(productCard);
                        count++;
                    }
                    
                    // Load all products
                    const allProductsContainer = $('#all-products');
                    allProductsContainer.empty();
                    
                    for (const hash in data) {
                        const product = data[hash];
                        const productCard = createProductCard(hash, product);
                        allProductsContainer.append(productCard);
                    }
                });
            }
            
            // Create product card element
            function createProductCard(hash, product) {
                const card = $(`
                    <div class="product-card" data-hash="${hash}">
                        <img src="${product.image}" alt="${product.name}" class="product-image">
                        <div class="product-info">
                            <div class="product-name">${product.name}</div>
                            <div class="product-price">$${product.price}</div>
                            <div class="product-description">${product.description}</div>
                            <button class="btn view-product">View Details</button>
                        </div>
                    </div>
                `);
                
                card.find('.view-product').click(function() {
                    viewProduct(hash);
                });
                
                return card;
            }
            
            // View product details
            function viewProduct(hash) {
                $.get('?api=product&id=' + hash, function(data) {
                    if (data.error) {
                        showNotification(data.error, 'error');
                        return;
                    }
                    
                    const product = data.data;
                    const detailHtml = `
                        <div class="product-detail">
                            <div class="product-detail-image">
                                <img src="${product.image}" alt="${product.name}">
                            </div>
                            <div class="product-detail-info">
                                <div class="product-detail-id">Product ID: ${data.id}</div>
                                <h2 class="product-detail-name">${product.name}</h2>
                                <div class="product-detail-price">$${product.price}</div>
                                <p class="product-detail-description">${product.description}</p>
                                <button class="btn add-to-cart" data-hash="${hash}">Add to Cart</button>
                            </div>
                        </div>
                    `;
                    
                    $('#product-detail-container').html(detailHtml);
                    showPage('product');
                });
            }
            
            // Show notification
            function showNotification(message, type = 'success') {
                const notification = $('#notification');
                notification.text(message);
                notification.addClass('show');
                
                setTimeout(function() {
                    notification.removeClass('show');
                }, 3000);
            }
            
            // Show page
            function showPage(pageId) {
                $('.page').removeClass('active');
                $('#' + pageId).addClass('active');
                
                // Update URL without page reload
                if (history.pushState) {
                    const newUrl = '?page=' + pageId;
                    window.history.pushState({path: newUrl}, '', newUrl);
                }
            }
            
            // Handle navigation
            $('nav a, footer a[data-page]').click(function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                showPage(page);
            });
            
            // Handle search
            $('.search-input').on('input', function() {
                const query = $(this).val().toLowerCase();
                const resultsContainer = $('.search-results');
                
                if (query.length < 2) {
                    resultsContainer.hide();
                    return;
                }
                
                $.get('?api=products', function(data) {
                    resultsContainer.empty();
                    
                    let hasResults = false;
                    for (const hash in data) {
                        const product = data[hash];
                        if (product.name.toLowerCase().includes(query) || 
                            product.description.toLowerCase().includes(query)) {
                            
                            const resultItem = $(`
                                <div class="search-result-item" data-hash="${hash}">
                                    <strong>${product.name}</strong> (ID: ${product.id}) - $${product.price}
                                </div>
                            `);
                            
                            resultItem.click(function() {
                                viewProduct(hash);
                                resultsContainer.hide();
                                $('.search-input').val('');
                            });
                            
                            resultsContainer.append(resultItem);
                            hasResults = true;
                        }
                    }
                    
                    if (hasResults) {
                        resultsContainer.show();
                    } else {
                        resultsContainer.html('<div class="search-result-item">No results found</div>');
                        resultsContainer.show();
                    }
                });
            });
            
            // Hide search results when clicking outside
            $(document).click(function(e) {
                if (!$(e.target).closest('.search-container').length) {
                    $('.search-results').hide();
                }
            });
            
            // Handle contact form
            $('#send-contact').click(function() {
                const name = $('#contact-name').val();
                const email = $('#contact-email').val();
                const message = $('#contact-message').val();
                
                if (!name || !email || !message) {
                    showNotification('Please fill in all fields', 'error');
                    return;
                }
                
                // Simulate sending message
                showNotification('Message sent successfully!');
                
                // Clear form
                $('#contact-name').val('');
                $('#contact-email').val('');
                $('#contact-message').val('');
            });
            
            // Handle admin login
            $('#admin-login').click(function() {
                const username = $('#admin-username').val();
                const password = $('#admin-password').val();
                
                if (!username || !password) {
                    showNotification('Please enter username and password', 'error');
                    return;
                }
                
                // Check credentials
                if (username === 'admin' && password === 'techgear123') {
                    showNotification('Login successful!');
                    $('#admin-content').show();
                } else {
                    showNotification('Invalid credentials', 'error');
                }
            });
            
            // Handle add product
            $('#add-product').click(function() {
                const name = $('#product-name').val();
                const price = $('#product-price').val();
                const description = $('#product-desc').val();
                
                if (!name || !price || !description) {
                    showNotification('Please fill in all fields', 'error');
                    return;
                }
                
                // Simulate adding product
                showNotification('Product added successfully!');
                
                // Clear form
                $('#product-name').val('');
                $('#product-price').val('');
                $('#product-desc').val('');
            });
            
            // Handle add to cart
            $(document).on('click', '.add-to-cart', function() {
                const hash = $(this).data('hash');
                showNotification('Product added to cart!');
            });
            
            // Handle browser back/forward buttons
            window.addEventListener('popstate', function(e) {
                const page = new URLSearchParams(window.location.search).get('page') || 'home';
                showPage(page);
            });
            
            // Initial load
            loadProducts();
            
            // Load page from URL if specified
            const urlParams = new URLSearchParams(window.location.search);
            const pageParam = urlParams.get('page');
            if (pageParam) {
                showPage(pageParam);
            }
            
            // Handle product detail from URL
            if (pageParam === 'product' && urlParams.get('id')) {
                viewProduct(urlParams.get('id'));
            }
        });
    </script>
</body>
</html>
