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

// Get selected items from session storage (passed via URL parameter)
$selected_items_json = isset($_GET['selected']) ? $_GET['selected'] : '';

if($selected_items_json) {
    // Decode the JSON from session storage
    $selected_items = json_decode(urldecode($selected_items_json), true);
    
    if(!empty($selected_items)) {
        // Get product details for selected items
        $selected_ids = array_column($selected_items, 'id');
        $ids_string = implode(',', array_map('intval', $selected_ids));
        
        $cart_query = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($ids_string)");
        
        while($product = mysqli_fetch_assoc($cart_query)) {
            // Find the quantity for this product from selected items
            $quantity = 0;
            foreach($selected_items as $item) {
                if($item['id'] == $product['id']) {
                    $quantity = $item['quantity'];
                    break;
                }
            }
            
            if($quantity > 0) {
                $product['quantity'] = $quantity;
                $product['subtotal'] = $product['price'] * $quantity;
                $total += $product['subtotal'];
                $cart_items[] = $product;
            }
        }
    }
}

// If no items after processing, redirect back to cart
if(empty($cart_items)) {
    header("Location: cart.php?error=No items selected for checkout");
    exit();
}

// Get user details
$user_sql = "SELECT * FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);

$shipping = $total >= 5000 ? 0 : 100;
$tax = round($total * 0.05); // 5% tax
$grand_total = $total + $shipping + $tax;
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
        /* ============================================
           CHECKOUT CONTAINER
        ============================================ */
        .checkout-container {
            max-width: 1280px;
            margin: var(--spacing-2xl) auto;
            padding: 0 var(--spacing-xl);
        }

        .checkout-header {
            margin-bottom: var(--spacing-2xl);
            padding-bottom: var(--spacing-lg);
            border-bottom: 2px solid var(--gray-200);
        }

        .checkout-header h1 {
            font-size: 2rem;
            margin-bottom: var(--spacing-xs);
            color: var(--gray-900);
        }

        .checkout-header h1 i {
            color: var(--primary);
            margin-right: var(--spacing-sm);
        }

        .checkout-header p {
            color: var(--gray-500);
        }

        /* ============================================
           CHECKOUT GRID
        ============================================ */
        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: var(--spacing-2xl);
        }

        /* ============================================
           CHECKOUT FORM
        ============================================ */
        .checkout-form {
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .form-section {
            padding: var(--spacing-xl);
            border-bottom: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .form-section:hover {
            background: var(--gray-50);
        }

        .section-title {
            font-size: 1.2rem;
            margin-bottom: var(--spacing-xl);
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding-bottom: var(--spacing-md);
            border-bottom: 2px solid var(--primary);
            display: inline-flex;
        }

        .section-title i {
            color: var(--primary);
            font-size: 1.1rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-md);
        }

        .form-group {
            margin-bottom: var(--spacing-lg);
        }

        .form-group label {
            display: block;
            margin-bottom: var(--spacing-sm);
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--gray-700);
        }

        .form-group label .required {
            color: var(--danger);
            margin-left: 2px;
        }

        .form-group input, 
        .form-group textarea, 
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-lg);
            font-family: inherit;
            font-size: 0.9rem;
            transition: var(--transition);
            background: var(--white);
        }

        .form-group input:focus, 
        .form-group textarea:focus, 
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* Payment Methods */
        .payment-methods {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-md);
        }

        .payment-method {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            padding: var(--spacing-md) var(--spacing-lg);
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-lg);
            cursor: pointer;
            transition: var(--transition);
            background: var(--white);
        }

        .payment-method:hover {
            border-color: var(--primary);
            background: linear-gradient(135deg, var(--primary-light), var(--white));
            transform: translateX(5px);
        }

        .payment-method input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .payment-method span {
            flex: 1;
            font-weight: 500;
            color: var(--gray-700);
        }

        .payment-method span i {
            margin-right: var(--spacing-sm);
            font-size: 1.1rem;
            width: 25px;
            color: var(--primary);
        }

        /* ============================================
           ORDER SUMMARY SIDEBAR
        ============================================ */
        .order-summary {
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            padding: var(--spacing-xl);
            position: sticky;
            top: 100px;
            height: fit-content;
            box-shadow: var(--shadow-md);
        }

        .order-summary h2 {
            font-size: 1.3rem;
            margin-bottom: var(--spacing-lg);
            padding-bottom: var(--spacing-md);
            border-bottom: 2px solid var(--gray-200);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            color: var(--gray-900);
        }

        .order-summary h2 i {
            color: var(--primary);
        }

        .selected-badge {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
            padding: var(--spacing-sm) var(--spacing-md);
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-xs);
            margin-bottom: var(--spacing-lg);
            border: 1px solid #bbf7d0;
        }

        .order-items {
            max-height: 320px;
            overflow-y: auto;
            margin-bottom: var(--spacing-lg);
            padding-right: var(--spacing-sm);
        }

        .order-items::-webkit-scrollbar {
            width: 4px;
        }

        .order-items::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: var(--radius-full);
        }

        .order-items::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: var(--radius-full);
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-md) 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item-info {
            flex: 1;
        }

        .order-item-name {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--gray-800);
            margin-bottom: 4px;
        }

        .order-item-meta {
            font-size: 0.7rem;
            color: var(--gray-500);
        }

        .order-item-price {
            font-weight: 600;
            color: var(--primary);
            font-size: 0.95rem;
        }

        .order-total {
            margin-top: var(--spacing-lg);
            padding-top: var(--spacing-lg);
            border-top: 1px solid var(--gray-200);
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-sm) 0;
            color: var(--gray-700);
        }

        .total-row.shipping, .total-row.tax {
            padding-bottom: var(--spacing-md);
            border-bottom: 1px solid var(--gray-200);
        }

        .grand-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: var(--spacing-md);
            padding-top: var(--spacing-md);
            border-top: 2px solid var(--gray-200);
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--gray-900);
        }

        .grand-total span:last-child {
            color: var(--primary);
            font-size: 1.4rem;
        }

        .shipping-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-xs);
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
            padding: 4px 10px;
            border-radius: var(--radius-full);
            font-size: 0.7rem;
            font-weight: 600;
        }

        .place-order-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: 14px;
            border: none;
            border-radius: var(--radius-lg);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: var(--spacing-xl);
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-sm);
        }

        .place-order-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .place-order-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .secure-badge {
            text-align: center;
            margin-top: var(--spacing-lg);
            padding-top: var(--spacing-md);
            border-top: 1px solid var(--gray-200);
            font-size: 0.8rem;
            color: var(--gray-500);
        }

        .secure-badge i {
            color: var(--success);
            margin-right: var(--spacing-xs);
        }

        /* ============================================
           TRUST INDICATORS
        ============================================ */
        .trust-badges {
            display: flex;
            justify-content: space-around;
            margin-top: var(--spacing-lg);
            padding-top: var(--spacing-lg);
            border-top: 1px solid var(--gray-200);
        }

        .trust-badge {
            text-align: center;
            font-size: 0.7rem;
            color: var(--gray-500);
        }

        .trust-badge i {
            font-size: 1.2rem;
            color: var(--primary);
            display: block;
            margin-bottom: var(--spacing-xs);
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 1024px) {
            .checkout-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-xl);
            }
            .order-summary {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .checkout-container {
                padding: 0 var(--spacing-md);
                margin: var(--spacing-xl) auto;
            }
            .checkout-header h1 {
                font-size: 1.5rem;
            }
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .form-section {
                padding: var(--spacing-lg);
            }
            .order-summary {
                padding: var(--spacing-lg);
            }
            .trust-badges {
                flex-wrap: wrap;
                gap: var(--spacing-md);
            }
        }

        @media (max-width: 480px) {
            .payment-method {
                padding: var(--spacing-sm) var(--spacing-md);
            }
            .order-item {
                flex-direction: column;
                align-items: flex-start;
                gap: var(--spacing-sm);
            }
            .order-item-price {
                align-self: flex-end;
            }
            .trust-badges {
                flex-direction: column;
                align-items: center;
                gap: var(--spacing-md);
            }
        }
    </style>
