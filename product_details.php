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
        /* ============================================
           PRODUCT DETAIL CONTAINER
        ============================================ */
        .product-detail-container {
            max-width: 1280px;
            margin: var(--spacing-2xl) auto;
            padding: 0 var(--spacing-xl);
        }

        /* Breadcrumb */
        .breadcrumb {
            margin-bottom: var(--spacing-xl);
            font-size: 0.85rem;
            color: var(--gray-500);
        }

        .breadcrumb a {
            color: var(--gray-500);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb a:hover {
            color: var(--primary);
        }

        .breadcrumb span {
            color: var(--gray-400);
            margin: 0 var(--spacing-xs);
        }

        .breadcrumb .current {
            color: var(--primary);
        }

        /* Product Detail Main Card */
        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-2xl);
            background: var(--white);
            border-radius: var(--radius-xl);
            padding: var(--spacing-xl);
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-md);
            margin-bottom: var(--spacing-2xl);
        }

        /* Product Gallery */
        .product-gallery {
            position: sticky;
            top: 100px;
        }

        .main-image {
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            border-radius: var(--radius-xl);
            overflow: hidden;
            margin-bottom: var(--spacing-md);
            text-align: center;
            border: 1px solid var(--gray-200);
            padding: var(--spacing-lg);
        }

        .main-image img {
            width: 100%;
            max-height: 400px;
            object-fit: contain;
            transition: transform var(--transition);
        }

        .main-image:hover img {
            transform: scale(1.05);
        }

        /* Product Info */
        .product-info h1 {
            font-size: 1.8rem;
            margin-bottom: var(--spacing-md);
            color: var(--gray-900);
        }

        /* Rating Section */
        .product-rating {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-lg);
            flex-wrap: wrap;
        }

        .stars {
            color: #fbbf24;
            font-size: 0.9rem;
        }

        .rating-count {
            color: var(--gray-500);
            font-size: 0.8rem;
        }

        .stock-status {
            padding: 4px 12px;
            border-radius: var(--radius-full);
            font-size: 0.7rem;
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

        /* Price Section */
        .product-price {
            margin-bottom: var(--spacing-lg);
            padding: var(--spacing-md) 0;
            border-top: 1px solid var(--gray-200);
            border-bottom: 1px solid var(--gray-200);
        }

        .current-price {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary);
        }

        .old-price {
            font-size: 1.1rem;
            color: var(--gray-400);
            text-decoration: line-through;
            margin-left: var(--spacing-sm);
        }

        .discount-badge {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: var(--white);
            padding: 4px 12px;
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: var(--spacing-sm);
        }

        /* Description */
        .product-description {
            margin: var(--spacing-lg) 0;
        }

        .product-description h3 {
            margin-bottom: var(--spacing-sm);
            font-size: 1rem;
            color: var(--gray-800);
        }

        .product-description p {
            color: var(--gray-600);
            line-height: 1.7;
            font-size: 0.9rem;
        }

        /* Specifications */
        .product-specs {
            margin: var(--spacing-lg) 0;
        }

        .product-specs h3 {
            margin-bottom: var(--spacing-md);
            font-size: 1rem;
            color: var(--gray-800);
        }

        .specs-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--spacing-xs);
        }

        .spec-item {
            display: flex;
            padding: var(--spacing-sm) 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .spec-label {
            width: 120px;
            font-weight: 500;
            color: var(--gray-700);
            font-size: 0.85rem;
        }

        .spec-value {
            flex: 1;
            color: var(--gray-600);
            font-size: 0.85rem;
        }

        /* Quantity Selector */
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            margin: var(--spacing-lg) 0;
            flex-wrap: wrap;
        }

        .quantity-selector label {
            font-weight: 500;
            color: var(--gray-700);
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .qty-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: var(--gray-50);
            cursor: pointer;
            font-size: 1.2rem;
            font-weight: bold;
            transition: var(--transition);
        }

        .qty-btn:hover {
            background: var(--primary);
            color: var(--white);
        }

        .quantity-controls input {
            width: 60px;
            text-align: center;
            border: none;
            padding: 10px 0;
            font-size: 1rem;
            font-weight: 500;
        }

        .quantity-controls input:focus {
            outline: none;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: var(--spacing-md);
            margin: var(--spacing-lg) 0;
            flex-wrap: wrap;
        }

        .add-to-cart-btn {
            flex: 2;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: 14px;
            border: none;
            border-radius: var(--radius-lg);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .add-to-cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .buy-now-btn {
            flex: 1;
            background: var(--gray-800);
            color: var(--white);
            padding: 14px;
            border: none;
            border-radius: var(--radius-lg);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .buy-now-btn:hover {
            background: var(--gray-900);
            transform: translateY(-2px);
        }

        .wishlist-btn {
            width: 50px;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            cursor: pointer;
            font-size: 1.2rem;
            transition: var(--transition);
        }

        .wishlist-btn:hover {
            background: #fef2f2;
            border-color: var(--danger);
        }

        .wishlist-btn.active {
            background: var(--danger);
            color: var(--white);
            border-color: var(--danger);
        }

        /* Product Meta */
        .product-meta {
            margin-top: var(--spacing-lg);
            padding-top: var(--spacing-lg);
            border-top: 1px solid var(--gray-200);
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            margin-bottom: var(--spacing-sm);
            font-size: 0.8rem;
            color: var(--gray-600);
        }

        .meta-item i {
            color: var(--primary);
            width: 20px;
        }

        /* Related Products Section */
        .related-products {
            margin-top: var(--spacing-2xl);
        }

        .related-products h2 {
            font-size: 1.5rem;
            margin-bottom: var(--spacing-xl);
            padding-left: var(--spacing-md);
            border-left: 4px solid var(--primary);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--spacing-xl);
        }

        /* Product Card for Related Items */
        .product-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            transition: var(--transition);
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary);
            box-shadow: var(--shadow-lg);
        }

        .p-img {
            height: 200px;
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .p-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition);
        }

        .product-card:hover .p-img img {
            transform: scale(1.08);
        }

        .p-discount {
            position: absolute;
            top: var(--spacing-sm);
            left: var(--spacing-sm);
            background: linear-gradient(135deg, var(--danger), #dc2626);
            color: var(--white);
            padding: 3px 8px;
            border-radius: var(--radius-full);
            font-size: 0.65rem;
            font-weight: 700;
        }

        .p-info {
            padding: var(--spacing-md);
        }

        .p-title {
            font-size: 0.85rem;
            font-weight: 500;
            height: 40px;
            overflow: hidden;
            margin-bottom: var(--spacing-sm);
            color: var(--gray-800);
        }

        .p-price {
            color: var(--primary);
            font-weight: 700;
            font-size: 1rem;
        }

        .p-old {
            text-decoration: line-through;
            color: var(--gray-400);
            font-size: 0.7rem;
            margin-left: 5px;
        }

        .p-btn {
            width: 100%;
            margin-top: var(--spacing-sm);
            padding: 8px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            border: none;
            border-radius: var(--radius-lg);
            cursor: pointer;
            font-weight: 600;
            font-size: 0.8rem;
            transition: var(--transition);
        }

        .p-btn:hover {
            transform: translateY(-2px);
        }

        /* Notification */
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: var(--radius-lg);
            color: var(--white);
            font-weight: 500;
            z-index: 1000;
            animation: slideInLeft 0.3s ease;
            box-shadow: var(--shadow-lg);
        }

        .notification.success { background: linear-gradient(135deg, #22c55e, #16a34a); }
        .notification.error { background: linear-gradient(135deg, #ef4444, #dc2626); }

        @keyframes slideInLeft {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 1024px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--spacing-md);
            }
        }

        @media (max-width: 768px) {
            .product-detail-container {
                padding: 0 var(--spacing-md);
                margin: var(--spacing-xl) auto;
            }
            .product-detail {
                grid-template-columns: 1fr;
                gap: var(--spacing-lg);
                padding: var(--spacing-lg);
            }
            .product-gallery {
                position: static;
            }
            .action-buttons {
                flex-direction: column;
            }
            .wishlist-btn {
                width: 100%;
                padding: 10px;
            }
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
            .spec-item {
                flex-direction: column;
            }
            .spec-label {
                width: 100%;
                margin-bottom: 4px;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="product-detail-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <span><i class="fas fa-chevron-right"></i></span>
            <a href="category.php?cat=<?php echo $product['category']; ?>"><?php echo ucfirst($product['category']); ?></a>
            <span><i class="fas fa-chevron-right"></i></span>
            <span class="current"><?php echo $product['name']; ?></span>
        </div>
        
        <!-- Product Detail Main Section -->
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
                        <i class="fas <?php echo $product['stock'] > 0 ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                        <?php echo $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
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
                    <h3><i class="fas fa-align-left"></i> Product Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($product['description'] ?: 'No description available for this product.')); ?></p>
                </div>
                
                <?php if(!empty($product['specifications'])): ?>
                <div class="product-specs">
                    <h3><i class="fas fa-list-ul"></i> Specifications</h3>
                    <div class="specs-grid">
                        <div class="spec-item">
                            <span class="spec-label">Brand:</span>
                            <span class="spec-value"><?php echo $product['brand'] ?? 'Relyve'; ?></span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Category:</span>
                            <span class="spec-value"><?php echo ucfirst($product['category']); ?></span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">SKU:</span>
                            <span class="spec-value">#<?php echo $product['id']; ?></span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
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
                        <i class="fas fa-bolt"></i> Buy Now
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
                        <span>7 days easy return policy</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>1 year warranty on all products</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-box"></i>
                        <span>Stock: <?php echo $product['stock']; ?> units available</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Products Section -->
        <?php if(mysqli_num_rows($related_products) > 0): ?>
        <div class="related-products">
            <h2><i class="fas fa-heart"></i> You Might Also Like</h2>
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
                            <button class="p-btn" onclick="event.stopPropagation(); addToCart(<?php echo $related['id']; ?>, 1)">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
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

// Add to cart function
function addToCart(productId, quantity = null) {
    let qty = quantity || document.getElementById('quantity').value;
    
    <?php if(!isset($_SESSION['user_id'])): ?>
        if(confirm('Please login to add items to cart. Go to login page?')) {
            window.location.href = 'login_form.php';
        }
        return;
    <?php endif; ?>
    
    const addBtn = document.querySelector('.add-to-cart-btn');
    const originalText = addBtn.innerHTML;
    addBtn.disabled = true;
    addBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    
    fetch('process/add_to_cart.php?product_id=' + productId + '&quantity=' + qty, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        addBtn.disabled = false;
        addBtn.innerHTML = originalText;
        
        if(data.success) {
            let message = data.action === 'updated' ? 'Cart updated! Quantity increased.' : 'Product added to cart!';
            showNotification(message, 'success');
            
            if(typeof window.updateCartBadge === 'function') {
                window.updateCartBadge();
            }
        } else {
            showNotification(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        addBtn.disabled = false;
        addBtn.innerHTML = originalText;
        console.error('Error:', error);
        showNotification('Error adding to cart', 'error');
    });
}

// Buy now function
function buyNow(productId) {
    const qty = document.getElementById('quantity').value;
    addToCart(productId, qty);
    setTimeout(() => {
        window.location.href = 'cart.php';
    }, 500);
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
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notif => notif.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${message}`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add slideOut animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
</script>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>