<?php
session_start();
include 'includes/navbar.php';
include 'config/db_connect.php';

$user_id = $_SESSION['user_id'] ?? 0;
$cart_items = [];
$total = 0;

if($user_id) {
    $cart_query = getCartItems($conn, $user_id);
    while($item = mysqli_fetch_assoc($cart_query)) {
        $item['subtotal'] = $item['price'] * $item['quantity'];
        $total += $item['subtotal'];
        $cart_items[] = $item;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/cart.css">
</head>
<body>

<main>
    <div class="container">
        <div class="cart-container">
            <h1 class="cart-title">Shopping Cart</h1>
            
            <?php if(empty($cart_items)): ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h2>Your cart is empty</h2>
                    <p>Looks like you haven't added anything to your cart yet</p>
                    <a href="index.php" class="continue-shopping">Continue Shopping</a>
                </div>
            <?php else: ?>
                <div class="cart-content">
                    <div class="cart-items">
                        <div class="cart-header">
                            <div>Product</div>
                            <div>Price</div>
                            <div>Quantity</div>
                            <div>Total</div>
                            <div></div>
                        </div>
                        
                        <?php foreach($cart_items as $item): ?>
                        <div class="cart-item" id="cart-item-<?php echo $item['product_id']; ?>">
                            <div class="item-product">
                                <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>">
                                <div>
                                    <h3><?php echo $item['name']; ?></h3>
                                    <p class="item-stock">In Stock</p>
                                </div>
                            </div>
                            <div class="item-price">৳<?php echo number_format($item['price']); ?></div>
                            <div class="item-quantity">
                                <div class="quantity-controls">
                                    <button class="qty-btn" onclick="updateQuantity(<?php echo $item['product_id']; ?>, <?php echo $item['quantity'] - 1; ?>)">-</button>
                                    <input type="number" id="qty-<?php echo $item['product_id']; ?>" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>" readonly>
                                    <button class="qty-btn" onclick="updateQuantity(<?php echo $item['product_id']; ?>, <?php echo $item['quantity'] + 1; ?>)">+</button>
                                </div>
                            </div>
                            <div class="item-total" id="total-<?php echo $item['product_id']; ?>">
                                ৳<?php echo number_format($item['subtotal']); ?>
                            </div>
                            <div class="item-remove">
                                <button onclick="removeFromCart(<?php echo $item['product_id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="cart-summary">
                        <h2>Order Summary</h2>
                        <div class="summary-details">
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span id="subtotal">৳<?php echo number_format($total); ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span id="shipping"><?php echo $total >= 5000 ? 'Free' : '৳100'; ?></span>
                            </div>
                            <div class="summary-row total">
                                <span>Total</span>
                                <span id="grand-total">৳<?php echo number_format($total >= 5000 ? $total : $total + 100); ?></span>
                            </div>
                        </div>
                        
                        <div class="promo-code">
                            <input type="text" placeholder="Promo code">
                            <button>Apply</button>
                        </div>
                        
                        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
                        <a href="index.php" class="continue-shopping-link">Continue Shopping</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<script>
function updateQuantity(productId, newQuantity) {
    if(newQuantity < 1) return;
    
    fetch('process/update_cart_process.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId + '&quantity=' + newQuantity
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            document.getElementById('qty-' + productId).value = newQuantity;
            document.getElementById('total-' + productId).innerHTML = '৳' + data.item_total;
            updateCartTotals();
        }
    });
}

function removeFromCart(productId) {
    if(confirm('Remove this item from cart?')) {
        fetch('process/remove_from_cart_process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById('cart-item-' + productId).remove();
                updateCartTotals();
                updateCartCount();
                
                // Check if cart is empty
                if(document.querySelectorAll('.cart-item').length === 0) {
                    location.reload();
                }
            }
        });
    }
}

function updateCartTotals() {
    fetch('process/get_cart_totals.php')
    .then(response => response.json())
    .then(data => {
        document.getElementById('subtotal').innerHTML = '৳' + data.subtotal.toLocaleString();
        let shipping = data.subtotal >= 5000 ? 'Free' : '৳100';
        document.getElementById('shipping').innerHTML = shipping;
        document.getElementById('grand-total').innerHTML = '৳' + data.grand_total.toLocaleString();
    });
}

function updateCartCount() {
    fetch('process/get_cart_count.php')
    .then(response => response.json())
    .then(data => {
        const badges = document.querySelectorAll('.badge');
        badges.forEach(badge => {
            badge.textContent = data.count;
        });
    });
}
</script>
</body>
</html>