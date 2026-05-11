<?php 
session_start();
include 'includes/navbar.php'; 
include 'config/db_connect.php';

// Queries
$flash_deals_result = mysqli_query($conn, "SELECT * FROM products ORDER BY created_at DESC LIMIT 5");
$featured_result = mysqli_query($conn, "SELECT * FROM products ORDER BY rating DESC LIMIT 5");

// Get active hero sections
$hero_result = mysqli_query($conn, "SELECT * FROM hero_section WHERE is_active = 1 ORDER BY display_order LIMIT 1");
$hero = mysqli_fetch_assoc($hero_result);

// If no hero found in database, show default
if(!$hero) {
    $hero = [
        'title' => 'Samsung Galaxy<br>S25 Ultra',
        'subtitle' => 'Titanium design • AI Camera • 5000mAh Battery',
        'button_text' => 'Buy Now',
        'button_link' => 'product_details.php?id=1',
        'image_url' => '#',
        'tag_text' => 'NEW ARRIVALS 2026'
    ];
}

// Product card function
function display_product($product) {
?>
<div class="product-card" onclick="location.href='product_details.php?id=<?php echo $product['id']; ?>'">
    <button class="wishlist-btn-card" 
        onclick="event.stopPropagation(); location.href='<?php echo isset($_SESSION['user_id']) ? 'process/add_to_wishlist.php?id='.$product['id'] : 'login_form.php'; ?>'">
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
    <link rel="stylesheet" href="assets/css/index.css">
    <style>

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
<section class="hero" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('<?php echo $hero['image_url']; ?>') center/cover;">
    <div class="container">
        <span class="hero-tag"><?php echo htmlspecialchars($hero['tag_text']); ?></span>
        <h1><?php echo $hero['title']; ?></h1>
        <p><?php echo htmlspecialchars($hero['subtitle']); ?></p>
        
        <?php 
        // Determine button link
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

<!-- FLASH DEALS -->
<section class="section" style="background:#fef2f2">
    <div class="container">
        <div class="section-header">
            <h2>Flash Deals</h2>
            <a href="hot_deals.php" style="color:var(--primary); text-decoration:none; font-weight:600">View All →</a>
        </div>
        <div class="product-grid">
            <?php while($product = mysqli_fetch_assoc($flash_deals_result)): ?>
                <?php display_product($product); ?>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- FEATURED -->
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

<?php include 'includes/footer.php'; ?>

<script>
document.getElementById('sInput').addEventListener('keypress', function(e) {
    if(e.key === 'Enter') {
        location.href='search.php?q=' + this.value;
    }
});
</script>

<script>
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
    
    console.log('Sending request for product:', productId, 'quantity:', quantity);
    
    fetch('process/add_to_cart.php?product_id=' + productId + '&quantity=' + quantity, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        // Re-enable button
        if(clickedButton) {
            clickedButton.disabled = false;
            clickedButton.innerHTML = 'Add to Cart';
        }
        
        if(data.success) {
            let message = '';
            if(data.action === 'updated') {
                message = `✓ ${data.product_name || 'Product'} quantity updated!`;
            } else {
                message = `✓ ${data.message || 'Product added to cart!'}`;
            }
            showNotification(message, 'success');
            updateCartCount();
        } else {
            let errorMsg = data.message || 'Error adding to cart';
            if(data.debug) {
                console.error('Debug info:', data.debug);
                errorMsg += ' (Check console for details)';
            }
            showNotification(errorMsg, 'error');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        if(clickedButton) {
            clickedButton.disabled = false;
            clickedButton.innerHTML = 'Add to Cart';
        }
        showNotification('Network error: Could not connect to server', 'error');
    });
}

function showNotification(message, type) {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notif => notif.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    
    let icon = '';
    let bgColor = '';
    
    if(type === 'success') {
        icon = '<i class="fas fa-check-circle"></i> ';
        bgColor = '#22c55e';
    } else if(type === 'info') {
        icon = '<i class="fas fa-info-circle"></i> ';
        bgColor = '#3b82f6';
    } else {
        icon = '<i class="fas fa-exclamation-circle"></i> ';
        bgColor = '#ef4444';
    }
    
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
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

function updateCartCount() {
    fetch('process/get_cart_count.php')
    .then(response => response.json())
    .then(data => {
        const badges = document.querySelectorAll('.badge');
        badges.forEach(badge => {
            if(data.count && data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'flex';
            } else {
                badge.textContent = '0';
            }
        });
    })
    .catch(error => {
        console.error('Error fetching cart count:', error);
    });
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
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
    updateCartCount();
});

document.getElementById('sInput').addEventListener('keypress', function(e) {
    if(e.key === 'Enter') {
        location.href='search.php?q=' + this.value;
    }
});
</script>

</body>
</html>