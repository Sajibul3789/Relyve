<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'config/db_connect.php';

// Get cart and wishlist counts for logged in user
$cart_count = 0;
$wishlist_count = 0;

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Get cart count
    $cart_result = mysqli_query($conn, "SELECT COALESCE(SUM(quantity), 0) as count FROM cart WHERE user_id = $user_id");
    if($cart_result) {
        $cart_row = mysqli_fetch_assoc($cart_result);
        $cart_count = (int)$cart_row['count'];
    }
    
    // Get wishlist count
    $wishlist_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM wishlist WHERE user_id = $user_id");
    if($wishlist_result) {
        $wishlist_row = mysqli_fetch_assoc($wishlist_result);
        $wishlist_count = (int)$wishlist_row['count'];
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/navbar.css">

<header>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container flex-row">
            <div><i class="fas fa-truck"></i> Free Delivery Over ৳5000</div>
            <div style="display:flex; gap:1rem;">
                <a href="track-order.php" style="color:white; text-decoration:none;">Track Order</a>
                <a href="support.php" style="color:white; text-decoration:none;">Support</a>
            </div>
        </div>
    </div>
    
    <!-- Main Navigation Bar - Made Sticky -->
    <div class="main-nav" style="position: sticky; top: 0; z-index: 1000; background: white; box-shadow: 0 3px 5px #00000036; padding: 0.75rem 0;">
        <div class="container flex-row">
            <a href="index.php" class="logo">
                <div class="logo-box">R</div>
                <div>
                    <b style="font-size:1.5rem; display:block">Relyve</b>
                    <small style="color:#999; display:block; margin-top:-0.25rem">.com</small>
                </div>
            </a>
            
            <nav class="hidden-mobile">
                <a href="index.php">Home</a>
                <a href="category.php?cat=smartphones">Smartphones</a>
                <a href="category.php?cat=laptops">Laptops</a>
                <a href="category.php?cat=tablets">Tablets</a>
                <a href="hot_deals.php" class="hot-deal">Hot Deals 🔥</a>
            </nav>
            
            <div class="nav-icons">
                <a href="wishlist.php" class="icon-btn">
                    <i class="fas fa-heart"></i>
                    <span class="wishlistBadge" id="wishlistBadge"><?php echo $wishlist_count; ?></span>
                    <span class="hide-sm">Wishlist</span>
                </a>
                <a href="cart.php" class="icon-btn">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cartBadge" id="cartBadge"><?php echo $cart_count; ?></span>
                    <span class="hide-sm">Cart</span>
                </a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="account.php" class="icon-btn">
                        <i class="fas fa-user"></i>
                        <span class="hide-sm">Account</span>
                    </a>
                    <a href="logout.php" class="icon-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="hide-sm">Logout</span>
                    </a>
                <?php else: ?>
                    <a href="login_form.php" class="icon-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        <span class="hide-sm">Login</span>
                    </a>
                    <a href="register_form.php" class="icon-btn">
                        <i class="fas fa-user-plus"></i>
                        <span class="hide-sm">Register</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<script>
// Function to update cart count badge
function updateCartBadge() {
    fetch('process/get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('cartBadge');
            if(badge) {
                badge.textContent = data.cartcount || 0;
            }
        })
        .catch(error => console.error('Error fetching cart count:', error));
}

// Function to update wishlist count badge
function updateWishlistBadge() {
    fetch('process/get_wishlist_count.php')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('wishlistBadge');
            if(badge) {
                badge.textContent = data.wishlistcount || 0;
            }
        })
        .catch(error => console.error('Error fetching wishlist count:', error));
}

// Update on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartBadge();
    updateWishlistBadge();
});

// Expose functions globally
window.updateCartBadge = updateCartBadge;
window.updateWishlistBadge = updateWishlistBadge;
</script>

<style>
.wishlistBadge, .cartBadge {
    position: absolute;
    top: -6px;
    left: -12px;
    background: #f97316;
    color: white;
    font-size: 10px;
    min-width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    padding: 0 4px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.2);
    transition: transform 0.1s ease;
}

.icon-btn {
    position: relative;
    text-decoration: none;
    color: #374151;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: color 0.2s ease;
    padding: 4px 0;
}

.icon-btn:hover {
    color: #f97316;
}

.icon-btn:hover i {
    transform: scale(1.05);
}

.icon-btn i {
    transition: transform 0.2s ease;
    font-size: 1rem;
}

nav a {
    text-decoration: none;
    color: #374151;
    font-weight: 500;
    transition: color 0.2s ease;
    padding: 4px 0;
    margin: 0 12px;
}

nav a:hover {
    color: #f97316;
}

.hot-deal {
    background: rgba(249, 115, 22, 0.1);
    padding: 6px 14px;
    border-radius: 20px;
    margin: 0 0 0 12px;
}

.hot-deal:hover {
    background: rgba(249, 115, 22, 0.2);
}

.logo {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}

.logo-box {
    width: 40px;
    height: 40px;
    background: #f97316;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
}

.top-bar {
    background: var(--primary);
    color: white;
    padding: 8px 0;
    font-size: 0.8rem;
}

@media (max-width: 768px) {
    .hide-sm {
        display: none;
    }
    nav a {
        margin: 0 8px;
    }
}
</style>