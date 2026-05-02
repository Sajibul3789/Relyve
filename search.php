<?php
session_start();
include 'includes/navbar.php';
include 'config/db_connect.php';

$search_term = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
$products = [];

if($search_term) {
    $sql = "SELECT * FROM products WHERE name LIKE '%$search_term%' OR description LIKE '%$search_term%'";
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
</head>
<body>

<main>
    <div class="container">
        <div class="section">
            <h2>Search Results for "<?php echo htmlspecialchars($search_term); ?>"</h2>
            <p style="margin-bottom: 30px; color: var(--text-light);">Found <?php echo count($products); ?> products</p>
            
            <?php if(empty($products)): ?>
                <div style="text-align: center; padding: 60px;">
                    <i class="fas fa-search" style="font-size: 4rem; color: #ddd; margin-bottom: 20px;"></i>
                    <h3>No products found</h3>
                    <p>Try searching with different keywords</p>
                    <a href="index.php" class="p-btn" style="display: inline-block; width: auto; padding: 12px 30px;">Back to Home</a>
                </div>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach($products as $product): ?>
                    <div class="product-card">
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
                            <div class="p-price">৳<?php echo number_format($product['price']); ?>
                                <?php if($product['old_price']): ?>
                                    <span class="p-old">৳<?php echo number_format($product['old_price']); ?></span>
                                <?php endif; ?>
                            </div>
                            <button class="p-btn" onclick="addToCart(<?php echo $product['id']; ?>, 1)">Add to Cart</button>
                            <a href="product-details.php?id=<?php echo $product['id']; ?>" class="p-btn" style="display:block; text-align:center; text-decoration:none; margin-top:8px; background:#f3f4f6; color:var(--text)">View Details</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<script>
function addToCart(productId, quantity) {
    <?php if(!isset($_SESSION['user_id'])): ?>
        if(confirm('Please login to add items to cart. Go to login page?')) {
            window.location.href = 'login_form.php';
        }
        return;
    <?php endif; ?>
    
    fetch('process/add_to_cart_process.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId + '&quantity=' + quantity
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Product added to cart!');
        } else {
            alert(data.message || 'Error adding to cart');
        }
    });
}
</script>
</body>
</html>