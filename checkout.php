<?php
session_start();
include 'includes/navbar.php';
include 'config/db_connect.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login_form.php?error=Please login to checkout");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_items = [];
$total = 0;

// Get user details
$user_sql = "SELECT * FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);

// Get cart items
$cart_query = getCartItems($conn, $user_id);
while($item = mysqli_fetch_assoc($cart_query)) {
    $item['subtotal'] = $item['price'] * $item['quantity'];
    $total += $item['subtotal'];
    $cart_items[] = $item;
}

$shipping = $total >= 5000 ? 0 : 100;
$grand_total = $total + $shipping;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/checkout.css">
</head>
<body>

<main>
    <div class="container">
        <h1 class="checkout-title">Checkout</h1>
        
        <div class="checkout-container">
            <div class="checkout-form">
                <form action="process/place_order_process.php" method="POST" id="checkoutForm">
                    <div class="form-section">
                        <h2>Shipping Information</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label>First Name *</label>
                                <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Last Name *</label>
                                <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Email Address *</label>
                            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Phone Number *</label>
                            <input type="tel" name="phone" value="<?php echo $user['phone']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Street Address *</label>
                            <input type="text" name="address" placeholder="House number, street name" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>City *</label>
                                <input type="text" name="city" placeholder="Dhaka" required>
                            </div>
                            <div class="form-group">
                                <label>Postal Code *</label>
                                <input type="text" name="zip" placeholder="1200" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2>Payment Method</h2>
                        <div class="payment-methods">
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="cod" checked>
                                <div class="method-content">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <div>
                                        <strong>Cash on Delivery</strong>
                                        <small>Pay when you receive the order</small>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="card">
                                <div class="method-content">
                                    <i class="fas fa-credit-card"></i>
                                    <div>
                                        <strong>Credit/Debit Card</strong>
                                        <small>Visa, Mastercard, Amex</small>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="bkash">
                                <div class="method-content">
                                    <i class="fas fa-mobile-alt"></i>
                                    <div>
                                        <strong>bKash</strong>
                                        <small>Pay with bKash mobile wallet</small>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2>Order Notes (Optional)</h2>
                        <textarea name="notes" rows="3" placeholder="Special delivery instructions or notes about your order"></textarea>
                    </div>
                </form>
            </div>
            
            <div class="order-summary">
                <h2>Your Order</h2>
                <div class="order-items">
                    <?php foreach($cart_items as $item): ?>
                    <div class="order-item">
                        <div class="item-info">
                            <span class="item-name"><?php echo $item['name']; ?></span>
                            <span class="item-qty">x<?php echo $item['quantity']; ?></span>
                        </div>
                        <span class="item-price">৳<?php echo number_format($item['subtotal']); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-totals">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <span>৳<?php echo number_format($total); ?></span>
                    </div>
                    <div class="total-row">
                        <span>Shipping</span>
                        <span><?php echo $shipping == 0 ? 'Free' : '৳' . $shipping; ?></span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Total</span>
                        <span>৳<?php echo number_format($grand_total); ?></span>
                    </div>
                </div>
                
                <button type="submit" form="checkoutForm" class="place-order-btn">
                    Place Order
                </button>
                
                <div class="secure-checkout">
                    <i class="fas fa-lock"></i>
                    <span>Secure Checkout</span>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<script>
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.querySelector('.place-order-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    
    const formData = new FormData(this);
    
    fetch('process/place_order_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            window.location.href = 'order_confirmation.php?order_id=' + data.order_id;
        } else {
            alert(data.message || 'Error placing order');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Place Order';
        }
    })
    .catch(error => {
        alert('Error placing order');
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Place Order';
    });
});
</script>
</body>
</html>