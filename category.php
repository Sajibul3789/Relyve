<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';

$category = isset($_GET['cat']) ? mysqli_real_escape_string($conn, $_GET['cat']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 100000;
$products = [];

$category_names = [
    'smartphones' => 'Smartphones',
    'laptops' => 'Laptops',
    'tablets' => 'Tablets',
    'accessories' => 'Accessories',
    'tv_audio' => 'TV & Audio',
    'watches' => 'Watches'
];

$display_name = $category_names[$category] ?? ucfirst($category);
$category_icon = [
    'smartphones' => 'fa-mobile-alt',
    'laptops' => 'fa-laptop',
    'tablets' => 'fa-tablet-alt',
    'accessories' => 'fa-headphones',
    'tv_audio' => 'fa-tv',
    'watches' => 'fa-clock'
];

$icon = $category_icon[$category] ?? 'fa-tag';

// Build query with filters
$where_conditions = ["category = '$category'"];
if($min_price > 0) {
    $where_conditions[] = "price >= $min_price";
}
if($max_price < 100000) {
    $where_conditions[] = "price <= $max_price";
}
$where_clause = implode(" AND ", $where_conditions);

// Sorting
$order_by = "ORDER BY ";
switch($sort) {
    case 'price_low':
        $order_by .= "price ASC";
        break;
    case 'price_high':
        $order_by .= "price DESC";
        break;
    case 'rating':
        $order_by .= "rating DESC";
        break;
    case 'name':
        $order_by .= "name ASC";
        break;
    default:
        $order_by .= "created_at DESC";
}

if($category) {
    $sql = "SELECT * FROM products WHERE $where_clause $order_by";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}

// Get price range for filters
$price_range_sql = "SELECT MIN(price) as min_price, MAX(price) as max_price FROM products WHERE category = '$category'";
$price_range_result = mysqli_query($conn, $price_range_sql);
$price_range = mysqli_fetch_assoc($price_range_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $display_name; ?> - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* ============================================
           CATEGORY HEADER
        ============================================ */
        .category-header {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #c2410c 100%);
            color: var(--white);
            padding: var(--spacing-3xl) 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .category-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .category-header h1 {
            font-size: 2.8rem;
            margin-bottom: var(--spacing-sm);
            position: relative;
            z-index: 1;
            animation: fadeInDown 0.6s ease;
        }

        .category-header h1 i {
            margin-right: var(--spacing-md);
        }

        .product-count {
            font-size: 1rem;
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

        /* ============================================
           BREADCRUMB NAVIGATION
        ============================================ */
        .breadcrumb {
            background: var(--white);
            padding: var(--spacing-md) 0;
            border-bottom: 1px solid var(--gray-200);
        }

        .breadcrumb-list {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            flex-wrap: wrap;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            color: var(--gray-600);
            font-size: 0.85rem;
        }

        .breadcrumb-item a {
            color: var(--gray-600);
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: var(--primary);
        }

        .breadcrumb-item.active {
            color: var(--primary);
            font-weight: 500;
        }

        .breadcrumb-separator {
            color: var(--gray-400);
        }

        /* ============================================
           PRODUCTS LAYOUT
        ============================================ */
        .products-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: var(--spacing-2xl);
            padding: var(--spacing-2xl) 0;
        }

        /* ============================================
           FILTERS SIDEBAR
        ============================================ */
        .filters-sidebar {
            background: var(--white);
            border-radius: var(--radius-xl);
            padding: var(--spacing-xl);
            border: 1px solid var(--gray-200);
            position: sticky;
            top: 100px;
            height: fit-content;
        }

        .filter-section {
            margin-bottom: var(--spacing-xl);
            padding-bottom: var(--spacing-xl);
            border-bottom: 1px solid var(--gray-200);
        }

        .filter-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .filter-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: var(--spacing-lg);
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .filter-title i {
            color: var(--primary);
            font-size: 1rem;
        }

        /* Price Range */
        .price-range {
            padding: var(--spacing-md) 0;
        }

        .price-inputs {
            display: flex;
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-lg);
        }

        .price-input {
            flex: 1;
        }

        .price-input label {
            display: block;
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-bottom: var(--spacing-xs);
        }

        .price-input input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            font-size: 0.85rem;
        }

        .price-input input:focus {
            outline: none;
            border-color: var(--primary);
        }

        /* Sort Select */
        .sort-select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            background: var(--white);
            cursor: pointer;
            font-family: inherit;
        }

        .sort-select:focus {
            outline: none;
            border-color: var(--primary);
        }

        /* Filter Actions */
        .filter-actions {
            display: flex;
            gap: var(--spacing-md);
            margin-top: var(--spacing-lg);
        }

        .filter-actions button {
            flex: 1;
            padding: 10px;
            border-radius: var(--radius-lg);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .apply-filters {
            background: var(--primary);
            color: var(--white);
            border: none;
        }

        .apply-filters:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .clear-filters {
            background: transparent;
            border: 1px solid var(--gray-300);
            color: var(--gray-600);
        }

        .clear-filters:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* ============================================
           PRODUCTS HEADER
        ============================================ */
        .products-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--spacing-xl);
            padding-bottom: var(--spacing-lg);
            border-bottom: 2px solid var(--gray-200);
            flex-wrap: wrap;
            gap: var(--spacing-md);
        }

        .products-count {
            color: var(--gray-600);
            font-size: 0.9rem;
        }

        .products-count strong {
            color: var(--primary);
            font-size: 1.1rem;
        }

        .view-options {
            display: flex;
            gap: var(--spacing-sm);
        }

        .view-btn {
            padding: 8px 12px;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            background: var(--white);
            cursor: pointer;
            transition: var(--transition);
        }

        .view-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* ============================================
           PRODUCT GRID
        ============================================ */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: var(--spacing-xl);
        }

        /* Enhanced Product Card */
        .product-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            transition: var(--transition);
            position: relative;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary);
            box-shadow: var(--shadow-xl);
        }

        .p-img {
            height: 220px;
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

        .product-card:hover .p-img img {
            transform: scale(1.08);
        }

        .p-discount {
            position: absolute;
            top: var(--spacing-md);
            left: var(--spacing-md);
            background: linear-gradient(135deg, var(--danger), #dc2626);
            color: var(--white);
            padding: 4px 12px;
            border-radius: var(--radius-full);
            font-size: 0.7rem;
            font-weight: 700;
            z-index: 1;
            box-shadow: var(--shadow-sm);
        }

        .wishlist-btn-card {
            position: absolute;
            top: var(--spacing-md);
            right: var(--spacing-md);
            background: var(--white);
            border: 1px solid var(--gray-200);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: var(--transition);
            color: var(--gray-500);
        }

        .wishlist-btn-card:hover {
            background: var(--danger);
            border-color: var(--danger);
            color: var(--white);
            transform: scale(1.1);
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
            color: var(--primary);
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: var(--spacing-sm);
        }

        .p-old {
            text-decoration: line-through;
            color: var(--gray-400);
            font-size: 0.8rem;
            margin-left: var(--spacing-sm);
            font-weight: 400;
        }

        .rating {
            margin: var(--spacing-sm) 0;
            display: flex;
            gap: 3px;
        }

        .rating i {
            color: #fbbf24;
            font-size: 0.85rem;
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
        .empty-state {
            text-align: center;
            padding: var(--spacing-3xl);
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--gray-300);
            margin-bottom: var(--spacing-lg);
        }

        .empty-state h2 {
            font-size: 1.5rem;
            margin-bottom: var(--spacing-md);
            color: var(--gray-800);
        }

        .empty-state p {
            color: var(--gray-500);
            margin-bottom: var(--spacing-xl);
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 1024px) {
            .products-layout {
                grid-template-columns: 1fr;
                gap: var(--spacing-xl);
            }
            .filters-sidebar {
                position: static;
                margin-bottom: var(--spacing-xl);
            }
        }

        @media (max-width: 768px) {
            .category-header h1 {
                font-size: 2rem;
            }
            .products-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--spacing-md);
            }
            .price-inputs {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .product-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
            }
            .filter-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<!-- BREADCRUMB NAVIGATION -->
<div class="breadcrumb">
    <div class="container">
        <div class="breadcrumb-list">
            <div class="breadcrumb-item">
                <a href="index.php"><i class="fas fa-home"></i> Home</a>
            </div>
            <div class="breadcrumb-separator">
                <i class="fas fa-chevron-right"></i>
            </div>
            <div class="breadcrumb-item">
                <a href="all_products.php">All Products</a>
            </div>
            <div class="breadcrumb-separator">
                <i class="fas fa-chevron-right"></i>
            </div>
            <div class="breadcrumb-item active">
                <?php echo $display_name; ?>
            </div>
        </div>
    </div>
</div>

<!-- CATEGORY HEADER -->
<div class="category-header">
    <div class="container">
        <h1>
            <i class="fas <?php echo $icon; ?>"></i>
            <?php echo $display_name; ?>
        </h1>
        <p class="product-count"><?php echo count($products); ?> products found</p>
    </div>
</div>

<main>
    <div class="container">
        <div class="products-layout">
            <!-- FILTERS SIDEBAR -->
            <aside class="filters-sidebar">
                <form method="GET" action="" id="filterForm">
                    <input type="hidden" name="cat" value="<?php echo htmlspecialchars($category); ?>">
                    
                    <!-- Price Range Filter -->
                    <div class="filter-section">
                        <div class="filter-title">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Price Range</span>
                        </div>
                        <div class="price-range">
                            <div class="price-inputs">
                                <div class="price-input">
                                    <label>Min (৳)</label>
                                    <input type="number" name="min_price" id="min_price" 
                                           value="<?php echo $min_price; ?>" 
                                           placeholder="0" min="0">
                                </div>
                                <div class="price-input">
                                    <label>Max (৳)</label>
                                    <input type="number" name="max_price" id="max_price" 
                                           value="<?php echo $max_price; ?>" 
                                           placeholder="<?php echo number_format($price_range['max_price'] ?? 100000); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sort By -->
                    <div class="filter-section">
                        <div class="filter-title">
                            <i class="fas fa-sort-amount-down"></i>
                            <span>Sort By</span>
                        </div>
                        <select name="sort" class="sort-select" id="sortSelect">
                            <option value="default" <?php echo $sort == 'default' ? 'selected' : ''; ?>>Default (Newest)</option>
                            <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                            <option value="rating" <?php echo $sort == 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
                            <option value="name" <?php echo $sort == 'name' ? 'selected' : ''; ?>>Name A-Z</option>
                        </select>
                    </div>

                    <!-- Filter Actions -->
                    <div class="filter-actions">
                        <button type="submit" class="apply-filters">Apply Filters</button>
                        <button type="button" class="clear-filters" onclick="clearFilters()">Clear All</button>
                    </div>
                </form>
            </aside>

            <!-- PRODUCTS SECTION -->
            <div class="products-section">
                <div class="products-header">
                    <div class="products-count">
                        Showing <strong><?php echo count($products); ?></strong> products
                    </div>
                    <div class="view-options">
                        <button class="view-btn" onclick="setView('grid')" title="Grid View">
                            <i class="fas fa-th"></i>
                        </button>
                        <button class="view-btn" onclick="setView('list')" title="List View">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>

                <?php if(empty($products)): ?>
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <h2>No products found</h2>
                        <p>Try adjusting your filters or check back later for new arrivals</p>
                        <button class="p-btn" onclick="clearFilters()" style="display: inline-block; width: auto; padding: 12px 30px;">
                            Clear Filters
                        </button>
                    </div>
                <?php else: ?>
                    <div class="product-grid" id="productGrid">
                        <?php foreach($products as $product): ?>
                            <div class="product-card" data-price="<?php echo $product['price']; ?>" data-date="<?php echo $product['created_at']; ?>" data-name="<?php echo htmlspecialchars($product['name']); ?>" data-rating="<?php echo $product['rating'] ?? 0; ?>">
                                <button class="wishlist-btn-card" 
                                    onclick="event.stopPropagation(); addToWishlist(<?php echo $product['id']; ?>)">
                                    <i class="far fa-heart"></i>
                                </button>
                                <div class="p-img" onclick="location.href='product_details.php?id=<?php echo $product['id']; ?>'">
                                    <img src="<?php echo $product['image_url']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
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
                                    <button class="p-btn" onclick="event.stopPropagation(); addToCart(<?php echo $product['id']; ?>, 1)">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
// Add to Cart Function
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
            updateCartBadge();
        } else {
            showNotification(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if(button) {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
        }
        showNotification('Network error', 'error');
    });
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
                updateWishlistBadge();
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

