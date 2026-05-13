<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';

// Get all hot deals
$hot_deals_query = "SELECT *, 
                    ((old_price - IFNULL(deal_price, price)) / old_price * 100) as discount_percent
                    FROM products 
                    WHERE is_hot_deal = 1 
                    AND (deal_end_date IS NULL OR deal_end_date > NOW())
                    AND stock > 0
                    ORDER BY discount_percent DESC, created_at DESC";
$hot_deals_result = mysqli_query($conn, $hot_deals_query);
$total_deals = mysqli_num_rows($hot_deals_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hot Deals - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .deals-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .deals-header::before {
            content: '🔥';
            font-size: 200px;
            position: absolute;
            opacity: 0.1;
            right: 20px;
            bottom: -50px;
            transform: rotate(-15deg);
        }
        .deals-header h1 {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #ffd700;
        }
        .deals-header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        .filter-bar {
            background: white;
            padding: 15px 0;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .sort-select {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .discount-filter {
            display: flex;
            gap: 10px;
        }
        .discount-filter button {
            padding: 8px 15px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .discount-filter button.active,
        .discount-filter button:hover {
            background: #f97316;
            color: white;
            border-color: #f97316;
        }
        .no-deals {
            text-align: center;
            padding: 80px;
            background: white;
            border-radius: 16px;
        }
        .no-deals i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }
        .flash-sale-tag {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="deals-header">
    <div class="container">
        <h1>🔥 Hot Deals & Flash Sales</h1>
        <p>Limited time offers with up to 70% off - Grab them before they're gone!</p>
        <div id="globalTimer" class="deal-timer" style="display: inline-flex; margin-top: 20px; background: rgba(0,0,0,0.3); padding: 10px 20px; border-radius: 50px;">
            <div style="text-align: center;">
                <div class="timer-digits" style="font-size: 1.8rem;">
                    <span id="timerDays">00</span>:<span id="timerHours">00</span>:<span id="timerMinutes">00</span>:<span id="timerSeconds">00</span>
                </div>
                <div class="timer-label">UNTIL DEALS END</div>
            </div>
        </div>
    </div>
</div>

<div class="filter-bar">
    <div class="container" style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
        <div class="discount-filter">
            <button class="filter-btn active" data-discount="all">All Deals</button>
            <button class="filter-btn" data-discount="20">20%+ Off</button>
            <button class="filter-btn" data-discount="30">30%+ Off</button>
            <button class="filter-btn" data-discount="50">50%+ Off</button>
        </div>
        <select class="sort-select" id="sortSelect">
            <option value="discount">Biggest Discount</option>
            <option value="price_low">Price: Low to High</option>
            <option value="price_high">Price: High to Low</option>
            <option value="ending">Ending Soon</option>
        </select>
    </div>
</div>

<main>
    <div class="container" style="padding: 40px 0;">
        <?php if($total_deals == 0): ?>
            <div class="no-deals">
                <i class="fas fa-tag"></i>
                <h2>No Active Deals</h2>
                <p>Check back soon for amazing discounts!</p>
                <a href="index.php" class="p-btn" style="display: inline-block; width: auto; padding: 12px 30px; margin-top: 20px;">Browse Products</a>
            </div>
        <?php else: ?>
            <div class="product-grid" id="dealsGrid">
                <?php while($product = mysqli_fetch_assoc($hot_deals_result)): 
                    $display_price = $product['deal_price'] ?: $product['price'];
                    $original_price = $product['old_price'] ?: $product['price'];
                    $discount = $original_price > $display_price ? round((($original_price - $display_price) / $original_price) * 100) : 0;
                    $remaining_qty = $product['deal_quantity'] ? ($product['deal_quantity'] - $product['deal_sold']) : $product['stock'];
                ?>
                    <div class="product-card hot-deal-card" data-price="<?php echo $display_price; ?>" data-discount="<?php echo $discount; ?>" data-deal-end="<?php echo $product['deal_end_date']; ?>">
                        <div class="flash-sale-tag">
                            <i class="fas fa-bolt"></i> FLASH SALE
                        </div>
                        <div class="p-img" onclick="location.href='product_details.php?id=<?php echo $product['id']; ?>'">
                            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                            <div class="p-discount">-<?php echo $discount; ?>%</div>
                        </div>
                        <div class="p-info">
                            <div class="p-title"><?php echo htmlspecialchars($product['name']); ?></div>
                            <div class="p-price">
                                <span class="deal-price">৳<?php echo number_format($display_price); ?></span>
                                <span class="p-old">৳<?php echo number_format($original_price); ?></span>
                            </div>
                            <div class="you-save">You save: ৳<?php echo number_format($original_price - $display_price); ?></div>
                            
                            <?php if($remaining_qty < 20): ?>
                            <div class="stock-warning">
                                <i class="fas fa-fire"></i> Only <?php echo $remaining_qty; ?> left!
                            </div>
                            <?php endif; ?>
                            
                            <?php if($product['deal_end_date']): ?>
                            <div class="product-timer" data-end="<?php echo $product['deal_end_date']; ?>">
                                <i class="fas fa-hourglass-half"></i>
                                <span class="countdown"></span>
                            </div>
                            <?php endif; ?>
                            
                            <button class="p-btn" onclick="event.stopPropagation(); addToCart(<?php echo $product['id']; ?>, 1)">
                                <i class="fas fa-shopping-cart"></i> Grab Deal
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
// Countdown Timer Function
function updateCountdown(endDate, element) {
    const now = new Date().getTime();
    const end = new Date(endDate).getTime();
    const distance = end - now;
    
    if(distance < 0) {
        if(element) element.innerHTML = 'Expired';
        return false;
    }
    
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    if(element) {
        if(days > 0) {
            element.innerHTML = `${days}d ${hours}h ${minutes}m`;
        } else {
            element.innerHTML = `${hours}h ${minutes}m ${seconds}s`;
        }
    }
    return true;
}

// Global timer for nearest ending deal
function updateGlobalTimer() {
    let nearestEnd = null;
    document.querySelectorAll('[data-deal-end]').forEach(el => {
        const endDate = el.getAttribute('data-deal-end');
        if(endDate && endDate !== '') {
            const end = new Date(endDate).getTime();
            if(!nearestEnd || end < nearestEnd) {
                nearestEnd = end;
            }
        }
    });
    
    if(nearestEnd) {
        const now = new Date().getTime();
        const distance = nearestEnd - now;
        
        if(distance > 0) {
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById('timerDays').textContent = String(days).padStart(2, '0');
            document.getElementById('timerHours').textContent = String(hours).padStart(2, '0');
            document.getElementById('timerMinutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('timerSeconds').textContent = String(seconds).padStart(2, '0');
        }
    }
}

// Initialize all timers
function initTimers() {
    document.querySelectorAll('.product-timer[data-end]').forEach(el => {
        const endDate = el.getAttribute('data-end');
        const countdownSpan = el.querySelector('.countdown');
        if(endDate && endDate !== '') {
            updateCountdown(endDate, countdownSpan);
        }
    });
    updateGlobalTimer();
}

// Update timers every second
setInterval(() => {
    document.querySelectorAll('.product-timer[data-end]').forEach(el => {
        const endDate = el.getAttribute('data-end');
        const countdownSpan = el.querySelector('.countdown');
        if(endDate && endDate !== '') {
            updateCountdown(endDate, countdownSpan);
        }
    });
    updateGlobalTimer();
}, 1000);

// Filter by discount
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const minDiscount = this.getAttribute('data-discount');
        const cards = document.querySelectorAll('.hot-deal-card');
        
        cards.forEach(card => {
            const discount = parseInt(card.getAttribute('data-discount'));
            if(minDiscount === 'all' || discount >= parseInt(minDiscount)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

// Sort functionality
document.getElementById('sortSelect')?.addEventListener('change', function() {
    const grid = document.getElementById('dealsGrid');
    const cards = Array.from(grid.children);
    const sortBy = this.value;
    
    cards.sort((a, b) => {
        if(sortBy === 'price_low') {
            return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
        } else if(sortBy === 'price_high') {
            return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
        } else if(sortBy === 'discount') {
            return parseInt(b.dataset.discount) - parseInt(a.dataset.discount);
        } else if(sortBy === 'ending') {
            const dateA = a.dataset.dealEnd ? new Date(a.dataset.dealEnd).getTime() : Infinity;
            const dateB = b.dataset.dealEnd ? new Date(b.dataset.dealEnd).getTime() : Infinity;
            return dateA - dateB;
        }
        return 0;
    });
    
    cards.forEach(card => grid.appendChild(card));
});

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
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Product added to cart!');
            updateCartCount();
        } else {
            alert(data.message || 'Error adding to cart');
        }
    });
}

function updateCartCount() {
    fetch('process/get_cart_count.php')
    .then(response => response.json())
    .then(data => {
        document.querySelectorAll('.badge').forEach(b => b.textContent = data.count);
    });
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    initTimers();
    updateCartCount();
});
</script>

<style>
.you-save {
    font-size: 0.7rem;
    color: #22c55e;
    margin: 5px 0;
}
.stock-warning {
    font-size: 0.7rem;
    color: #ef4444;
    margin: 5px 0;
    font-weight: bold;
}
.flash-sale-tag {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 10;
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    padding: 4px 12px;
    border-radius: 20px;
    color: white;
    font-size: 0.7rem;
    font-weight: bold;
}
</style>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>