</head>
<body>

<main>
    <div class="checkout-container">
        <div class="checkout-header">
            <h1><i class="fas fa-lock"></i> Secure Checkout</h1>
            <p>Complete your order with confidence</p>
        </div>
        
        <div class="checkout-grid">
            <!-- CHECKOUT FORM -->
            <div class="checkout-form">
                <form action="process/place_order_process.php" method="POST" id="checkoutForm">
                    <!-- Pass selected items as JSON string -->
                    <input type="hidden" name="selected_items" id="selectedItemsInput" value='<?php echo json_encode($cart_items); ?>'>
                    
                    <!-- Shipping Information -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-truck"></i>
                            <span>Shipping Information</span>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>First Name <span class="required">*</span></label>
                                <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Last Name <span class="required">*</span></label>
                                <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Phone Number <span class="required">*</span></label>
                            <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Street Address <span class="required">*</span></label>
                            <input type="text" name="address" placeholder="House number, street name" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>City <span class="required">*</span></label>
                                <input type="text" name="city" placeholder="Dhaka" required>
                            </div>
                            <div class="form-group">
                                <label>Postal Code <span class="required">*</span></label>
                                <input type="text" name="zip" placeholder="1200" required>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Method -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-credit-card"></i>
                            <span>Payment Method</span>
                        </div>
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
                                <span><i class="fab fa-bkash"></i> bKash</span>
                            </label>
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="nagad">
                                <span><i class="fas fa-mobile-alt"></i> Nagad</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Order Notes -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-pencil-alt"></i>
                            <span>Order Notes (Optional)</span>
                        </div>
                        <div class="form-group">
                            <textarea name="notes" rows="3" placeholder="Special delivery instructions or notes about your order..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- ORDER SUMMARY -->
            <div class="order-summary">
                <h2><i class="fas fa-receipt"></i> Your Order</h2>
                
                <div class="selected-badge">
                    <i class="fas fa-check-circle"></i>
                    <?php echo count($cart_items); ?> item(s) selected for checkout
                </div>
                
                <div class="order-items">
                    <?php foreach($cart_items as $item): ?>
                        <div class="order-item">
                            <div class="order-item-info">
                                <div class="order-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                <div class="order-item-meta">Qty: <?php echo $item['quantity']; ?></div>
                            </div>
                            <div class="order-item-price">৳<?php echo number_format($item['subtotal']); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-total">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <span>৳<?php echo number_format($total); ?></span>
                    </div>
                    <div class="total-row shipping">
                        <span>Shipping Fee</span>
                        <span>
                            <?php if($shipping == 0): ?>
                                <span class="shipping-badge"><i class="fas fa-gift"></i> Free Shipping</span>
                            <?php else: ?>
                                ৳<?php echo number_format($shipping); ?>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="total-row tax">
                        <span>Estimated Tax (5%)</span>
                        <span>৳<?php echo number_format($tax); ?></span>
                    </div>
                    <div class="grand-total">
                        <span>Total Amount</span>
                        <span>৳<?php echo number_format($grand_total); ?></span>
                    </div>
                </div>
                
                <button type="submit" form="checkoutForm" class="place-order-btn">
                    <i class="fas fa-check-circle"></i> Place Order
                </button>
                
                <div class="secure-badge">
                    <i class="fas fa-shield-alt"></i> Secure Checkout - Your information is protected
                </div>
                
                <div class="trust-badges">
                    <div class="trust-badge">
                        <i class="fas fa-truck"></i>
                        <span>Fast Delivery</span>
                    </div>
                    <div class="trust-badge">
                        <i class="fas fa-undo-alt"></i>
                        <span>Easy Returns</span>
                    </div>
                    <div class="trust-badge">
                        <i class="fas fa-headset"></i>
                        <span>24/7 Support</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Payment method hover effect
