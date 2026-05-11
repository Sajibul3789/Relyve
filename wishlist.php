<?php
session_start();
include 'includes/navbar.php';
include 'config/db_connect.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle remove from wishlist
if(isset($_GET['remove'])) {
    $product_id = (int)$_GET['remove'];
    mysqli_query($conn, "DELETE FROM wishlist WHERE user_id = $user_id AND product_id = $product_id");
    header("Location: wishlist.php");
    exit();
}

// Handle clear wishlist
if(isset($_GET['clear'])) {
    mysqli_query($conn, "DELETE FROM wishlist WHERE user_id = $user_id");
    header("Location: wishlist.php");
    exit();
}

// Handle add all to cart
if(isset($_POST['add_all_to_cart'])) {
    $wishlist_query = mysqli_query($conn, "SELECT product_id FROM wishlist WHERE user_id = $user_id");
    while($item = mysqli_fetch_assoc($wishlist_query)) {
        add_to_cart($conn, $user_id, $item['product_id'], 1);
    }
    header("Location: wishlist.php?added=all");
    exit();
}

// Get wishlist items with product details using JOIN
$wishlist_sql = "SELECT p.*, w.created_at as date_added 
                 FROM wishlist w 
                 JOIN products p ON w.product_id = p.id 
                 WHERE w.user_id = $user_id 
                 ORDER BY w.created_at DESC";
