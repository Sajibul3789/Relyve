<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';

$search_term = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
$products = [];

if($search_term) {
    $sql = "SELECT * FROM products WHERE name LIKE '%$search_term%' OR description LIKE '%$search_term%' OR category LIKE '%$search_term%'";
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
    <title>Search Results - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .search-header {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        .search-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .no-results {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 16px;
        }
        .no-results i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }
        .suggestion {
            margin-top: 30px;
            text-align: center;
        }
        .suggestion a {
            display: inline-block;
            margin: 5px;
            padding: 8px 15px;
            background: #f3f4f6;
            border-radius: 20px;
            text-decoration: none;
            color: #374151;
        }
    </style>
</head>
<body>

<div class="search-header">
    <div class="container">
        <h1>Search Results</h1>
        <p><?php echo count($products); ?> products found for "<strong><?php echo htmlspecialchars($search_term); ?></strong>"</p>
    </div>
</div>

<main>
    <div class="container" style="padding: 40px 0;">
        <?php if(empty($products)): ?>
            <div class="no-results">
                <i class="fas fa-search"></i>
                <h2>No products found</h2>
                <p>We couldn't find any products matching "<?php echo htmlspecialchars($search_term); ?>"</p>
                <div class="suggestion">
                    <p>Try searching for:</p>
                    <a href="search.php?q=smartphone">Smartphone</a>
                    <a href="search.php?q=laptop">Laptop</a>
                    <a href="search.php?q=headphone">Headphone</a>
                    <a href="search.php?q=watch">Watch</a>
                </div>
                <a href="index.php" class="p-btn" style="display: inline-block; width: auto; padding: 12px 30px; margin-top: 30px;">Back to Home</a>
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach($products as $product): ?>
                    <div class="product-card">
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
</script>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>