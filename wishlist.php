<?php
session_start();
include_once 'config/db_connect.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ============================================
// HANDLE ALL ACTIONS BEFORE ANY OUTPUT
// ============================================

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

// ============================================
// NOW INCLUDE NAVBAR - AFTER ALL redirects
// ============================================
include_once 'includes/navbar.php';

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
        /* ============================================
           WISHLIST CONTAINER
        ============================================ */
        .wishlist-container {
            max-width: 1280px;
            margin: var(--spacing-2xl) auto;
            padding: 0 var(--spacing-xl);
        }

        /* Breadcrumb */
        .breadcrumb {
            margin-bottom: var(--spacing-xl);
        }

        .breadcrumb a {
            color: var(--gray-500);
            text-decoration: none;
            font-size: 0.85rem;
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
            font-weight: 500;
        }

        /* Wishlist Header */
        .wishlist-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: var(--spacing-lg);
            margin-bottom: var(--spacing-2xl);
            padding-bottom: var(--spacing-lg);
            border-bottom: 2px solid var(--gray-200);
        }

        .wishlist-title {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }

        .wishlist-title i {
            font-size: 2rem;
            color: #ef4444;
        }

        .wishlist-title h1 {
            font-size: 1.8rem;
            margin-bottom: 0;
            color: var(--gray-900);
        }

        .wishlist-title span {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            font-size: 0.8rem;
            padding: 4px 12px;
            border-radius: var(--radius-full);
            font-weight: 600;
        }

        /* Wishlist Actions */
        .wishlist-actions {
            display: flex;
            gap: var(--spacing-md);
        }

        .clear-wishlist-btn, .add-all-btn {
            padding: 10px 20px;
            border-radius: var(--radius-lg);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: var(--transition);
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .clear-wishlist-btn {
            background: var(--white);
            border: 1px solid var(--danger);
            color: var(--danger);
        }

        .clear-wishlist-btn:hover {
            background: var(--danger);
            color: var(--white);
            transform: translateY(-2px);
        }

        .add-all-btn {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: var(--white);
        }

        .add-all-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Empty Wishlist */
        .empty-wishlist {
            text-align: center;
            padding: var(--spacing-3xl);
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
        }

        .empty-wishlist i {
            font-size: 5rem;
            color: var(--gray-300);
            margin-bottom: var(--spacing-lg);
        }

        .empty-wishlist h2 {
            font-size: 1.5rem;
            margin-bottom: var(--spacing-md);
            color: var(--gray-800);
        }

        .empty-wishlist p {
            color: var(--gray-500);
            margin-bottom: var(--spacing-xl);
        }

        .shop-now-btn {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: 12px 30px;
            border-radius: var(--radius-lg);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .shop-now-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Wishlist Grid */
        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: var(--spacing-xl);
        }

        /* Wishlist Card */
        .wishlist-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            transition: var(--transition);
            position: relative;
        }

        .wishlist-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary);
            box-shadow: var(--shadow-xl);
        }

        /* Remove Button */
        .remove-wishlist {
            position: absolute;
            top: var(--spacing-md);
            right: var(--spacing-md);
            background: var(--white);
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            cursor: pointer;
            color: var(--gray-400);
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            z-index: 10;
            box-shadow: var(--shadow-sm);
        }

        .remove-wishlist:hover {
            background: var(--danger);
            color: var(--white);
            transform: scale(1.1);
        }

        /* Product Image */
        .wishlist-img {
            height: 220px;
            overflow: hidden;
            cursor: pointer;
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            position: relative;
        }

        .wishlist-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition);
        }

        .wishlist-card:hover .wishlist-img img {
            transform: scale(1.08);
        }

        /* Discount Badge on Image */
        .img-discount {
            position: absolute;
            top: var(--spacing-md);
            left: var(--spacing-md);
            background: linear-gradient(135deg, var(--danger), #dc2626);
            color: var(--white);
            padding: 4px 12px;
            border-radius: var(--radius-full);
            font-size: 0.7rem;
            font-weight: 700;
            z-index: 5;
        }

        /* Wishlist Info */
        .wishlist-info {
            padding: var(--spacing-lg);
        }

        .wishlist-name {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: var(--spacing-sm);
            cursor: pointer;
            color: var(--gray-800);
            line-height: 1.4;
            height: 44px;
            overflow: hidden;
        }

        .wishlist-name:hover {
            color: var(--primary);
        }

        /* Price Section */
        .wishlist-price {
            display: flex;
            align-items: baseline;
            gap: var(--spacing-sm);
            flex-wrap: wrap;
            margin-bottom: var(--spacing-sm);
        }

        .current-price {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.2rem;
        }

        .old-price {
            color: var(--gray-400);
            font-size: 0.85rem;
            text-decoration: line-through;
        }

        .discount-badge {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: var(--white);
            padding: 2px 8px;
            border-radius: var(--radius-full);
            font-size: 0.65rem;
            font-weight: 600;
        }

        /* Rating */
        .rating {
            margin: var(--spacing-sm) 0;
            display: flex;
            gap: 3px;
        }

        .rating i {
            color: #fbbf24;
            font-size: 0.75rem;
        }

        /* Stock Status */
        .wishlist-stock {
            font-size: 0.75rem;
            margin-bottom: var(--spacing-md);
        }

        .in-stock {
            color: var(--success);
        }

        .out-stock {
            color: var(--danger);
        }

        /* Card Actions */
        .wishlist-actions-card {
            display: flex;
            gap: var(--spacing-sm);
            margin-top: var(--spacing-sm);
        }

        .add-to-cart-wishlist {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            border: none;
            border-radius: var(--radius-lg);
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
        }

        .add-to-cart-wishlist:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        .add-to-cart-wishlist:disabled {
            background: var(--gray-300);
            cursor: not-allowed;
            transform: none;
        }

        /* Date Added */
        .date-added {
            font-size: 0.7rem;
            color: var(--gray-400);
            margin-top: var(--spacing-md);
            padding-top: var(--spacing-sm);
            border-top: 1px solid var(--gray-100);
            text-align: center;
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
            z-index: 10001;
            animation: slideIn 0.3s ease;
            box-shadow: var(--shadow-lg);
        }

        .notification.success { background: linear-gradient(135deg, #22c55e, #16a34a); }
        .notification.error { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .notification.info { background: linear-gradient(135deg, #3b82f6, #2563eb); }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 1024px) {
            .wishlist-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .wishlist-container {
                padding: 0 var(--spacing-md);
                margin: var(--spacing-xl) auto;
            }
            .wishlist-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .wishlist-actions {
                width: 100%;
                justify-content: space-between;
            }
            .wishlist-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
            }
            .wishlist-title h1 {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 480px) {
            .wishlist-actions {
                flex-direction: column;
            }
            .clear-wishlist-btn, .add-all-btn {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="wishlist-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <span><i class="fas fa-chevron-right"></i></span>
            <span class="current">My Wishlist</span>
        </div>

        <!-- Wishlist Header -->
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
            <!-- Empty Wishlist -->
            <div class="empty-wishlist">
                <i class="far fa-heart"></i>
                <h2>Your wishlist is empty</h2>
                <p>Save your favorite items here to buy them later</p>
                <a href="index.php" class="shop-now-btn">
                    <i class="fas fa-shopping-cart"></i> Start Shopping
                </a>
            </div>
        <?php else: ?>
            <!-- Wishlist Grid -->
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
                            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <?php if($discount > 0): ?>
                                <div class="img-discount">-<?php echo $discount; ?>%</div>
                            <?php endif; ?>
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
                            
                            <div class="rating">
                                <?php 
                                $rating = $product['rating'] ?? 0;
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

<?php include_once 'includes/footer.php'; ?>

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
    
    const button = event?.target?.closest('.add-to-cart-wishlist');
    if(button) {
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    }
    
    fetch('process/add_to_cart.php?product_id=' + productId + '&quantity=' + quantity, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(button) {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
        }
        
        if(data.success) {
            showNotification('Product added to cart!', 'success');
            if(typeof window.updateCartBadge === 'function') {
                window.updateCartBadge();
            }
        } else {
            showNotification(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        if(button) {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
        }
        console.error('Error:', error);
        showNotification('Error adding to cart', 'error');
    });
}

// Remove from wishlist (using AJAX)
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
                if(wishlistItem) {
                    wishlistItem.style.transition = 'all 0.3s';
                    wishlistItem.style.opacity = '0';
                    wishlistItem.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        wishlistItem.remove();
                        showNotification('Item removed from wishlist', 'success');
                        
                        // Update count
                        const remainingItems = document.querySelectorAll('.wishlist-card').length;
                        const countSpan = document.querySelector('.wishlist-title span');
                        if(countSpan) {
                            countSpan.textContent = remainingItems + ' items';
                        }
                        
                        // Update wishlist badge
                        if(typeof window.updateWishlistBadge === 'function') {
                            window.updateWishlistBadge();
                        } else if(data.wishlist_count !== undefined) {
                            const badge = document.getElementById('wishlistBadge');
                            if(badge) badge.textContent = data.wishlist_count;
                        }
                        
                        if(remainingItems === 0) {
                            location.reload();
                        }
                    }, 300);
                }
            } else {
                showNotification(data.message || 'Error removing item', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error removing item', 'error');
        });
    }
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
    // Update badges
    if(typeof window.updateCartBadge === 'function') {
        window.updateCartBadge();
    }
    if(typeof window.updateWishlistBadge === 'function') {
        window.updateWishlistBadge();
    }
});
</script>

</body>
</html>