$wishlist_result = mysqli_query($conn, $wishlist_sql);
$wishlist_count = mysqli_num_rows($wishlist_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .wishlist-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .wishlist-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .wishlist-title {
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .wishlist-title i {
            color: #ef4444;
        }
        
        .wishlist-title span {
            background: #f97316;
            color: white;
            font-size: 0.9rem;
            padding: 2px 10px;
            border-radius: 20px;
        }
        
        .wishlist-actions {
            display: flex;
            gap: 10px;
        }
        
        .clear-wishlist-btn, .add-all-btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }
        
        .clear-wishlist-btn {
            background: white;
            border: 1px solid #ef4444;
            color: #ef4444;
        }
        
        .clear-wishlist-btn:hover {
            background: #ef4444;
            color: white;
        }
        
        .add-all-btn {
            background: #22c55e;
            color: white;
            border: none;
        }
        
        .add-all-btn:hover {
            background: #16a34a;
            transform: translateY(-2px);
        }
        
        /* Empty Wishlist */
        .empty-wishlist {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 16px;
        }
        
        .empty-wishlist i {
            font-size: 5rem;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        .empty-wishlist h2 {
            margin-bottom: 10px;
        }
        
        .empty-wishlist p {
            color: #666;
            margin-bottom: 30px;
        }
        
        .shop-now-btn {
            display: inline-block;
            background: #f97316;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }
        
        /* Wishlist Grid */
        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
        
        .wishlist-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s;
            position: relative;
        }
        
        .wishlist-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .remove-wishlist {
            position: absolute;
            top: 12px;
            right: 12px;
            background: white;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            color: #999;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            z-index: 10;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .remove-wishlist:hover {
            background: #ef4444;
            color: white;
            transform: scale(1.1);
        }
        
        .wishlist-img {
            height: 220px;
            overflow: hidden;
            cursor: pointer;
            background: #f8f9fa;
        }
        
        .wishlist-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .wishlist-card:hover .wishlist-img img {
            transform: scale(1.05);
        }
        
        .wishlist-info {
            padding: 15px;
        }
        
        .wishlist-name {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 8px;
            cursor: pointer;
        }
        
        .wishlist-name:hover {
            color: #f97316;
        }
        
        .wishlist-price {
            display: flex;
            align-items: baseline;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .current-price {
            color: #f97316;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .old-price {
            color: #999;
            font-size: 0.85rem;
            text-decoration: line-through;
        }
        
        .discount-badge {
            background: #ef4444;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .wishlist-stock {
            font-size: 0.75rem;
            margin-bottom: 15px;
        }
        
        .in-stock {
            color: #22c55e;
        }
        
        .out-stock {
            color: #ef4444;
        }
        
        .wishlist-actions-card {
            display: flex;
            gap: 10px;
        }
        
        .add-to-cart-wishlist {
            flex: 2;
            padding: 10px;
            background: #f97316;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .add-to-cart-wishlist:hover {
            background: #ea580c;
        }
        
        .add-to-cart-wishlist:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .view-details {
            flex: 1;
            padding: 10px;
            background: #f3f4f6;
            color: #374151;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            text-align: center;
            display: inline-block;
            font-size: 0.85rem;
        }
        
        .view-details:hover {
            background: #e5e7eb;
        }
        
        .date-added {
            font-size: 0.7rem;
            color: #999;
            margin-top: 10px;
            text-align: center;
            padding-top: 10px;
            border-top: 1px solid #f0f0f0;
        }
        
        /* Notification */
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 10px;
            color: white;
            font-weight: 500;
            z-index: 10001;
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .notification.success {
            background: #22c55e;
        }
        
        .notification.error {
            background: #ef4444;
        }
        
        .notification.info {
            background: #3b82f6;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        @media (max-width: 768px) {
            .wishlist-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .wishlist-actions {
                width: 100%;
                justify-content: space-between;
            }
            
            .wishlist-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 15px;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="wishlist-container">
        <div class="wishlist-header">
            <div class="wishlist-title">
                <i class="fas fa-heart"></i>
                <h1>My Wishlist</h1>
                <span><?php echo $wishlist_count; ?> items</span>
            </div>
            
            <?php if($wishlist_count > 0): ?>
            <div class="wishlist-actions">
                <form method="POST" style="display: inline;">
                    <button type="submit" name="add_all_to_cart" class="add-all-btn" onclick="return confirm('Add all items from wishlist to cart?')">
                        <i class="fas fa-cart-plus"></i> Add All to Cart
                    </button>
                </form>
                <a href="?clear=1" class="clear-wishlist-btn" onclick="return confirm('Clear entire wishlist?')">
                    <i class="fas fa-trash-alt"></i> Clear Wishlist
                </a>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if($wishlist_count == 0): ?>
            <div class="empty-wishlist">
                <i class="far fa-heart"></i>
                <h2>Your wishlist is empty</h2>
                <p>Save your favorite items here to buy them later</p>
                <a href="index.php" class="shop-now-btn">Start Shopping →</a>
            </div>
        <?php else: ?>
            <div class="wishlist-grid">
                <?php while($product = mysqli_fetch_assoc($wishlist_result)): 
                    $discount = 0;
                    if($product['old_price'] && $product['old_price'] > 0) {
                        $discount = round((($product['old_price'] - $product['price']) / $product['old_price']) * 100);
                    }
                ?>
                    <div class="wishlist-card" id="wishlist-item-<?php echo $product['id']; ?>">
                        <button class="remove-wishlist" onclick="removeFromWishlist(<?php echo $product['id']; ?>)">
                            <i class="fas fa-times"></i>
                        </button>
                        
                        <div class="wishlist-img" onclick="location.href='product_details.php?id=<?php echo $product['id']; ?>'">
                            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                        </div>
                        
                        <div class="wishlist-info">
                            <div class="wishlist-name" onclick="location.href='product_details.php?id=<?php echo $product['id']; ?>'">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </div>
                            
                            <div class="wishlist-price">
                                <span class="current-price">৳<?php echo number_format($product['price']); ?></span>
                                <?php if($product['old_price']): ?>
                                    <span class="old-price">৳<?php echo number_format($product['old_price']); ?></span>
                                    <span class="discount-badge">-<?php echo $discount; ?>%</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="wishlist-stock">
                                <?php if($product['stock'] > 0): ?>
                                    <span class="in-stock"><i class="fas fa-check-circle"></i> In Stock (<?php echo $product['stock']; ?> left)</span>
                                <?php else: ?>
                                    <span class="out-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="wishlist-actions-card">
                                <button class="add-to-cart-wishlist" onclick="addToCart(<?php echo $product['id']; ?>, 1)" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                                <a href="product_details.php?id=<?php echo $product['id']; ?>" class="view-details">
                                    View
                                </a>
                            </div>
                            
                            <div class="date-added">
                                <i class="far fa-clock"></i> Added on <?php echo date('M j, Y', strtotime($product['date_added'])); ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<script>
// Show notification
function showNotification(message, type) {
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notif => notif.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'info' ? 'fa-info-circle' : 'fa-exclamation-circle'}"></i> ${message}`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add to cart function
function addToCart(productId, quantity) {
    <?php if(!isset($_SESSION['user_id'])): ?>
        if(confirm('Please login to add items to cart. Go to login page?')) {
            window.location.href = 'login_form.php';
        }
        return;
    <?php endif; ?>
    
    fetch('process/add_to_cart.php?product_id=' + productId + '&quantity=' + quantity, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            showNotification(data.message || 'Product added to cart!', 'success');
            updateCartCount();
        } else {
            showNotification(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding to cart', 'error');
    });
}

// Remove from wishlist
function removeFromWishlist(productId) {
    if(confirm('Remove this item from your wishlist?')) {
        fetch('process/remove_from_wishlist.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const wishlistItem = document.getElementById('wishlist-item-' + productId);
                wishlistItem.style.transition = 'all 0.3s';
                wishlistItem.style.opacity = '0';
                wishlistItem.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    wishlistItem.remove();
                    showNotification('Item removed from wishlist', 'info');
                    
                    // Update count
                    const remainingItems = document.querySelectorAll('.wishlist-card').length;
                    const countSpan = document.querySelector('.wishlist-title span');
                    if(countSpan) {
                        countSpan.textContent = remainingItems + ' items';
                    }
                    
                    // Show empty state if no items left
                    if(remainingItems === 0) {
                        location.reload();
                    }
                }, 300);
            } else {
                showNotification('Error removing item', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error removing item', 'error');
        });
    }
}

// Update cart count badge
function updateCartCount() {
    fetch('process/get_cart_count.php')
    .then(response => response.json())
    .then(data => {
        const badges = document.querySelectorAll('.badge');
        badges.forEach(badge => {
            badge.textContent = data.count;
        });
    })
    .catch(error => console.error('Error:', error));
}

// Add CSS animations if not present
if(!document.querySelector('#wishlist-styles')) {
    const style = document.createElement('style');
    style.id = 'wishlist-styles';
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});
</script>

</body>
</html>