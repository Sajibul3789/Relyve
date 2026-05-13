<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = getProductById($conn, $product_id);

if (!$product) {
    header("Location: index.php");
    exit();
}

// Get related products
$category = $product['category'];
$related_sql = "SELECT * FROM products WHERE category = '$category' AND id != $product_id LIMIT 4";
$related_products = mysqli_query($conn, $related_sql);

// Check if product is in wishlist
$in_wishlist = false;
if(isset($_SESSION['user_id'])) {
    $wishlist_check = mysqli_query($conn, "SELECT id FROM wishlist WHERE user_id = {$_SESSION['user_id']} AND product_id = $product_id");
    $in_wishlist = mysqli_num_rows($wishlist_check) > 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?> - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .product-detail-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .breadcrumb {
            margin-bottom: 30px;
            font-size: 0.85rem;
            color: #666;
        }
        .breadcrumb a {
            color: #666;
            text-decoration: none;
        }
        .breadcrumb a:hover {
            color: #f97316;
        }
        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .product-gallery {
            position: sticky;
            top: 100px;
        }
        .main-image {
            background: #f8f9fa;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 15px;
            text-align: center;
        }
        .main-image img {
            width: 100%;
            max-height: 400px;
            object-fit: contain;
        }
        .product-info h1 {
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        .product-rating {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .stars {
            color: #fbbf24;
        }
        .rating-count {
            color: #666;
            font-size: 0.85rem;
        }
        .stock-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .in-stock {
            background: #dcfce7;
            color: #16a34a;
        }
        .out-stock {
            background: #fee2e2;
            color: #dc2626;
        }
        .product-price {
            margin-bottom: 20px;
        }
        .current-price {
            font-size: 2rem;
            font-weight: 700;
            color: #f97316;
        }
        .old-price {
            font-size: 1.1rem;
            color: #999;
            text-decoration: line-through;
            margin-left: 10px;
        }
        .discount-badge {
            background: #ef4444;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-left: 10px;
        }
        .product-description {
            margin: 20px 0;
            padding: 20px 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }
        .product-description h3 {
            margin-bottom: 10px;
            font-size: 1rem;
        }
        .product-description p {
            color: #666;
            line-height: 1.6;
        }
        .product-specs {
            margin: 20px 0;
        }
        .product-specs h3 {
            margin-bottom: 10px;
            font-size: 1rem;
        }
        .product-specs ul {
            list-style: none;
        }
        .product-specs li {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            flex-wrap: wrap;
        }
        .product-specs li strong {
            width: 120px;
        }
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .qty-btn {
            width: 36px;
            height: 36px;
            border: none;
            background: #f8f9fa;
            cursor: pointer;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .qty-btn:hover {
            background: #f97316;
            color: white;
        }
        .quantity-controls input {
            width: 50px;
            text-align: center;
            border: none;
            padding: 8px 0;
        }
        .action-buttons {
            display: flex;
            gap: 15px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        .add-to-cart-btn {
            flex: 2;
            background: #f97316;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .add-to-cart-btn:hover {
            background: #ea580c;
        }
        .buy-now-btn {
            flex: 1;
            background: #1f2937;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .buy-now-btn:hover {
            background: #111827;
        }
        .wishlist-btn {
            width: 50px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.3s;
        }
        .wishlist-btn:hover {
            background: #fef2f2;
            border-color: #ef4444;
        }
        .wishlist-btn.active {
            background: #ef4444;
            color: white;
            border-color: #ef4444;
        }
        .product-meta {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 0.85rem;
            color: #666;
        }
        .meta-item i {
            color: #f97316;
            width: 20px;
        }
        .related-products {
            margin-top: 60px;
        }
        .related-products h2 {
            margin-bottom: 30px;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 25px;
        }
        .product-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid #f0f0f0;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .p-img {
            height: 200px;
            overflow: hidden;
            position: relative;
        }
        .p-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .p-discount {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #ef4444;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .p-info {
            padding: 15px;
        }
        .p-title {
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 8px;
        }
        .p-price {
            color: #f97316;
            font-weight: 700;
            font-size: 1.1rem;
        }
        .p-old {
            text-decoration: line-through;
            color: #999;
            font-size: 0.8rem;
            margin-left: 5px;
        }
        .p-btn {
            width: 100%;
            margin-top: 10px;
            padding: 8px;
            background: #f97316;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 10px;
            color: white;
            z-index: 1000;
            animation: slideIn 0.3s ease;
        }
        .notification.success { background: #22c55e; }
        .notification.error { background: #ef4444; }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @media (max-width: 768px) {
            .product-detail {
                grid-template-columns: 1fr;
                gap: 30px;
                padding: 20px;
            }
            .action-buttons {
                flex-direction: column;
            }
            .wishlist-btn {
                width: 100%;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="product-detail-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php">Home</a> / 
            <a href="category.php?cat=<?php echo $product['category']; ?>"><?php echo ucfirst($product['category']); ?></a> / 
            <span><?php echo $product['name']; ?></span>
        </div>
        
        <div class="product-detail">
            <!-- Product Gallery -->
            <div class="product-gallery">
                <div class="main-image">
                    <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" id="mainImage">
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="product-info">
                <h1><?php echo $product['name']; ?></h1>
                
                <div class="product-rating">
                    <div class="stars">
                        <?php 
                        $rating = $product['rating'] ?? 4.5;
                        for($i = 1; $i <= 5; $i++):
                            if($i <= floor($rating)):
                                echo '<i class="fas fa-star"></i>';
                            elseif($i - $rating <= 0.5):
                                echo '<i class="fas fa-star-half-alt"></i>';
                            else:
                                echo '<i class="far fa-star"></i>';
                            endif;
                        endfor;
                        ?>
                    </div>
                    <span class="rating-count">(128 reviews)</span>
                    <span class="stock-status <?php echo $product['stock'] > 0 ? 'in-stock' : 'out-stock'; ?>">
                        <?php echo $product['stock'] > 0 ? '✓ In Stock' : '✗ Out of Stock'; ?>
                    </span>
                </div>
                
                <div class="product-price">
                    <span class="current-price">৳<?php echo number_format($product['price']); ?></span>
                    <?php if($product['old_price'] && $product['old_price'] > 0): ?>
                        <span class="old-price">৳<?php echo number_format($product['old_price']); ?></span>
                        <span class="discount-badge">
                            -<?php echo round((($product['old_price'] - $product['price']) / $product['old_price']) * 100); ?>%
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="product-description">
                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($product['description'] ?: 'No description available for this product.')); ?></p>
                </div>
                
                <div class="quantity-selector">
                    <label>Quantity:</label>
                    <div class="quantity-controls">
                        <button class="qty-btn" id="decrementBtn">-</button>
                        <input type="number" id="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>">
                        <button class="qty-btn" id="incrementBtn">+</button>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <button class="add-to-cart-btn" onclick="addToCart(<?php echo $product['id']; ?>)">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                    <button class="buy-now-btn" onclick="buyNow(<?php echo $product['id']; ?>)">
                        Buy Now
                    </button>
                    <button class="wishlist-btn <?php echo $in_wishlist ? 'active' : ''; ?>" id="wishlistBtn" onclick="toggleWishlist(<?php echo $product['id']; ?>)">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
                
                <div class="product-meta">
                    <div class="meta-item">
                        <i class="fas fa-truck"></i>
                        <span>Free delivery on orders over ৳5000</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-undo-alt"></i>
                        <span>7 days return policy</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>1 year warranty</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-tag"></i>
                        <span>Category: <?php echo ucfirst($product['category']); ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-box"></i>
                        <span>Stock: <?php echo $product['stock']; ?> units available</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        <?php if(mysqli_num_rows($related_products) > 0): ?>
        <div class="related-products">
            <h2>You Might Also Like</h2>
            <div class="product-grid">
                <?php while($related = mysqli_fetch_assoc($related_products)): ?>
                    <div class="product-card" onclick="location.href='product_details.php?id=<?php echo $related['id']; ?>'">
                        <div class="p-img">
                            <img src="<?php echo $related['image_url']; ?>" alt="<?php echo $related['name']; ?>">
                            <?php if($related['old_price'] && $related['old_price'] > 0): ?>
                                <div class="p-discount">
                                    -<?php echo round((($related['old_price'] - $related['price']) / $related['old_price']) * 100); ?>%
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-info">
                            <div class="p-title"><?php echo htmlspecialchars($related['name']); ?></div>
                            <div class="p-price">
                                ৳<?php echo number_format($related['price']); ?>
                                <?php if($related['old_price'] && $related['old_price'] > 0): ?>
                                    <span class="p-old">৳<?php echo number_format($related['old_price']); ?></span>
                                <?php endif; ?>
                            </div>
                            <button class="p-btn" onclick="event.stopPropagation(); addToCart(<?php echo $related['id']; ?>, 1)">Add to Cart</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<script>
// Quantity controls
const quantityInput = document.getElementById('quantity');
const decrementBtn = document.getElementById('decrementBtn');
const incrementBtn = document.getElementById('incrementBtn');
const maxStock = <?php echo $product['stock']; ?>;

decrementBtn.addEventListener('click', function() {
    let currentVal = parseInt(quantityInput.value);
    if(currentVal > 1) {
        quantityInput.value = currentVal - 1;
    }
});

incrementBtn.addEventListener('click', function() {
    let currentVal = parseInt(quantityInput.value);
    if(currentVal < maxStock) {
        quantityInput.value = currentVal + 1;
    }
});

quantityInput.addEventListener('change', function() {
    let val = parseInt(this.value);
    if(isNaN(val) || val < 1) {
        this.value = 1;
    } else if(val > maxStock) {
        this.value = maxStock;
        showNotification('Only ' + maxStock + ' items available in stock', 'error');
    }
});

// Add to cart - ONLY calls the global function
function addToCart(productId, quantity = null) {
    let qty = quantity || document.getElementById('quantity').value;
    
    <?php if(!isset($_SESSION['user_id'])): ?>
        if(confirm('Please login to add items to cart. Go to login page?')) {
            window.location.href = 'login_form.php';
        }
        return;
    <?php endif; ?>
    
    fetch('process/add_to_cart.php?product_id=' + productId + '&quantity=' + qty, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            let message = data.action === 'updated' ? 'Cart updated! Quantity increased.' : 'Product added to cart!';
            showNotification(message, 'success');
            
            // Just call the global function - navbar handles the rest!
            if(typeof window.updateCartBadge === 'function') {
                window.updateCartBadge();
            }
        } else {
            showNotification(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding to cart', 'error');
    });
}

// Toggle wishlist
function toggleWishlist(productId) {
    <?php if(!isset($_SESSION['user_id'])): ?>
        if(confirm('Please login to add to wishlist. Go to login page?')) {
            window.location.href = 'login_form.php';
        }
        return;
    <?php endif; ?>
    
    const btn = document.getElementById('wishlistBtn');
    const isActive = btn.classList.contains('active');
    
    let url = isActive ? 'process/remove_from_wishlist.php' : 'process/add_to_wishlist.php';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            btn.classList.toggle('active');
            showNotification(isActive ? 'Removed from wishlist' : 'Added to wishlist', 'success');
            // Update wishlist badge
            if(typeof updateWishlistBadge === 'function') {
                updateWishlistBadge();
            }
        } else {
            showNotification(data.message || 'Error', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error', 'error');
    });
}

// Show notification
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${message}`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Update cart count
function updateCartCount() {
    fetch('process/get_cart_count.php')
    .then(response => response.json())
    .then(data => {
        document.querySelectorAll('.badge').forEach(badge => {
            badge.textContent = data.count || 0;
        });
    })
    .catch(error => console.error('Error:', error));
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});
</script>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>