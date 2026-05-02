<?php
session_start();
include 'includes/navbar.php';
include 'config/db_connect.php';

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?> - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/product_details.css">
</head>
<body>

<main>
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <i class="fas fa-chevron-right"></i>
            <a href="#"><?php echo ucfirst($product['category']); ?></a>
            <i class="fas fa-chevron-right"></i>
            <span><?php echo $product['name']; ?></span>
        </div>

        <!-- Product Main Section -->
        <div class="product-detail">
            <div class="product-gallery">
                <div class="main-image">
                    <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" id="mainProductImage">
                </div>
                <div class="thumbnail-list">
                    <div class="thumbnail active">
                        <img src="<?php echo $product['image_url']; ?>" alt="Thumbnail 1">
                    </div>
                    <div class="thumbnail">
                        <img src="<?php echo $product['image_url']; ?>" alt="Thumbnail 2">
                    </div>
                </div>
            </div>

            <div class="product-info">
                <div class="product-category"><?php echo ucfirst($product['category']); ?></div>
                <h1 class="product-title"><?php echo $product['name']; ?></h1>
                
                <div class="product-rating">
                    <div class="stars">
                        <?php 
                        $rating = $product['rating'];
                        for($i = 1; $i <= 5; $i++) {
                            if($i <= $rating) {
                                echo '<i class="fas fa-star"></i>';
                            } elseif($i - 0.5 <= $rating) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        }
                        ?>
                    </div>
                    <span class="rating-count">(128 reviews)</span>
                    <span class="stock-status <?php echo $product['stock'] > 0 ? 'in-stock' : 'out-stock'; ?>">
                        <?php echo $product['stock'] > 0 ? '✓ In Stock' : '✗ Out of Stock'; ?>
                    </span>
                </div>

                <div class="product-price">
                    <div class="current-price">৳<?php echo number_format($product['price']); ?></div>
                    <?php if($product['old_price']): ?>
                        <div class="old-price">৳<?php echo number_format($product['old_price']); ?></div>
                        <div class="discount-badge">
                            -<?php echo round((($product['old_price'] - $product['price']) / $product['old_price']) * 100); ?>%
                        </div>
                    <?php endif; ?>
                </div>

                <div class="product-description">
                    <h3>Description</h3>
                    <p><?php echo $product['description']; ?></p>
                </div>

                <?php if($product['specs']): 
                    $specs = json_decode($product['specs'], true);
                    if($specs): ?>
                    <div class="product-specs">
                        <h3>Key Specifications</h3>
                        <ul>
                            <?php foreach($specs as $key => $value): ?>
                                <li><strong><?php echo $key; ?>:</strong> <?php echo $value; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; endif; ?>

                <div class="purchase-options">
                    <div class="quantity-selector">
                        <label>Quantity:</label>
                        <div class="quantity-controls">
                            <button class="qty-btn" onclick="decrementQty()">-</button>
                            <input type="number" id="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>">
                            <button class="qty-btn" onclick="incrementQty()">+</button>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button class="add-to-cart-btn" onclick="addToCart(<?php echo $product['id']; ?>)">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button class="buy-now-btn" onclick="buyNow(<?php echo $product['id']; ?>)">
                            Buy Now
                        </button>
                        <button class="wishlist-btn" onclick="addToWishlist(<?php echo $product['id']; ?>)">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
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
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php if(mysqli_num_rows($related_products) > 0): ?>
        <div class="related-products">
            <h2>You Might Also Like</h2>
            <div class="product-grid">
                <?php while($related = mysqli_fetch_assoc($related_products)): ?>
                <div class="product-card">
                    <div class="p-img">
                        <img src="<?php echo $related['image_url']; ?>" alt="<?php echo $related['name']; ?>">
                        <?php if($related['old_price']): ?>
                            <div class="p-discount">
                                -<?php echo round((($related['old_price'] - $related['price']) / $related['old_price']) * 100); ?>%
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-info">
                        <div class="p-title"><?php echo $related['name']; ?></div>
                        <div class="p-price">৳<?php echo number_format($related['price']); ?>
                            <?php if($related['old_price']): ?>
                                <span class="p-old">৳<?php echo number_format($related['old_price']); ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="product-details.php?id=<?php echo $related['id']; ?>" class="p-btn">View Details</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<script>
function decrementQty() {
    let qty = document.getElementById('quantity');
    if (qty.value > 1) {
        qty.value = parseInt(qty.value) - 1;
    }
}

function incrementQty() {
    let qty = document.getElementById('quantity');
    let max = <?php echo $product['stock']; ?>;
    if (qty.value < max) {
        qty.value = parseInt(qty.value) + 1;
    }
}

function addToCart(productId) {
    let quantity = document.getElementById('quantity').value;
    
    fetch('process/add_to_cart_process.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId + '&quantity=' + quantity
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            showNotification('Product added to cart!', 'success');
            updateCartCount();
        } else {
            showNotification(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        showNotification('Error adding to cart', 'error');
    });
}

function buyNow(productId) {
    let quantity = document.getElementById('quantity').value;
    window.location.href = 'checkout.php?product_id=' + productId + '&quantity=' + quantity;
}

function addToWishlist(productId) {
    fetch('process/add_to_wishlist.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            showNotification('Added to wishlist!', 'success');
        } else {
            showNotification('Please login to add to wishlist', 'error');
        }
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${message}`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function updateCartCount() {
    fetch('process/get_cart_count.php')
    .then(response => response.json())
    .then(data => {
        const badges = document.querySelectorAll('.badge');
        badges.forEach(badge => {
            badge.textContent = data.count;
        });
    });
}
</script>
</body>
</html>