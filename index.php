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
        /* ============================================
           ENHANCED SEARCH SECTION
        ============================================ */
        .search-area {
            padding: var(--spacing-xl) 0;
            background: linear-gradient(135deg, var(--white) 0%, var(--gray-50) 100%);
            border-bottom: 2px solid var(--gray-200);
            backdrop-filter: blur(10px);
        }

        .search-container {
            max-width: 850px;
            margin: 0 auto;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 16px 25px 16px 55px;
            border-radius: var(--radius-2xl);
            border: 2px solid var(--gray-200);
            background: var(--white);
            font-size: 1rem;
            transition: var(--transition);
        }

        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 22px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 1.2rem;
        }

        .search-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary);
            color: white;
            padding: 10px 35px;
            border-radius: var(--radius-2xl);
            font-weight: 600;
            transition: var(--transition);
        }

        .search-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ============================================
           ENHANCED HERO SECTION
        ============================================ */
        .hero {
            min-height: 550px;
            height: auto;
            width: 100vw;
            display: flex;
            align-items: center;
            position: relative;
            isolation: isolate;
            border-top: 3px solid #777777;
            border-bottom: 3px solid #777777;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
            z-index: -1;
        }

        .hero-content {
            max-width: 700px;
            animation: fadeInUp 0.8s ease;
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

        .hero-tag {
            background: var(--primary);
            color: var(--white);
            padding: 6px 18px;
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: var(--spacing-lg);
            display: inline-block;
            letter-spacing: 1px;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: var(--spacing-md);
            font-weight: 800;
            line-height: 1.2;
            color: var(--white);
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: var(--spacing-xl);
            opacity: 0.95;
            color: var(--white);
        }

        .hero-btn {
            background: var(--primary);
            color: white;
            padding: 14px 40px !important;
            border-radius: var(--radius-2xl) !important;
            font-size: 1.1rem !important;
            font-weight: 600;
            transition: var(--transition);
        }

        .hero-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        /* ============================================
           ENHANCED CATEGORIES SECTION
        ============================================ */
        .categories-section {
            padding-top: var(--spacing-sm);
            padding-bottom: var(--spacing-4xl);
            background: linear-gradient(135deg, var(--white) 0%, var(--gray-100) 100%);
        }

        .section-title {
            text-align: center;
            font-size: 2.2rem;
            margin-bottom: var(--spacing-lg);
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--primary);
            border-radius: var(--radius-full);
        }

        .cat-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: var(--spacing-lg);
            margin-top: var(--spacing-xl);
        }

        .cat-card {
            background: var(--white);
            border-radius: var(--radius-3xl);
            border: 0.15rem solid var(--gray-300);
            overflow: hidden;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            color: inherit;
            display: block;
            padding: var(--spacing-xl) var(--spacing-md);
        }

        .cat-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary);
            box-shadow: var(--shadow-lg);
        }

        .cat-img {
            width: 80px;
            height: 80px;
            margin: 0 auto var(--spacing-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            border-radius: var(--radius-2xl);
            transition: var(--transition);
        }

        .cat-card:hover .cat-img {
            transform: scale(1.1);
        }

        .cat-blue { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #3b82f6; }
        .cat-slate { background: linear-gradient(135deg, #f1f5f9, #e2e8f0); color: #475569; }
        .cat-orange { background: linear-gradient(135deg, #fff7ed, #ffedd5); color: #f97316; }
        .cat-green { background: linear-gradient(135deg, #f0fdf4, #dcfce7); color: #22c55e; }
        .cat-purple { background: linear-gradient(135deg, #faf5ff, #f3e8ff); color: #a855f7; }
        .cat-red { background: linear-gradient(135deg, #fef2f2, #fee2e2); color: #ef4444; }

        .cat-card h3 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
            color: var(--gray-800);
        }

        /* ============================================
           ENHANCED PRODUCT GRID
        ============================================ */
        .section {
            padding: var(--spacing-2xl) 0;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--spacing-2xl);
            padding-bottom: var(--spacing-md);
            border-bottom: 2px solid var(--gray-200);
        }

        .section-header h2 {
            margin-bottom: 0;
            font-size: 1.8rem;
            position: relative;
            padding-left: var(--spacing-md);
            border-left: 4px solid var(--primary);
        }

        .view-all-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .view-all-link:hover {
            color: var(--primary-dark);
            gap: var(--spacing-md);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--spacing-md);
        }

        /* Enhanced Product Card */
        .product-card {
            background: var(--white);
            border-radius: var(--radius-2xl);
            border: 0.15rem solid var(--gray-300);
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
            color: white;
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
            color: white;
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
            color: white;
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
           HOT DEALS SECTION (Fixed)
        ============================================ */
        .hot-deals-section {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            position: relative;
            padding: var(--spacing-2xl) 0;
            border: 2px solid #fac1573b;
        }

        .hot-deals-section::before {
            content: '🔥';
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 100px;
            opacity: 0.1;
            pointer-events: none;
        }

        .hot-deals-section .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--spacing-xl);
        }

        .hot-deal-badge {
            position: absolute;
            top: var(--spacing-md);
            left: var(--spacing-md);
            background: linear-gradient(135deg, #f97316, #ef4444);
            color: white;
            padding: 6px 14px;
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: bold;
            z-index: 10;
            box-shadow: var(--shadow-md);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.95; }
        }

        .hot-price {
            color: #f97316;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .deal-timer-small {
            font-size: 0.75rem;
            color: var(--gray-600);
            margin: var(--spacing-sm) 0;
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            background: rgba(249, 115, 22, 0.1);
            padding: 6px 12px;
            border-radius: var(--radius-full);
        }

        .deal-timer-small i {
            color: #f97316;
        }

        .countdown-text {
            font-weight: 600;
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

        /* ============================================
           NOTIFICATION STYLES
        ============================================ */
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: var(--radius-lg);
            color: white;
            font-weight: 500;
            z-index: 10000;
            animation: slideInLeft 0.3s ease;
            box-shadow: var(--shadow-lg);
            backdrop-filter: blur(10px);
        }

        .notification.success { 
            background: linear-gradient(135deg, #22c55e, #16a34a);
        }
        .notification.error { 
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }
        .notification.info { 
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* ============================================
           BREADCRUMB & DIVIDERS
        ============================================ */
        .divider {
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gray-300), transparent);
            margin: var(--spacing-xl) 0;
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 1200px) {
            .product-grid {
                grid-template-columns: repeat(4, 1fr);
            }
            .hot-deals-section .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            .hot-deals-section .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .hero {
                min-height: 450px;
            }
            .hero h1 {
                font-size: 2rem;
            }
            .hero p {
                font-size: 1rem;
            }
            .hero-btn {
                padding: 12px 30px !important;
                font-size: 1rem !important;
            }
            .section {
                padding: 40px 0;
            }
            .section-header h2 {
                font-size: 1.5rem;
            }
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--spacing-md);
            }
            .hot-deals-section .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--spacing-md);
            }
            .cat-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--spacing-md);
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 1.5rem;
            }
            .section-title {
                font-size: 1.5rem;
            }
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--spacing-sm);
            }
            .hot-deals-section .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--spacing-sm);
            }
            .p-info {
                padding: var(--spacing-md);
            }
            .p-title {
                font-size: 0.85rem;
                height: auto;
            }
            .p-price {
                font-size: 1rem;
            }
            .p-btn {
                padding: 8px;
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>

<!-- SEARCH SECTION -->
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
<section class="hero" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%), url('<?php echo $hero['image_url']; ?>') center/cover; background-blend-mode: overlay;">
    <div class="container">
        <div class="hero-content">
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
            
            <button class="hero-btn btn" onclick="location.href='<?php echo $button_link; ?>'">
                <?php echo htmlspecialchars($hero['button_text']); ?> <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- DIVIDER -->
