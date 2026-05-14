<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';

$search_term = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'relevance';
$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 100000;
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$products = [];

if($search_term) {
    // Build query with filters
    $where_conditions = ["(name LIKE '%$search_term%' OR description LIKE '%$search_term%' OR category LIKE '%$search_term%')"];
    
    if($min_price > 0) {
        $where_conditions[] = "price >= $min_price";
    }
    if($max_price < 100000) {
        $where_conditions[] = "price <= $max_price";
    }
    if($category) {
        $where_conditions[] = "category = '$category'";
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
        case 'newest':
            $order_by .= "created_at DESC";
            break;
        default:
            $order_by .= "CASE WHEN name LIKE '%$search_term%' THEN 1 ELSE 2 END";
    }
    
    $sql = "SELECT * FROM products WHERE $where_clause $order_by";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}

// Get categories for filter
$categories_sql = "SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != ''";
$categories_result = mysqli_query($conn, $categories_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* ============================================
           SEARCH HEADER
        ============================================ */
        .search-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: var(--spacing-3xl) 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .search-header::before {
            content: '🔍';
            position: absolute;
            font-size: 200px;
            opacity: 0.1;
            right: -50px;
            bottom: -80px;
            transform: rotate(-15deg);
            pointer-events: none;
        }

        .search-header h1 {
            font-size: 2rem;
            margin-bottom: var(--spacing-sm);
            color: var(--white);
            position: relative;
            z-index: 1;
        }

        .search-header p {
            color: rgba(255,255,255,0.9);
            position: relative;
            z-index: 1;
        }

        .search-header strong {
            color: #ffd700;
        }

        /* ============================================
           SEARCH LAYOUT
        ============================================ */
        .search-layout {
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
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: var(--spacing-lg);
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .filter-title i {
            color: var(--primary);
        }

        /* Price Range */
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
            font-size: 0.7rem;
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

        /* Category List */
        .category-list {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-md);
        }

        .category-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            cursor: pointer;
        }

        .category-item input {
            cursor: pointer;
            accent-color: var(--primary);
        }

        .category-item label {
            cursor: pointer;
            flex: 1;
            color: var(--gray-700);
            font-size: 0.85rem;
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
           PRODUCTS SECTION
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
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: var(--spacing-xl);
        }

        /* Product Card */
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
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: var(--shadow-lg);
        }

        .p-img {
            height: 220px;
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
            padding: 4px 10px;
            border-radius: var(--radius-full);
            font-size: 0.7rem;
            font-weight: 700;
        }

        .wishlist-btn-card {
            position: absolute;
            top: var(--spacing-sm);
            right: var(--spacing-sm);
            background: var(--white);
            border: 1px solid var(--gray-200);
            width: 32px;
            height: 32px;
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
        }

        .p-info {
            padding: var(--spacing-md);
        }

        .p-title {
            font-size: 0.9rem;
            font-weight: 500;
            height: 42px;
            overflow: hidden;
            margin-bottom: var(--spacing-sm);
            color: var(--gray-800);
        }

        .p-price {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .p-old {
            text-decoration: line-through;
            color: var(--gray-400);
            font-size: 0.75rem;
            margin-left: 5px;
        }

        .rating {
            margin: var(--spacing-xs) 0;
            display: flex;
            gap: 2px;
        }

        .rating i {
            color: #fbbf24;
            font-size: 0.7rem;
        }

        .p-btn {
            width: 100%;
            margin-top: var(--spacing-sm);
            padding: 8px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            border: none;
            border-radius: var(--radius-lg);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.8rem;
        }

        .p-btn:hover {
            transform: translateY(-2px);
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: var(--spacing-3xl);
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
        }

        .no-results i {
            font-size: 4rem;
            color: var(--gray-300);
            margin-bottom: var(--spacing-lg);
        }

        .no-results h2 {
            font-size: 1.5rem;
            margin-bottom: var(--spacing-md);
            color: var(--gray-800);
        }

        .suggestions {
            margin-top: var(--spacing-xl);
        }

        .suggestion-tags {
            display: flex;
            justify-content: center;
            gap: var(--spacing-sm);
            flex-wrap: wrap;
            margin-top: var(--spacing-md);
        }

        .suggestion-tag {
            display: inline-block;
            padding: 8px 16px;
            background: var(--gray-100);
            border-radius: var(--radius-full);
            text-decoration: none;
            color: var(--gray-700);
            transition: var(--transition);
            font-size: 0.85rem;
        }

        .suggestion-tag:hover {
            background: var(--primary);
            color: var(--white);
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 1024px) {
            .search-layout {
                grid-template-columns: 1fr;
                gap: var(--spacing-xl);
            }
            .filters-sidebar {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .search-header h1 {
                font-size: 1.5rem;
            }
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--spacing-md);
            }
            .products-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .price-inputs {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!-- SEARCH HEADER -->
<div class="search-header">
    <div class="container">
        <h1><i class="fas fa-search"></i> Search Results</h1>
        <p><?php echo count($products); ?> products found for "<strong><?php echo htmlspecialchars($search_term); ?></strong>"</p>
    </div>
</div>

<main>
    <div class="container">
        <div class="search-layout">
            <!-- FILTERS SIDEBAR -->
            <aside class="filters-sidebar">
                <form method="GET" action="" id="filterForm">
                    <input type="hidden" name="q" value="<?php echo htmlspecialchars($search_term); ?>">
                    
                    <!-- Price Range Filter -->
                    <div class="filter-section">
                        <div class="filter-title">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Price Range</span>
                        </div>
                        <div class="price-inputs">
                            <div class="price-input">
                                <label>Min (৳)</label>
                                <input type="number" name="min_price" id="min_price" 
                                       value="<?php echo $min_price; ?>" placeholder="0">
                            </div>
                            <div class="price-input">
                                <label>Max (৳)</label>
                                <input type="number" name="max_price" id="max_price" 
                                       value="<?php echo $max_price; ?>" placeholder="100000">
                            </div>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="filter-section">
                        <div class="filter-title">
                            <i class="fas fa-tags"></i>
                            <span>Category</span>
                        </div>
                        <div class="category-list">
                            <div class="category-item">
                                <input type="radio" name="category" value="" id="all_cats" 
                                       <?php echo !$category ? 'checked' : ''; ?>>
                                <label for="all_cats">All Categories</label>
                            </div>
                            <?php while($cat = mysqli_fetch_assoc($categories_result)): ?>
                                <div class="category-item">
                                    <input type="radio" name="category" value="<?php echo htmlspecialchars($cat['category']); ?>" 
                                           id="cat_<?php echo md5($cat['category']); ?>"
                                           <?php echo $category == $cat['category'] ? 'checked' : ''; ?>>
                                    <label for="cat_<?php echo md5($cat['category']); ?>">
                                        <?php echo ucfirst(htmlspecialchars($cat['category'])); ?>
                                    </label>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Sort By -->
                    <div class="filter-section">
                        <div class="filter-title">
                            <i class="fas fa-sort-amount-down"></i>
                            <span>Sort By</span>
                        </div>
                        <select name="sort" class="sort-select" id="sortSelect">
                            <option value="relevance" <?php echo $sort == 'relevance' ? 'selected' : ''; ?>>Relevance</option>
                            <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                            <option value="rating" <?php echo $sort == 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
                            <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest First</option>
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
                        Showing <strong><?php echo count($products); ?></strong> results
                    </div>
                </div>

                <?php if(empty($products)): ?>
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <h2>No products found</h2>
                        <p>We couldn't find any products matching "<?php echo htmlspecialchars($search_term); ?>"</p>
                        
                        <div class="suggestions">
                            <p>Try searching for:</p>
                            <div class="suggestion-tags">
                                <a href="search.php?q=smartphone" class="suggestion-tag">Smartphone</a>
                                <a href="search.php?q=laptop" class="suggestion-tag">Laptop</a>
                                <a href="search.php?q=headphone" class="suggestion-tag">Headphone</a>
                                <a href="search.php?q=watch" class="suggestion-tag">Watch</a>
                                <a href="search.php?q=tablet" class="suggestion-tag">Tablet</a>
                                <a href="search.php?q=accessories" class="suggestion-tag">Accessories</a>
                            </div>
                        </div>
                        
                        <a href="index.php" class="p-btn" style="display: inline-block; width: auto; padding: 12px 30px; margin-top: 30px;">
                            <i class="fas fa-arrow-left"></i> Back to Home
                        </a>
                    </div>
                <?php else: ?>
                    <div class="product-grid" id="productGrid">
                        <?php foreach($products as $product): ?>
                            <div class="product-card" onclick="location.href='product_details.php?id=<?php echo $product['id']; ?>'">
                                <button class="wishlist-btn-card" 
                                    onclick="event.stopPropagation(); addToWishlist(<?php echo $product['id']; ?>)">
                                    <i class="far fa-heart"></i>
                                </button>
                                <div class="p-img">
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
    window.location.href = 'search.php?q=<?php echo urlencode($search_term); ?>';
}

// Auto-submit form on sort change
document.getElementById('sortSelect')?.addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});

// CSS animations
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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartBadge();
    updateWishlistBadge();
});

// Expose functions globally
window.updateCartBadge = updateCartBadge;
window.updateWishlistBadge = updateWishlistBadge;
</script>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>