document.querySelectorAll('.payment-method').forEach(method => {
    method.addEventListener('click', function() {
        const radio = this.querySelector('input[type="radio"]');
        radio.checked = true;
        
        document.querySelectorAll('.payment-method').forEach(m => {
            m.style.borderColor = 'var(--gray-200)';
            m.style.background = 'var(--white)';
        });
        this.style.borderColor = 'var(--primary)';
        this.style.background = 'linear-gradient(135deg, var(--primary-light), var(--white))';
    });
});

// Auto-fill shipping info from account if available
<?php if(!empty($user['address'])): ?>
document.querySelector('input[name="address"]').value = '<?php echo htmlspecialchars($user['address']); ?>';
document.querySelector('input[name="city"]').value = '<?php echo htmlspecialchars($user['city'] ?? ''); ?>';
document.querySelector('input[name="zip"]').value = '<?php echo htmlspecialchars($user['zip'] ?? ''); ?>';
<?php endif; ?>

// Form submission
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate required fields
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if(!field.value.trim()) {
            field.style.borderColor = 'var(--danger)';
            isValid = false;
        } else {
            field.style.borderColor = 'var(--gray-200)';
        }
    });
    
    if(!isValid) {
        showNotification('Please fill in all required fields', 'error');
        return;
    }
    
    const btn = document.querySelector('.place-order-btn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing Order...';
    
    fetch('process/place_order.php', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            showNotification('Order placed successfully! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = 'order_confirmation.php?order_id=' + data.order_id;
            }, 1500);
        } else {
            showNotification(data.message || 'Error placing order. Please try again.', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Network error. Please try again.', 'error');
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
});

// Show notification function
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

// Remove notification animation style if exists
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
</script>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>