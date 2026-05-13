<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';

$category = isset($_GET['cat']) ? mysqli_real_escape_string($conn, $_GET['cat']) : '';
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

if($category) {
    $sql = "SELECT * FROM products WHERE category = '$category' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
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
        .category-header {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        .category-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .product-count {
            font-size: 1rem;
            opacity: 0.9;
        }
        .filter-bar {
            background: white;
            padding: 15px 0;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
        }
        .sort-select {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .no-products {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 16px;
        }
        .no-products i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="category-header">
    <div class="container">
        <h1><?php echo $display_name; ?></h1>
        <p class="product-count"><?php echo count($products); ?> products found</p>
    </div>
</div>

<div class="filter-bar">
    <div class="container" style="display: flex; justify-content: flex-end;">
        <select class="sort-select" id="sortSelect">
            <option value="default">Sort by: Default</option>
            <option value="price_low">Price: Low to High</option>
            <option value="price_high">Price: High to Low</option>
            <option value="newest">Newest First</option>
        </select>
    </div>
</div>

<main>
    <div class="container" style="padding: 40px 0;">
        <?php if(empty($products)): ?>
            <div class="no-products">
                <i class="fas fa-box-open"></i>
                <h2>No products in this category</h2>
                <p>Check back later for new arrivals</p>
                <a href="index.php" class="p-btn" style="display: inline-block; width: auto; padding: 12px 30px; margin-top: 20px;">Back to Home</a>
            </div>
        <?php else: ?>
            <div class="product-grid" id="productGrid">
                <?php foreach($products as $product): ?>
                    <div class="product-card" data-price="<?php echo $product['price']; ?>" data-date="<?php echo $product['created_at']; ?>">
                        <button class="wishlist-btn-card" 
                            onclick="event.stopPropagation(); location.href='<?php echo isset($_SESSION['user_id']) ? 'process/add_to_wishlist.php?id='.$product['id'] : 'login_form.php'; ?>'">
                            <i class="far fa-heart"></i>
                        </button>
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
                                ৳<?php echo number_format($product['price']); ?>
                                <?php if($product['old_price']): ?>
                                    <span class="p-old">৳<?php echo number_format($product['old_price']); ?></span>
                                <?php endif; ?>
                            </div>
                            <button class="p-btn" onclick="event.stopPropagation(); addToCart(<?php echo $product['id']; ?>, 1)">Add to Cart</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
function addToCart(productId, quantity) {
    <?php if(!isset($_SESSION['user_id'])): ?>
        if(confirm('Please login to add items to cart. Go to login page?')) {
            window.location.href = 'login_form.php';
        }
        return;
    <?php endif; ?>
    
    fetch('process/add_to_cart.php?product_id=' + productId + '&quantity=' + quantity)
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

// Sorting functionality
document.getElementById('sortSelect')?.addEventListener('change', function() {
    const grid = document.getElementById('productGrid');
    const products = Array.from(grid.children);
    const sortBy = this.value;
    
    products.sort((a, b) => {
        if(sortBy === 'price_low') {
            return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
        } else if(sortBy === 'price_high') {
            return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
        } else if(sortBy === 'newest') {
            return new Date(b.dataset.date) - new Date(a.dataset.date);
        }
        return 0;
    });
    
    products.forEach(product => grid.appendChild(product));
});
</script>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>