<div class="divider"></div>

<!-- SHOP BY CATEGORY -->
<section class="categories-section">
    <div class="container">
        <h2 class="section-title">Shop by Category</h2>
        <div class="cat-grid">
            <a href="category.php?cat=smartphones" class="cat-card">
                <div class="cat-img cat-blue"><i class="fas fa-mobile-alt"></i></div>
                <h3>Smartphones</h3>
            </a>
            <a href="category.php?cat=laptops" class="cat-card">
                <div class="cat-img cat-slate"><i class="fas fa-laptop"></i></div>
                <h3>Laptops</h3>
            </a>
            <a href="category.php?cat=tablets" class="cat-card">
                <div class="cat-img cat-orange"><i class="fas fa-tablet-alt"></i></div>
                <h3>Tablets</h3>
            </a>
            <a href="category.php?cat=accessories" class="cat-card">
                <div class="cat-img cat-green"><i class="fas fa-headphones"></i></div>
                <h3>Accessories</h3>
            </a>
            <a href="category.php?cat=tv_audio" class="cat-card">
                <div class="cat-img cat-purple"><i class="fas fa-tv"></i></div>
                <h3>TV & Audio</h3>
            </a>
            <a href="category.php?cat=watches" class="cat-card">
                <div class="cat-img cat-red"><i class="fas fa-clock"></i></div>
                <h3>Watches</h3>
            </a>
        </div>
    </div>
</section>

<!-- HOT DEALS SECTION - FIXED -->
<section class="hot-deals-section">
    <div class="container">
        <div class="section-header">
            <h2>🔥 Hot Deals</h2>
            <a href="hot_deals.php" class="view-all-link">View All <i class="fas fa-arrow-right"></i></a>
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
                    <div class="hot-deal-badge">🔥 -<?php echo $discount; ?>% OFF</div>
                    <div class="p-img" onclick="location.href='product_details.php?id=<?php echo $product['id']; ?>'">
                        <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
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
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- FEATURED PRODUCTS -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>✨ Featured Products</h2>
            <a href="all_products.php" class="view-all-link">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="product-grid">
            <?php while($featured = mysqli_fetch_assoc($featured_result)): ?>
                <?php display_product($featured); ?>
            <?php endwhile; ?>
        </div>
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
            clickedButton.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
        }
        
        if(data.success) {
            let message = data.action === 'updated' ? 'Cart updated!' : 'Product added to cart!';
            showNotification(message, 'success');
            updateCartBadge();
        } else {
            showNotification(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if(clickedButton) {
            clickedButton.disabled = false;
            clickedButton.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
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

// ============================================
// NOTIFICATION FUNCTIONS
// ============================================

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
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

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