// Update Cart Badge
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

// Update Wishlist Badge
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

// Show Notification
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

// Clear Filters
function clearFilters() {
    window.location.href = 'category.php?cat=<?php echo $category; ?>';
}

// Set View (Grid/List)
function setView(view) {
    const grid = document.getElementById('productGrid');
    if(view === 'list') {
        grid.style.display = 'flex';
        grid.style.flexDirection = 'column';
        document.querySelectorAll('.product-card').forEach(card => {
            card.style.display = 'flex';
            card.style.flexDirection = 'row';
            card.style.height = '200px';
            card.style.alignItems = 'center';
        });
        document.querySelectorAll('.p-img').forEach(img => {
            img.style.width = '200px';
            img.style.height = '200px';
            img.style.borderBottom = 'none';
            img.style.borderRight = '1px solid var(--gray-200)';
        });
    } else {
        grid.style.display = 'grid';
        document.querySelectorAll('.product-card').forEach(card => {
            card.style.display = 'block';
            card.style.height = 'auto';
        });
        document.querySelectorAll('.p-img').forEach(img => {
            img.style.width = 'auto';
            img.style.height = '220px';
            img.style.borderBottom = '1px solid var(--gray-200)';
            img.style.borderRight = 'none';
        });
    }
}

// Auto-submit form on sort change
document.getElementById('sortSelect')?.addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartBadge();
    updateWishlistBadge();
});

// Expose functions globally
window.updateCartBadge = updateCartBadge;
window.updateWishlistBadge = updateWishlistBadge;
</script>

<style>
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
</style>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>