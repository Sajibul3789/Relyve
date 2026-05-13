<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';

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

if(empty($cart_items)) {
    header("Location: cart.php");
    exit();
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
    <style>
        .checkout-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }
        .checkout-form {
            background: white;
            border-radius: 16px;
            padding: 25px;
        }
        .form-section {
            margin-bottom: 30px;
        }
        .form-section h2 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f97316;
            display: inline-block;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-family: inherit;
        }
        .form-group input:focus {
            outline: none;
            border-color: #f97316;
        }
        .payment-methods {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .payment-method {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
        }
        .payment-method:hover {
            border-color: #f97316;
        }
        .order-summary {
            background: white;
            border-radius: 16px;
            padding: 25px;
            position: sticky;
            top: 100px;
            height: fit-content;
        }
        .order-summary h2 {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .order-total {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .grand-total {
            font-weight: 700;
            font-size: 1.2rem;
            color: #f97316;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
        }
        .place-order-btn {
            width: 100%;
            background: #f97316;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
        }
        .place-order-btn:hover {
            background: #ea580c;
        }
        @media (max-width: 768px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="checkout-container">
        <h1 style="margin-bottom: 30px;">Checkout</h1>
        
        <div class="checkout-grid">
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
                                <span><i class="fas fa-money-bill-wave"></i> Cash on Delivery</span>
                            </label>
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="card">
                                <span><i class="fas fa-credit-card"></i> Credit/Debit Card</span>
                            </label>
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="bkash">
                                <span><i class="fas fa-mobile-alt"></i> bKash</span>
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
                            <span><?php echo $item['name']; ?> x<?php echo $item['quantity']; ?></span>
                            <span>৳<?php echo number_format($item['subtotal']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="order-total">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <span>৳<?php echo number_format($total); ?></span>
                    </div>
                    <div class="total-row">
                        <span>Shipping</span>
                        <span><?php echo $shipping == 0 ? 'Free' : '৳' . $shipping; ?></span>
                    </div>
                    <div class="grand-total">
                        <span>Total</span>
                        <span>৳<?php echo number_format($grand_total); ?></span>
                    </div>
                </div>
                <button type="submit" form="checkoutForm" class="place-order-btn">Place Order</button>
                <div style="text-align: center; margin-top: 15px; font-size: 0.8rem; color: #666;">
                    <i class="fas fa-lock"></i> Secure Checkout
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.querySelector('.place-order-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    
    fetch('process/place_order_process.php', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            window.location.href = 'order_confirmation.php?order_id=' + data.order_id;
        } else {
            alert(data.message || 'Error placing order');
            btn.disabled = false;
            btn.innerHTML = 'Place Order';
        }
    })
    .catch(error => {
        alert('Error placing order');
        btn.disabled = false;
        btn.innerHTML = 'Place Order';
    });
});
</script>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>