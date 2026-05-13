<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';

$products_sql = "SELECT * FROM products ORDER BY created_at DESC";
$products_result = mysqli_query($conn, $products_sql);
$total_products = mysqli_num_rows($products_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="page-header">
    <div class="container">
        <h1>All Products</h1>
        <p><?php echo $total_products; ?> products available</p>
    </div>
</div>

<main>
    <div class="container" style="padding: 60px 0;">
        <div class="product-grid">
            <?php while($product = mysqli_fetch_assoc($products_result)): ?>
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
            <?php endwhile; ?>
        </div>
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