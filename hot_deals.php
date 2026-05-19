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
        /* ============================================
           DEALS HEADER
        ============================================ */
        .deals-header {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #c2410c 100%);
            color: var(--white);
            padding: var(--spacing-3xl) 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .deals-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        .deals-header::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,107,107,0.1) 0%, transparent 70%);
            animation: rotate 30s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .deals-header h1 {
            font-size: 3rem;
            margin-bottom: var(--spacing-md);
            position: relative;
            z-index: 1;
            animation: fadeInDown 0.6s ease;
        }

        .deals-header h1 i {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .deals-header p {
            font-size: 1.2rem;
            opacity: 0.95;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Global Timer */
        .global-timer-wrapper {
            display: inline-flex;
            margin-top: var(--spacing-xl);
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: var(--spacing-md) var(--spacing-xl);
            border-radius: var(--radius-2xl);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 1;
        }

        .global-timer-wrapper .timer-digits {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 5px;
            font-family: monospace;
        }

        .global-timer-wrapper .timer-label {
            font-size: 0.7rem;
            opacity: 0.8;
            margin-top: 4px;
        }

        /* ============================================
           FILTER BAR
        ============================================ */
        .filter-bar {
            background: var(--white);
            padding: var(--spacing-md) 0;
            margin-bottom: var(--spacing-xl);
            border-bottom: 1px solid var(--gray-200);
            position: sticky;
            top: 0;
            z-index: 99;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .filter-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: var(--spacing-md);
        }

        .discount-filter {
            display: flex;
            gap: var(--spacing-sm);
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 20px;
            border: 2px solid var(--gray-200);
            background: var(--white);
            border-radius: var(--radius-full);
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            font-size: 0.85rem;
        }

        .filter-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }

        .filter-btn.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }

        .sort-select {
            padding: 8px 20px;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-full);
            background: var(--white);
            font-family: inherit;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .sort-select:focus {
            outline: none;
            border-color: var(--primary);
        }

        /* ============================================
           PRODUCT GRID
        ============================================ */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: var(--spacing-xl);
            padding: var(--spacing-xl) 0;
        }

        /* Hot Deal Card */
        .hot-deal-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            transition: var(--transition);
            position: relative;
            cursor: pointer;
        }

        .hot-deal-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
            box-shadow: var(--shadow-xl);
        }

        .p-img {
            height: 240px;
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid var(--gray-200);
        }

        .p-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition);
        }

        .hot-deal-card:hover .p-img img {
            transform: scale(1.08);
        }

        .p-discount {
            position: absolute;
            top: var(--spacing-md);
            right: var(--spacing-md);
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: var(--white);
            padding: 6px 14px;
            border-radius: var(--radius-full);
            font-size: 0.8rem;
            font-weight: 700;
            z-index: 1;
            box-shadow: var(--shadow-sm);
        }

        .flash-sale-tag {
            position: absolute;
            top: var(--spacing-md);
            left: var(--spacing-md);
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            padding: 5px 12px;
            border-radius: var(--radius-full);
            color: var(--white);
            font-size: 0.7rem;
            font-weight: 700;
            z-index: 1;
            box-shadow: var(--shadow-sm);
            animation: pulse 2s infinite;
        }

        .flash-sale-tag i {
            margin-right: 4px;
        }

        .p-info {
            padding: var(--spacing-lg);
        }

        .p-title {
            font-size: 0.95rem;
            font-weight: 500;
            height: 44px;
            overflow: hidden;
            margin-bottom: var(--spacing-md);
            color: var(--gray-800);
            line-height: 1.4;
        }

        .p-price {
            margin-bottom: var(--spacing-sm);
        }

        .deal-price {
            color: var(--primary);
            font-size: 1.4rem;
            font-weight: 700;
        }

        .p-old {
            text-decoration: line-through;
            color: var(--gray-400);
            font-size: 0.85rem;
            margin-left: var(--spacing-sm);
        }

        .you-save {
            font-size: 0.75rem;
            color: var(--success);
            margin: var(--spacing-sm) 0;
            font-weight: 500;
            display: inline-block;
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            padding: 4px 10px;
            border-radius: var(--radius-full);
        }

        .stock-warning {
            font-size: 0.75rem;
            color: var(--danger);
            margin: var(--spacing-sm) 0;
            font-weight: 600;
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            padding: 4px 10px;
            border-radius: var(--radius-full);
            display: inline-block;
        }

        .stock-warning i {
            margin-right: 4px;
        }

        .product-timer {
            font-size: 0.75rem;
            color: var(--gray-600);
            margin: var(--spacing-sm) 0;
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            background: var(--gray-100);
            padding: 5px 12px;
            border-radius: var(--radius-full);
        }

        .product-timer i {
            color: var(--primary);
        }

        .countdown {
            font-weight: 600;
            color: var(--primary-dark);
        }

        .p-btn {
            width: 100%;
            margin-top: var(--spacing-md);
            padding: 12px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            border: none;
            border-radius: var(--radius-lg);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .p-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ============================================
           EMPTY STATE
        ============================================ */
        .no-deals {
            text-align: center;
            padding: var(--spacing-3xl);
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            margin: var(--spacing-xl) 0;
        }

        .no-deals i {
            font-size: 5rem;
            color: var(--gray-300);
            margin-bottom: var(--spacing-lg);
        }

        .no-deals h2 {
            font-size: 1.5rem;
            margin-bottom: var(--spacing-md);
            color: var(--gray-800);
        }

        .no-deals p {
            color: var(--gray-500);
            margin-bottom: var(--spacing-xl);
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 1024px) {
            .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .deals-header h1 {
                font-size: 2rem;
            }
            .deals-header p {
                font-size: 1rem;
            }
            .global-timer-wrapper .timer-digits {
                font-size: 1.2rem;
                letter-spacing: 2px;
            }
            .filter-wrapper {
                flex-direction: column;
                align-items: stretch;
            }
            .discount-filter {
                justify-content: center;
            }
            .sort-select {
                width: 100%;
            }
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--spacing-md);
            }
        }

        @media (max-width: 480px) {
            .product-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
            }
            .discount-filter {
                gap: var(--spacing-xs);
            }
            .filter-btn {
                padding: 6px 14px;
                font-size: 0.75rem;
            }
            .p-info {
                padding: var(--spacing-md);
            }
        }
    </style>
