<?php 
session_start();
include_once 'includes/navbar.php'; 
include_once 'config/db_connect.php';

// Queries
$flash_deals_result = mysqli_query($conn, "SELECT * FROM products ORDER BY created_at DESC LIMIT 4");
$featured_result = mysqli_query($conn, "SELECT * FROM products ORDER BY rating DESC LIMIT 5");

// Get active hero sections
$hero_result = mysqli_query($conn, "SELECT * FROM hero_section WHERE is_active = 1 ORDER BY display_order LIMIT 1");
$hero = mysqli_fetch_assoc($hero_result);

// Product card function
function display_product($product) {
?>
<div class="product-card" onclick="location.href='product_details.php?id=<?php echo $product['id']; ?>'">
    <button class="wishlist-btn-card" 
        onclick="event.stopPropagation(); addToWishlist(<?php echo $product['id']; ?>)">
        <i class="far fa-heart"></i>
    </button>
    <div class="p-img">
        <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
        <?php if($product['old_price']): ?>
            <div class="p-discount">
                -<?php echo round((($product['old_price'] - $product['price']) / $product['old_price']) * 100); ?>%
            </div>
        <?php endif; ?>
    </div>
    <div class="p-info">
        <div class="p-title"><?php echo htmlspecialchars($product['name']); ?></div>
        <div class="p-price">
            ৳<?php echo number_format($product['price']); ?>
            <?php if($product['old_price']): ?>
                <span class="p-old">৳<?php echo number_format($product['old_price']); ?></span>
            <?php endif; ?>
        </div>
        <div class="rating" style="margin: 8px 0; font-size:0.8rem">
            <?php 
            $rating = $product['rating'] ?? 0;
            for($i = 1; $i <= 5; $i++):
                if($i <= floor($rating)):
                    echo '<i class="fas fa-star" style="color:#fbbf24"></i>';
                elseif($i - $rating <= 0.5):
                    echo '<i class="fas fa-star-half-alt" style="color:#fbbf24"></i>';
                else:
                    echo '<i class="far fa-star" style="color:#fbbf24"></i>';
                endif;
            endfor;
            ?>
        </div>
        <button class="p-btn" 
            onclick="event.stopPropagation(); addToCart(<?php echo $product['id']; ?>, 1)">
            Add to Cart
        </button>
    </div>
</div>
<?php
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relyve - Online Shopping Bangladesh</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Search Section */
        .search-area {
            padding: 15px 0;
            background: var(--white);
            border-bottom: 1px solid #eee;
        }

        .search-container {
            max-width: 850px;
            margin: 0 auto;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 14px 25px 14px 55px;
            border-radius: 35px;
            border: 1.5px solid #cecece;
            outline: none;
            font-size: 1rem;
            transition: var(--transition);
        }

        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 22px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1.3rem;
        }

        .search-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary);
            color: white;
            padding: 10px 35px;
            border-radius: 30px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .search-btn:hover {
            background: var(--primary-dark);
        }

        /* Hero Section */
        .hero {
            height: 520px;
            display: flex;
            align-items: center;
            color: white;
        }

        .hero-tag {
            background: var(--primary);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.75rem;
            margin-bottom: 15px;
            display: inline-block;
        }

        .hero h1 {
            font-size: 3.8rem;
            margin-bottom: 10px;
            font-weight: 800;
            line-height: 1.1;
            color: var(--white);
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
            color: var(--white);
        }

        .hero-btn {
            background: var(--primary);
            color: white;
            border: none;
            transition: var(--transition);
            cursor: pointer;
        }

        .hero-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Categories Section */
        .cat-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 40px;
        }

        @media (min-width: 768px) {
            .cat-grid {
                grid-template-columns: repeat(6, 1fr);
            }
        }

        .cat-card {
            background: white;
            border-radius: 25px;
            border: 0.25rem solid #e7e7e7;
            overflow: hidden;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: var(--shadow-md);
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .cat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .cat-img {
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
        }

        .cat-blue { background: #eff6ff; color: #3b82f6; }
        .cat-slate { background: #f1f5f9; color: #475569; }

        .cat-card h3 {
            padding: 15px;
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0;
        }

        .category-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        /* Product Grid & Cards */
        .section {
            padding: 80px 0;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 35px;
        }

        .section-header h2 {
            margin-bottom: 0;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (min-width: 1024px) {
            .product-grid {
                grid-template-columns: repeat(5, 1fr);
            }
        }

        .product-card {
            background: white;
            border-radius: 25px;
            border: 0.25rem solid #e7e7e7;
            overflow: hidden;
            transition: var(--transition);
            position: relative;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .p-img {
            height: 220px;
            background: #f8f8f8;
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
            transform: scale(1.05);
        }

        .p-discount {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #ef4444;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 700;
            z-index: 1;
        }

        .wishlist-btn-card {
            position: absolute;
            top: 10px;
            right: 10px;
            background: white;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: var(--transition);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            color: var(--gray-500);
        }

        .wishlist-btn-card:hover {
            background: var(--danger);
            color: white;
            transform: scale(1.1);
        }

        .p-info {
            padding: 20px;
        }

        .p-title {
            font-size: 0.95rem;
            font-weight: 500;
            height: 42px;
            overflow: hidden;
            margin-bottom: 12px;
            color: var(--gray-800);
        }

        .p-price {
            color: var(--primary);
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .p-old {
            text-decoration: line-through;
            color: #aaa;
            font-size: 0.8rem;
            margin-left: 8px;
        }

        .rating {
            margin: 8px 0;
            font-size: 0.8rem;
        }

        .rating i {
            color: #fbbf24;
        }

        .p-btn {
            width: 100%;
            margin-top: 15px;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .p-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .p-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Hot Deals Badge */
        .hot-deal-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: linear-gradient(135deg, #f97316, #ef4444);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: bold;
            z-index: 10;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
        }

        /* Hot Price Styling */
        .hot-price {
            color: #f97316;
            font-size: 1.4rem;
            font-weight: 700;
        }

        /* Deal Timer Small */
        .deal-timer-small {
            font-size: 0.7rem;
            color: #666;
            margin: 5px 0;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .deal-timer-small i {
            color: #f97316;
        }

        .countdown-text {
            font-weight: 500;
            color: #ef4444;
        }

        /* Product Card with Hot Deal Border Effect */
        .product-card[data-deal-end] {
            position: relative;
            overflow: hidden;
        }

        .product-card[data-deal-end]::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #f97316, transparent);
            animation: shimmer 2s infinite;
            z-index: 5;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Badge Styling */
        .badge {
            position: absolute;
            top: -8px;
            right: -8px;
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
        }

        /* Notification */
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            z-index: 10000;
            animation: slideIn 0.3s ease;
            background: var(--success);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            font-size: 14px;
            min-width: 250px;
            text-align: center;
            font-family: var(--font-family);
        }

        .notification.success { background: #22c55e; }
        .notification.error { background: #ef4444; }
        .notification.info { background: #3b82f6; }

        /* Mobile Responsive Overrides */
        @media (max-width: 768px) {
            .hero {
                height: 400px;
            }
            .hero h1 {
                font-size: 2rem;
            }
            .hero p {
                font-size: 1rem;
            }
            .section {
                padding: 40px 0;
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 1.5rem;
            }
            .hero-btn {
                padding: 12px 25px !important;
                font-size: 0.9rem !important;
            }
        }
    </style>
</head>
<body>

<!-- SEARCH -->
<div class="search-area">
    <div class="container">
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="sInput" class="search-input" placeholder="Search smartphones, laptops, accessories...">
            <button class="search-btn" onclick="window.location.href='search.php?q='+document.getElementById('sInput').value">
                Search
            </button>
        </div>
    </div>
</div>

<main>

<!-- HERO SECTION - DYNAMIC FROM DATABASE -->
<?php if($hero): ?>
<section class="hero" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('<?php echo $hero['image_url']; ?>') center/cover;">
    <div class="container">
        <span class="hero-tag"><?php echo htmlspecialchars($hero['tag_text']); ?></span>
        <h1><?php echo $hero['title']; ?></h1>
        <p><?php echo htmlspecialchars($hero['subtitle']); ?></p>
        
        <?php 
        $button_link = $hero['button_link'] ?? '';
        if(empty($button_link) && !empty($hero['product_id'])) {
            $button_link = "product_details.php?id=" . $hero['product_id'];
        } elseif(empty($button_link)) {
            $button_link = "all_products.php";
        }
        ?>
        
        <button class="hero-btn p-btn"
            style="width:auto; padding:18px 40px; border-radius:18px; font-size:1.1rem"
            onclick="location.href='<?php echo $button_link; ?>'">
            <?php echo htmlspecialchars($hero['button_text']); ?>
        </button>
    </div>
</section>
<?php endif; ?>

<!-- SHOP BY CATEGORY -->
<section class="section container">
    <h2 style="text-align:center; margin-bottom:40px; font-size:2rem">Shop by Category</h2>
    <div class="cat-grid">
        <a href="category.php?cat=smartphones" class="category-link"><div class="cat-card"><div class="cat-img cat-blue"><i class="fas fa-mobile-alt"></i></div><h3>Smartphones</h3></div></a>
        <a href="category.php?cat=laptops" class="category-link"><div class="cat-card"><div class="cat-img cat-slate"><i class="fas fa-laptop"></i></div><h3>Laptops</h3></div></a>
        <a href="category.php?cat=tablets" class="category-link"><div class="cat-card"><div class="cat-img" style="background:#fff7ed; color:#f97316"><i class="fas fa-tablet-alt"></i></div><h3>Tablets</h3></div></a>
        <a href="category.php?cat=accessories" class="category-link"><div class="cat-card"><div class="cat-img" style="background:#f0fdf4; color:#22c55e"><i class="fas fa-headphones"></i></div><h3>Accessories</h3></div></a>
        <a href="category.php?cat=tv_audio" class="category-link"><div class="cat-card"><div class="cat-img" style="background:#faf5ff; color:#a855f7"><i class="fas fa-tv"></i></div><h3>TV & Audio</h3></div></a>
        <a href="category.php?cat=watches" class="category-link"><div class="cat-card"><div class="cat-img" style="background:#fef2f2; color:#ef4444"><i class="fas fa-clock"></i></div><h3>Watches</h3></div></a>
    </div>
</section>

<!-- HOT DEALS SECTION -->
<section class="section" style="background:#fef2f2">
    <div class="container">
        <div class="section-header">
            <h2>🔥 Hot Deals</h2>
            <a href="hot_deals.php" style="color:var(--primary); text-decoration:none; font-weight:600">View All →</a>
        </div>
        <div class="product-grid">
            <?php 
            // Check if hot deals columns exist, if not use regular products with discounts
            $hot_deals_result = null;
            $column_check = mysqli_query($conn, "SHOW COLUMNS FROM products LIKE 'is_hot_deal'");
            if(mysqli_num_rows($column_check) > 0) {
                $hot_deals_query = "SELECT *, 
                                    ((old_price - IFNULL(deal_price, price)) / old_price * 100) as discount_percent
                                    FROM products 
                                    WHERE is_hot_deal = 1 
                                    AND (deal_end_date IS NULL OR deal_end_date > NOW())
                                    AND stock > 0
                                    ORDER BY discount_percent DESC, created_at DESC 
                                    LIMIT 8";
                $hot_deals_result = mysqli_query($conn, $hot_deals_query);
            }
            
            // If no hot deals found or columns don't exist, show products with discounts
            if(!$hot_deals_result || mysqli_num_rows($hot_deals_result) == 0) {
                $hot_deals_query = "SELECT *, 
                                    ((old_price - price) / old_price * 100) as discount_percent
                                    FROM products 
                                    WHERE old_price IS NOT NULL 
                                    AND old_price > price 
                                    AND stock > 0
                                    ORDER BY discount_percent DESC, created_at DESC 
                                    LIMIT 8";
                $hot_deals_result = mysqli_query($conn, $hot_deals_query);
            }
            
            while($product = mysqli_fetch_assoc($hot_deals_result)): 
                $display_price = (isset($product['deal_price']) && $product['deal_price']) ? $product['deal_price'] : $product['price'];
                $original_price = $product['old_price'] ?: $product['price'];
                $discount = $original_price > $display_price ? round((($original_price - $display_price) / $original_price) * 100) : 0;
            ?>
                <div class="product-card" <?php echo isset($product['deal_end_date']) && $product['deal_end_date'] ? 'data-deal-end="'.$product['deal_end_date'].'"' : ''; ?>>
                    <div class="hot-deal-badge">🔥 -<?php echo $discount; ?>%</div>
                    <div class="p-img" onclick="location.href='product_details.php?id=<?php echo $product['id']; ?>'">
                        <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                        <?php if($product['old_price']): ?>
                            <div class="p-discount">
                                -<?php echo round((($product['old_price'] - $product['price']) / $product['old_price']) * 100); ?>%
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-info">
                        <div class="p-title"><?php echo htmlspecialchars($product['name']); ?></div>
                        <div class="p-price">
                            <span class="hot-price">৳<?php echo number_format($display_price); ?></span>
                            <?php if($original_price > $display_price): ?>
                                <span class="p-old">৳<?php echo number_format($original_price); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if(isset($product['deal_end_date']) && $product['deal_end_date']): ?>
                            <div class="deal-timer-small" data-end="<?php echo $product['deal_end_date']; ?>">
                                <i class="fas fa-clock"></i> 
                                <span class="countdown-text"></span>
                            </div>
                        <?php endif; ?>
                        <div class="rating" style="margin: 8px 0; font-size:0.8rem">
                            <?php 
                            $rating = $product['rating'] ?? 0;
                            for($i = 1; $i <= 5; $i++):
                                if($i <= floor($rating)):
                                    echo '<i class="fas fa-star" style="color:#fbbf24"></i>';
                                elseif($i - $rating <= 0.5):
                                    echo '<i class="fas fa-star-half-alt" style="color:#fbbf24"></i>';
                                else:
                                    echo '<i class="far fa-star" style="color:#fbbf24"></i>';
                                endif;
                            endfor;
                            ?>
                        </div>
                        <button class="p-btn" onclick="event.stopPropagation(); addToCart(<?php echo $product['id']; ?>, 1)">
                            Add to Cart
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- FEATURED PRODUCTS -->
<section class="section container">
    <div class="section-header">
        <h2>Featured Products</h2>
        <a href="all_products.php" style="color:var(--primary); text-decoration:none; font-weight:600">View All →</a>
    </div>
    <div class="product-grid">
        <?php while($featured = mysqli_fetch_assoc($featured_result)): ?>
            <?php display_product($featured); ?>
        <?php endwhile; ?>
    </div>
</section>

</main>

<?php include_once 'includes/footer.php'; ?>

<script>
// Countdown Timer Function for Hot Deals
function updateDealTimers() {
    document.querySelectorAll('[data-end]').forEach(el => {
        const endDate = el.getAttribute('data-end');
        if(endDate && endDate !== '') {
            const now = new Date().getTime();
            const end = new Date(endDate).getTime();
            const distance = end - now;
            
            if(distance > 0) {
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                const timerSpan = el.querySelector('.countdown-text');
                if(timerSpan) {
                    if(hours > 0) {
                        timerSpan.innerHTML = `${hours}h ${minutes}m left`;
                    } else if(minutes > 0) {
                        timerSpan.innerHTML = `${minutes}m ${seconds}s left`;
                    } else {
                        timerSpan.innerHTML = `${seconds}s left`;
                    }
                }
            } else {
                const timerSpan = el.querySelector('.countdown-text');
                if(timerSpan) timerSpan.innerHTML = 'Expired';
            }
        }
    });
}

// Update timers every second
setInterval(updateDealTimers, 1000);
updateDealTimers();

// Search input enter key
document.getElementById('sInput').addEventListener('keypress', function(e) {
    if(e.key === 'Enter') {
        location.href='search.php?q=' + this.value;
    }
});

// ============================================
// CART FUNCTIONS
// ============================================

// Update Cart Badge (matches wishlist pattern)
function updateCartBadge() {
    fetch('process/get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('cartBadge');
            if(badge) {
                badge.textContent = data.cartcount || 0;
                console.log('Cart badge updated to:', data.cartcount);
            }
        })
        .catch(error => console.error('Error fetching cart count:', error));
}

// Add to Cart Function
function addToCart(productId, quantity) {
    <?php if(!isset($_SESSION['user_id'])): ?>
        if(confirm('Please login to add items to cart. Go to login page?')) {
            window.location.href = 'login_form.php';
        }
        return;
    <?php endif; ?>
    
    // Disable button
    const buttons = document.querySelectorAll('.p-btn');
    let clickedButton = null;
    buttons.forEach(btn => {
        if(btn.onclick && btn.onclick.toString().includes('addToCart(' + productId)) {
            clickedButton = btn;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        }
    });
    
    fetch('process/add_to_cart.php?product_id=' + productId + '&quantity=' + quantity, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(clickedButton) {
            clickedButton.disabled = false;
            clickedButton.innerHTML = 'Add to Cart';
        }
        
        if(data.success) {
            let message = data.action === 'updated' ? 'Cart updated!' : 'Product added to cart!';
            showNotification(message, 'success');
            updateCartBadge();  // Update cart badge immediately
        } else {
            showNotification(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if(clickedButton) {
            clickedButton.disabled = false;
            clickedButton.innerHTML = 'Add to Cart';
        }
        showNotification('Network error', 'error');
    });
}

// ============================================
// WISHLIST FUNCTIONS
// ============================================

// Update Wishlist Badge
function updateWishlistBadge() {
    fetch('process/get_wishlist_count.php')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('wishlistBadge');
            if(badge) {
                badge.textContent = data.wishlistcount || 0;
                console.log('Wishlist badge updated to:', data.wishlistcount);
            }
        })
        .catch(error => console.error('Error fetching wishlist count:', error));
}

// Add to Wishlist Function
function addToWishlist(productId) {
    <?php if(!isset($_SESSION['user_id'])): ?>
        if(confirm('Please login to add to wishlist. Go to login page?')) {
            window.location.href = 'login_form.php';
        }
        return;
    <?php endif; ?>
    
    fetch('process/add_to_wishlist.php?id=' + productId)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                showNotification('Added to wishlist!', 'success');
                updateWishlistBadge();  // Update wishlist badge immediately
            } else {
                if(data.message !== 'Already in wishlist') {
                    showNotification(data.message || 'Error adding to wishlist', 'error');
                } else {
                    showNotification('Item already in wishlist', 'info');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error adding to wishlist', 'error');
        });
}

// ============================================
// NOTIFICATION FUNCTIONS
// ============================================

// Show Notification
function showNotification(message, type) {
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notif => notif.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    
    let icon = type === 'success' ? '<i class="fas fa-check-circle"></i> ' : '<i class="fas fa-exclamation-circle"></i> ';
    let bgColor = type === 'success' ? '#22c55e' : '#ef4444';
    
    notification.innerHTML = icon + message;
    notification.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 12px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        animation: slideIn 0.3s ease;
        background: ${bgColor};
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-size: 14px;
        min-width: 250px;
        text-align: center;
        font-family: 'Inter', sans-serif;
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    .p-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    .badge {
        position: absolute;
        top: -8px;
        right: -8px;
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
    }
`;
document.head.appendChild(style);

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateDealTimers();
    updateCartBadge();
    updateWishlistBadge();
});

// Expose functions globally for navbar
window.updateCartBadge = updateCartBadge;
window.updateWishlistBadge = updateWishlistBadge;
</script>

</body>
</html>