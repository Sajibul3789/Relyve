<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';

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
    <style>
        /* ============================================
           CART CONTAINER
        ============================================ */
        .cart-container {
            max-width: 1280px;
            margin: var(--spacing-2xl) auto;
            padding: 0 var(--spacing-xl);
        }

        /* ============================================
           CART HEADER
        ============================================ */
        .cart-header-section {
            margin-bottom: var(--spacing-2xl);
            padding-bottom: var(--spacing-lg);
            border-bottom: 2px solid var(--gray-200);
        }

        .cart-title {
            font-size: 2rem;
            margin-bottom: var(--spacing-lg);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: var(--spacing-md);
            color: var(--gray-900);
        }

        .cart-title i {
            color: var(--primary);
            margin-right: var(--spacing-sm);
        }

        .cart-stats {
            display: flex;
            gap: var(--spacing-lg);
            align-items: center;
            flex-wrap: wrap;
        }

        .select-all-wrapper {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            background: var(--white);
            padding: 8px 16px;
            border-radius: var(--radius-full);
            border: 0.15rem solid var(--gray-300);
            cursor: pointer;
            transition: var(--transition);
        }

        .select-all-wrapper:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .select-all-wrapper input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .select-all-checkbox {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            border: 0.15rem solid var(--gray-300);
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            outline: none;
            transition: background 0.2s;
            transform: scale(1.25);
            margin-right: var(--spacing-sm);
        }

        .select-all-checkbox:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .delete-selected {
            background: var(--white);
            border: 1px solid var(--danger);
            color: var(--danger);
            padding: 8px 20px;
            border-radius: var(--radius-full);
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .delete-selected:hover {
            background: var(--danger);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ============================================
           EMPTY CART STATE
        ============================================ */
        .empty-cart {
            text-align: center;
            padding: var(--spacing-3xl);
            background: var(--white);
            border-radius: var(--radius-2xl);
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
        }

        .empty-cart i {
            font-size: 5rem;
            color: var(--gray-300);
            margin-bottom: var(--spacing-lg);
        }

        .empty-cart h2 {
            font-size: 1.8rem;
            margin-bottom: var(--spacing-md);
            color: var(--gray-800);
        }

        .empty-cart p {
            color: var(--gray-500);
            margin-bottom: var(--spacing-xl);
        }

        .continue-shopping {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: 12px 30px;
            border-radius: var(--radius-lg);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .continue-shopping:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* ============================================
           CART CONTENT LAYOUT
        ============================================ */
        .cart-content {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: var(--spacing-2xl);
        }

        /* ============================================
           CART ITEMS TABLE
        ============================================ */
        .cart-items {
            background: var(--white);
            border-radius: var(--radius-2xl);
            overflow: hidden;
            border: 0.15rem solid var(--gray-200);
            box-shadow: var(--shadow-sm);
        }

        .cart-header {
            display: grid;
            grid-template-columns: 50px 1fr 120px 140px 120px 50px;
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            padding: var(--spacing-md) var(--spacing-lg);
            font-weight: 600;
            border-bottom: 2px solid var(--gray-200);
            color: var(--gray-700);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 50px 1fr 120px 140px 120px 50px;
            padding: var(--spacing-lg);
            border-bottom: 1px solid var(--gray-100);
            align-items: center;
            transition: var(--transition);
        }

        .cart-item:hover {
            background: var(--gray-50);
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-checkbox {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            border: 0.15rem solid var(--gray-300);
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            outline: none;
            transition: background 0.2s;
            transform: scale(1.15);
            margin-right: var(--spacing-sm);
        }

        .item-checkbox:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .item-select {
            text-align: center;
        }

        .item-select input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .item-product {
            display: flex;
            gap: var(--spacing-md);
            align-items: center;
        }

        .item-product img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .item-product:hover img {
            transform: scale(1.05);
            box-shadow: var(--shadow-md);
        }

        .item-product h3 {
            font-size: 0.95rem;
            margin-bottom: var(--spacing-xs);
            color: var(--gray-800);
            font-weight: 600;
        }

        .item-stock {
            color: var(--success);
            font-size: 0.7rem;
            font-weight: 500;
        }

        .item-price {
            font-weight: 600;
            color: var(--primary);
            font-size: 1rem;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .qty-btn {
            width: 32px;
            height: 32px;
            border: 1px solid var(--gray-200);
            background: var(--white);
            border-radius: var(--radius-lg);
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            color: var(--primary);
            transition: var(--transition);
        }

        .qty-btn:hover {
            background: var(--primary);
            color: var(--white);
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .quantity-controls input {
            width: 50px;
            text-align: center;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 6px;
            background: var(--white);
            font-weight: 500;
        }

        .item-total {
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 1.1rem;
        }

        .item-remove button {
            background: none;
            border: none;
            color: var(--gray-400);
            cursor: pointer;
            font-size: 1.2rem;
            transition: var(--transition);
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .item-remove button:hover {
            color: var(--danger);
            background: rgba(239, 68, 68, 0.1);
            transform: scale(1.1);
        }

        /* ============================================
           CART SUMMARY SIDEBAR
        ============================================ */
        .cart-summary {
            background: var(--white);
            border-radius: var(--radius-2xl);
            padding: var(--spacing-xl);
            position: sticky;
            top: 100px;
            border: 0.15rem solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            height: fit-content;
        }

        .cart-summary h2 {
            font-size: 1.3rem;
            margin-bottom: var(--spacing-lg);
            padding-bottom: var(--spacing-md);
            border-bottom: 2px solid var(--gray-200);
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .cart-summary h2 i {
            color: var(--primary);
        }

        .selected-items-info {
            background: linear-gradient(135deg, #fef3c7, #ffedd5);
            padding: var(--spacing-md);
            border-radius: var(--radius-lg);
            margin: var(--spacing-lg) 0;
            font-size: 0.85rem;
            color: #d97706;
            border-left: 3px solid #f59e0b;
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: var(--spacing-md) 0;
            color: var(--gray-700);
        }

        .summary-row.total {
            font-weight: 800;
            font-size: 1.2rem;
            border-top: 2px solid var(--gray-200);
            margin-top: var(--spacing-md);
            padding-top: var(--spacing-lg);
            color: var(--gray-900);
        }

        .summary-row.total span:last-child {
            color: var(--primary);
            font-size: 1.4rem;
        }

        .checkout-btn {
            display: block;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            text-align: center;
            padding: 14px;
            border-radius: var(--radius-lg);
            text-decoration: none;
            font-weight: 600;
            margin-top: var(--spacing-xl);
            transition: var(--transition);
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .checkout-btn.disabled {
            background: var(--gray-300);
            cursor: not-allowed;
            pointer-events: none;
            opacity: 0.6;
        }

        .continue-shopping-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-sm);
            color: var(--gray-500);
            text-decoration: none;
            font-size: 0.85rem;
            margin-top: var(--spacing-lg);
            transition: var(--transition);
        }

        .continue-shopping-link:hover {
            color: var(--primary);
            gap: var(--spacing-md);
        }

        /* ============================================
           COUPON SECTION
        ============================================ */
        .coupon-section {
            margin-top: var(--spacing-xl);
            padding-top: var(--spacing-lg);
            border-top: 1px solid var(--gray-200);
        }

        .coupon-input-group {
            display: flex;
            gap: var(--spacing-sm);
            margin-top: var(--spacing-md);
        }

        .coupon-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            font-size: 0.85rem;
        }

        .coupon-input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .apply-coupon-btn {
            padding: 10px 20px;
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            color: var(--gray-700);
            cursor: pointer;
            transition: var(--transition);
        }

        .apply-coupon-btn:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--white);
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 1024px) {
            .cart-content {
                grid-template-columns: 1fr;
                gap: var(--spacing-xl);
            }
            .cart-summary {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .cart-container {
                padding: 0 var(--spacing-md);
                margin: var(--spacing-xl) auto;
            }
            .cart-title {
                font-size: 1.5rem;
                flex-direction: column;
                align-items: flex-start;
            }
            .cart-header {
                display: none;
            }
            .cart-item {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
                text-align: center;
                position: relative;
                padding: var(--spacing-lg) var(--spacing-lg) var(--spacing-lg) 50px;
            }
            .item-select {
                position: absolute;
                left: var(--spacing-md);
                top: 50%;
                transform: translateY(-50%);
            }
            .item-product {
                flex-direction: column;
                text-align: center;
            }
            .quantity-controls {
                justify-content: center;
            }
            .item-price, .item-total {
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .cart-header-section {
                flex-direction: column;
            }
            .cart-stats {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="cart-container">
        <!-- CART HEADER -->
        <div class="cart-header-section">
            <div class="cart-title">
                <span><i class="fas fa-shopping-cart"></i> Shopping Cart</span>
                <div class="cart-stats">
                    <div class="select-all-wrapper">
                        <input type="checkbox" id="selectAllCheckbox" class="select-all-checkbox">
                        <label for="selectAllCheckbox" style="font-size: 1rem;">Select All Items</label>
                    </div>
                    <button class="delete-selected" id="deleteSelectedBtn" style="display: none;">
                        <i class="fas fa-trash-alt"></i> Delete Selected
                    </button>
                </div>
            </div>
        </div>
        
        <?php if(empty($cart_items)): ?>
            <!-- EMPTY CART STATE -->
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added anything to your cart yet</p>
                <a href="index.php" class="continue-shopping">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <!-- CART CONTENT -->
            <div class="cart-content">
                <!-- CART ITEMS -->
                <div class="cart-items">
                    <div class="cart-header">
                        <div><i class="fas fa-check-circle"></i></div>
                        <div>Product</div>
                        <div>Price</div>
                        <div>Quantity</div>
                        <div>Total</div>
                        <div></div>
                    </div>
                    
                    <?php foreach($cart_items as $item): ?>
                        <div class="cart-item" id="cart-item-<?php echo $item['product_id']; ?>" data-price="<?php echo $item['price']; ?>" data-quantity="<?php echo $item['quantity']; ?>">
                            <div class="item-select">
                                <input type="checkbox" class="item-checkbox" data-id="<?php echo $item['product_id']; ?>" data-price="<?php echo $item['price']; ?>" data-quantity="<?php echo $item['quantity']; ?>">
                            </div>
                            <div class="item-product">
                                <img src="<?php echo $item['image_url']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <div>
                                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                    <p class="item-stock"><i class="fas fa-check-circle"></i> In Stock</p>
                                </div>
                            </div>
                            <div class="item-price">৳<?php echo number_format($item['price']); ?></div>
                            <div class="item-quantity">
                                <div class="quantity-controls">
                                    <button class="qty-btn minus-btn" data-id="<?php echo $item['product_id']; ?>">-</button>
                                    <input type="text" id="qty-<?php echo $item['product_id']; ?>" value="<?php echo $item['quantity']; ?>" readonly>
                                    <button class="qty-btn plus-btn" data-id="<?php echo $item['product_id']; ?>">+</button>
                                </div>
                            </div>
                            <div class="item-total" id="total-<?php echo $item['product_id']; ?>">
                                ৳<?php echo number_format($item['subtotal']); ?>
                            </div>
                            <div class="item-remove">
                                <button class="remove-btn" data-id="<?php echo $item['product_id']; ?>" title="Remove Item">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- CART SUMMARY -->
                <div class="cart-summary">
                    <h2><i class="fas fa-receipt"></i> Order Summary</h2>
                    
                    <div class="selected-items-info" id="selectedInfo">
                        <i class="fas fa-info-circle"></i> No items selected
                    </div>
                    
                    <div class="summary-row">
                        <span>Selected Subtotal:</span>
                        <span id="selectedSubtotal">৳0</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping Fee:</span>
                        <span id="shipping">৳0</span>
                    </div>
                    <div class="summary-row">
                        <span>Estimated Tax:</span>
                        <span>৳0</span>
                    </div>
                    <div class="summary-row total">
                        <span>Selected Total:</span>
                        <span id="selectedGrandTotal">৳0</span>
                    </div>
                    
                    <!-- Coupon Section
                    <div class="coupon-section">
                        <div class="coupon-input-group">
                            <input type="text" class="coupon-input" placeholder="Enter coupon code" id="couponCode">
                            <button class="apply-coupon-btn" id="applyCouponBtn">
                                <i class="fas fa-ticket-alt"></i> Apply
                            </button>
                        </div>
                    </div>
                    -->
                
                    <a href="#" id="checkoutBtn" class="checkout-btn disabled">
                        <i class="fas fa-arrow-right"></i> Checkout Selected Items
                    </a>
                    
                    <a href="index.php" class="continue-shopping-link">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>

<script>
// Update quantity
function updateQuantity(productId, newQuantity) {
    if (newQuantity < 1) return;
    
    fetch('process/update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId + '&quantity=' + newQuantity
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('qty-' + productId).value = newQuantity;
            document.getElementById('total-' + productId).innerHTML = '৳' + data.item_total;
            
            // Update checkbox data
            const checkbox = document.querySelector(`.item-checkbox[data-id="${productId}"]`);
            if (checkbox) {
                checkbox.setAttribute('data-quantity', newQuantity);
            }
            
            updateCartTotals();
            updateSelectedTotals();
            
            if(typeof window.updateCartBadge === 'function') {
                window.updateCartBadge();
            }
        } else {
            showNotification(data.message || 'Error updating quantity', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating cart', 'error');
    });
}

// Remove from cart
function removeFromCart(productId) {
    if (confirm('Remove this item from cart?')) {
        fetch('process/remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const cartItem = document.getElementById('cart-item-' + productId);
                cartItem.remove();
                updateCartTotals();
                updateSelectedTotals();
                
                if(typeof window.updateCartBadge === 'function') {
                    window.updateCartBadge();
                }
                
                if (document.querySelectorAll('.cart-item').length === 0) {
                    location.reload();
                }
                
                showNotification('Item removed from cart', 'success');
            } else {
                showNotification('Error removing item', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error removing item', 'error');
        });
    }
}

// Update cart totals
function updateCartTotals() {
    fetch('process/get_cart_totals.php')
        .then(response => response.json())
        .then(data => {
            // Update any necessary totals
        })
        .catch(error => console.error('Error:', error));
}

// Update selected items totals
function updateSelectedTotals() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    const selectedCount = checkboxes.length;
    let selectedSubtotal = 0;
    
    checkboxes.forEach(checkbox => {
        const price = parseFloat(checkbox.getAttribute('data-price'));
        const quantity = parseInt(checkbox.getAttribute('data-quantity'));
        selectedSubtotal += price * quantity;
    });
    
    const shipping = selectedSubtotal >= 5000 ? 0 : (selectedCount > 0 ? 100 : 0);
    const grandTotal = selectedSubtotal + shipping;
    
    document.getElementById('selectedSubtotal').innerHTML = '৳' + selectedSubtotal.toLocaleString();
    document.getElementById('shipping').innerHTML = selectedCount > 0 ? (shipping === 0 ? '<i class="fas fa-gift"></i> Free' : '৳100') : '৳0';
    document.getElementById('selectedGrandTotal').innerHTML = '৳' + grandTotal.toLocaleString();
    
    // Update selected info text
    const selectedInfo = document.getElementById('selectedInfo');
    if (selectedCount === 0) {
        selectedInfo.innerHTML = '<i class="fas fa-info-circle"></i> No items selected';
        document.getElementById('checkoutBtn').classList.add('disabled');
    } else {
        selectedInfo.innerHTML = `<i class="fas fa-check-circle"></i> ${selectedCount} item${selectedCount > 1 ? 's' : ''} selected for checkout`;
        document.getElementById('checkoutBtn').classList.remove('disabled');
    }
    
    // Show/hide delete selected button
    const deleteBtn = document.getElementById('deleteSelectedBtn');
    if (deleteBtn) {
        deleteBtn.style.display = selectedCount > 0 ? 'flex' : 'none';
    }
}

// Delete selected items
function deleteSelectedItems() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    if (checkboxes.length === 0) return;
    
    if (confirm(`Delete ${checkboxes.length} item(s) from cart?`)) {
        const productIds = Array.from(checkboxes).map(cb => cb.getAttribute('data-id'));
        let deletedCount = 0;
        
        productIds.forEach(productId => {
            fetch('process/remove_from_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const cartItem = document.getElementById('cart-item-' + productId);
                    if (cartItem) cartItem.remove();
                    deletedCount++;
                    
                    if (deletedCount === productIds.length) {
                        updateCartTotals();
                        updateSelectedTotals();
                        
                        if (typeof window.updateCartBadge === 'function') {
                            window.updateCartBadge();
                        }
                        
                        if (document.querySelectorAll('.cart-item').length === 0) {
                            location.reload();
                        }
                        
                        showNotification('Selected items removed', 'success');
                    }
                }
            });
        });
    }
}

// Checkout selected items
function checkoutSelected() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    if (checkboxes.length === 0) {
        showNotification('Please select items to checkout', 'error');
        return;
    }
    
    const selectedItems = [];
    checkboxes.forEach(checkbox => {
        selectedItems.push({
            id: checkbox.getAttribute('data-id'),
            quantity: parseInt(checkbox.getAttribute('data-quantity'))
        });
    });
    
    // Pass selected items as URL parameter
    const encodedItems = encodeURIComponent(JSON.stringify(selectedItems));
    window.location.href = 'checkout.php?selected=' + encodedItems;
}

// Show notification
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

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Select All checkbox
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const allCheckboxes = document.querySelectorAll('.item-checkbox');
            allCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateSelectedTotals();
        });
    }
    
    // Individual checkboxes
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedTotals();
            
            if (selectAllCheckbox) {
                const allCheckboxes = document.querySelectorAll('.item-checkbox');
                const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            }
        });
    });
    
    // Minus buttons
    document.querySelectorAll('.minus-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const qtyInput = document.getElementById('qty-' + productId);
            const currentQty = parseInt(qtyInput.value);
            if (currentQty > 1) {
                updateQuantity(productId, currentQty - 1);
            }
        });
    });
    
    // Plus buttons
    document.querySelectorAll('.plus-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const qtyInput = document.getElementById('qty-' + productId);
            const currentQty = parseInt(qtyInput.value);
            updateQuantity(productId, currentQty + 1);
        });
    });
    
    // Remove buttons
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            removeFromCart(productId);
        });
    });
    
    // Delete selected button
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    if (deleteSelectedBtn) {
        deleteSelectedBtn.addEventListener('click', deleteSelectedItems);
    }
    
    // Checkout button
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            checkoutSelected();
        });
    }
    
    // Apply coupon button
    const applyCouponBtn = document.getElementById('applyCouponBtn');
    if (applyCouponBtn) {
        applyCouponBtn.addEventListener('click', function() {
            const couponCode = document.getElementById('couponCode').value;
            if (couponCode) {
                showNotification('Coupon feature coming soon!', 'info');
            } else {
                showNotification('Please enter a coupon code', 'error');
            }
        });
    }
    
    // Initialize
    updateSelectedTotals();
});

// CSS animations
const style = document.createElement('style');
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
</script>

</body>
</html>