</head>
<body>

<!-- DEALS HEADER -->
<div class="deals-header">
    <div class="container">
        <h1><i class="fas fa-bolt"></i> Hot Deals & Flash Sales</h1>
        <p>Limited time offers with up to 70% off - Grab them before they're gone!</p>
        <div class="global-timer-wrapper">
            <!--<div class="text-center">
                <div class="timer-digits">
                    <span id="timerDays">00</span>:<span id="timerHours">00</span>:<span id="timerMinutes">00</span>:<span id="timerSeconds">00</span>
                </div>
                -->
                <div class="timer-label">UNTIL BEST DEALS END</div>
            </div>
        </div>
    </div>
</div>

<!-- FILTER BAR -->
<div class="filter-bar">
    <div class="container">
        <div class="filter-wrapper">
            <div class="discount-filter">
                <button class="filter-btn active" data-discount="all">🎯 All Deals</button>
                <button class="filter-btn" data-discount="20">💰 20%+ Off</button>
                <button class="filter-btn" data-discount="30">🔥 30%+ Off</button>
                <button class="filter-btn" data-discount="50">⚡ 50%+ Off</button>
            </div>
            <select class="sort-select" id="sortSelect">
                <option value="discount">🏆 Biggest Discount</option>
                <option value="price_low">💰 Price: Low to High</option>
                <option value="price_high">💎 Price: High to Low</option>
                <option value="ending">⏰ Ending Soon</option>
            </select>
        </div>
    </div>
</div>

<main>
    <div class="container">
        <?php if($total_deals == 0): ?>
            <div class="no-deals">
                <i class="fas fa-tag"></i>
                <h2>No Active Deals</h2>
                <p>Check back soon for amazing discounts!</p>
                <a href="index.php" class="p-btn" style="display: inline-block; width: auto; padding: 12px 30px; margin-top: 20px;">
                    <i class="fas fa-shopping-bag"></i> Browse Products
                </a>
            </div>
        <?php else: ?>
            <div class="product-grid" id="dealsGrid">
                <?php while($product = mysqli_fetch_assoc($hot_deals_result)): 
                    $display_price = $product['deal_price'] ?: $product['price'];
                    $original_price = $product['old_price'] ?: $product['price'];
                    $discount = $original_price > $display_price ? round((($original_price - $display_price) / $original_price) * 100) : 0;
                    $remaining_qty = isset($product['deal_quantity']) ? ($product['deal_quantity'] - ($product['deal_sold'] ?? 0)) : $product['stock'];
                ?>
                    <div class="product-card hot-deal-card" 
                         data-price="<?php echo $display_price; ?>" 
                         data-discount="<?php echo $discount; ?>" 
                         data-deal-end="<?php echo $product['deal_end_date']; ?>"
                         onclick="location.href='product_details.php?id=<?php echo $product['id']; ?>'">
                        
                        <div class="flash-sale-tag">
                            <i class="fas fa-bolt"></i> FLASH SALE
                        </div>
                        
                        <div class="p-img">
                            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <div class="p-discount">-<?php echo $discount; ?>%</div>
                        </div>
                        
                        <div class="p-info">
                            <div class="p-title"><?php echo htmlspecialchars($product['name']); ?></div>
                            
                            <div class="p-price">
                                <span class="deal-price">৳<?php echo number_format($display_price); ?></span>
                                <span class="p-old">৳<?php echo number_format($original_price); ?></span>
                            </div>
                            
                            <div class="you-save">
                                <i class="fas fa-save"></i> You save: ৳<?php echo number_format($original_price - $display_price); ?>
                            </div>
                            
                            <?php if($remaining_qty < 20 && $remaining_qty > 0): ?>
                            <div class="stock-warning">
                                <i class="fas fa-fire"></i> Only <?php echo $remaining_qty; ?> left! Hurry up!
                            </div>
                            <?php endif; ?>
                            
                            <?php if($product['deal_end_date'] && $product['deal_end_date'] != '0000-00-00 00:00:00'): ?>
                            <div class="product-timer" data-end="<?php echo $product['deal_end_date']; ?>">
                                <i class="fas fa-hourglass-half"></i>
                                <span class="countdown"></span>
                            </div>
                            <?php endif; ?>
                            
                            <button class="p-btn" onclick="event.stopPropagation(); addToCart(<?php echo $product['id']; ?>, 1)">
                                <i class="fas fa-shopping-cart"></i> Grab Deal Now
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
    if(!endDate || endDate === '0000-00-00 00:00:00') return false;
    
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
        } else if(hours > 0) {
            element.innerHTML = `${hours}h ${minutes}m ${seconds}s`;
        } else {
            element.innerHTML = `${minutes}m ${seconds}s`;
        }
    }
    return true;
}

// Global timer for nearest ending deal
function updateGlobalTimer() {
    let nearestEnd = null;
    document.querySelectorAll('[data-deal-end]').forEach(el => {
        const endDate = el.getAttribute('data-deal-end');
        if(endDate && endDate !== '' && endDate !== '0000-00-00 00:00:00') {
            const end = new Date(endDate).getTime();
            const now = new Date().getTime();
            if(end > now && (!nearestEnd || end < nearestEnd)) {
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
        if(endDate && endDate !== '' && endDate !== '0000-00-00 00:00:00') {
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
        if(endDate && endDate !== '' && endDate !== '0000-00-00 00:00:00') {
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
            const dateA = a.dataset.dealEnd && a.dataset.dealEnd !== '0000-00-00 00:00:00' ? new Date(a.dataset.dealEnd).getTime() : Infinity;
            const dateB = b.dataset.dealEnd && b.dataset.dealEnd !== '0000-00-00 00:00:00' ? new Date(b.dataset.dealEnd).getTime() : Infinity;
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
    
    const button = event?.target?.closest('.p-btn');
    if(button) {
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    }
    
    fetch('process/add_to_cart.php?product_id=' + productId + '&quantity=' + quantity, {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if(button) {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-shopping-cart"></i> Grab Deal Now';
        }
        
        if(data.success) {
            showNotification('Product added to cart!', 'success');
            updateCartCount();
        } else {
            showNotification(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if(button) {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-shopping-cart"></i> Grab Deal Now';
        }
        showNotification('Network error', 'error');
    });
}

function updateCartCount() {
    fetch('process/get_cart_count.php')
    .then(response => response.json())
    .then(data => {
        document.querySelectorAll('.badge').forEach(b => b.textContent = data.cartcount || 0);
    });
}

// Show notification function
function showNotification(message, type) {
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notif => notif.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    
    let icon = type === 'success' ? '<i class="fas fa-check-circle"></i> ' : 
               type === 'error' ? '<i class="fas fa-exclamation-circle"></i> ' : 
               '<i class="fas fa-info-circle"></i> ';
    
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
        background: ${type === 'success' ? '#22c55e' : type === 'error' ? '#ef4444' : '#3b82f6'};
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-size: 14px;
        min-width: 250px;
        text-align: center;
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add CSS animations
if(!document.querySelector('#notificationStyles')) {
    const style = document.createElement('style');
    style.id = 'notificationStyles';
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

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    initTimers();
    updateCartCount();
});
</script>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>