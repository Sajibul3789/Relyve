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
    <style>
        .cart-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .cart-title {
            font-size: 1.8rem;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        .select-all {
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 0.9rem;
            cursor: pointer;
        }
        .select-all input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 16px;
        }
        .empty-cart i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }
        .continue-shopping {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }
        .cart-content {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
        }
        .cart-items {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .cart-header {
            display: grid;
            grid-template-columns: 0.5fr 3fr 1fr 1.5fr 1fr 0.5fr;
            background: #f8f9fa;
            padding: 12px 20px;
            font-weight: 600;
            border-bottom: 1px solid #eee;
        }
        .cart-item {
            display: grid;
            grid-template-columns: 0.5fr 3fr 1fr 1.5fr 1fr 0.5fr;
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            align-items: center;
            transition: background 0.2s;
        }
        .cart-item:hover {
            background: #fafafa;
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
            gap: 15px;
            align-items: center;
        }
        .item-product img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
        }
        .item-product h3 {
            font-size: 0.95rem;
            margin-bottom: 4px;
        }
        .item-stock {
            color: #22c55e;
            font-size: 0.7rem;
        }
        .item-price {
            font-weight: 600;
            color: var(--primary);
        }
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .qty-btn {
            width: 28px;
            height: 28px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            color: var(--primary);
            transition: all 0.2s;
        }
        .qty-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .quantity-controls input {
            width: 45px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 5px;
            background: white;
        }
        .item-total {
            font-weight: 700;
            color: var(--primary);
        }
        .item-remove button {
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.2s;
        }
        .item-remove button:hover {
            color: #ef4444;
            transform: scale(1.1);
        }
        .cart-summary {
            background: white;
            border-radius: 16px;
            padding: 20px;
            position: sticky;
            top: 100px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .cart-summary h2 {
            font-size: 1.2rem;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }
        .summary-row.total {
            font-weight: 700;
            font-size: 1.2rem;
            border-top: 1px solid #eee;
            margin-top: 10px;
            padding-top: 15px;
        }
        .selected-items-info {
            background: #fef3c7;
            padding: 10px;
            border-radius: 8px;
            margin: 10px 0;
            font-size: 0.85rem;
            color: #d97706;
        }
        .checkout-btn {
            display: block;
            background: var(--primary);
            color: white;
            text-align: center;
            padding: 14px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.2s;
        }
        .checkout-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        .checkout-btn.disabled {
            background: #ccc;
            cursor: not-allowed;
            pointer-events: none;
        }
        .continue-shopping-link {
            display: block;
            text-align: center;
            color: var(--text-light);
            text-decoration: none;
            font-size: 0.85rem;
            margin-top: 15px;
        }
        .delete-selected {
            background: white;
            border: 1px solid #ef4444;
            color: #ef4444;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .delete-selected:hover {
            background: #ef4444;
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        @media (max-width: 768px) {
            .cart-content {
                grid-template-columns: 1fr;
            }
            .cart-header {
                display: none;
            }
            .cart-item {
                grid-template-columns: 1fr;
                gap: 12px;
                text-align: center;
                position: relative;
                padding-left: 40px;
            }
            .item-select {
                position: absolute;
                left: 15px;
                top: 50%;
                transform: translateY(-50%);
            }
            .item-product {
                flex-direction: column;
            }
            .quantity-controls {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="cart-container">
        <div class="cart-title">
            <span><i class="fas fa-shopping-cart"></i> Shopping Cart (<?php echo count($cart_items); ?> items)</span>
            <div class="action-buttons">
                <button class="delete-selected" id="deleteSelectedBtn" style="display: none;">
                    <i class="fas fa-trash-alt"></i> Delete Selected
                </button>
            </div>
        </div>
        
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
                        <div><input type="checkbox" id="selectAllCheckbox"></div>
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
                                <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>">
                                <div>
                                    <h3><?php echo $item['name']; ?></h3>
                                    <p class="item-stock">✓ In Stock</p>
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
                                <button class="remove-btn" data-id="<?php echo $item['product_id']; ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="cart-summary">
                    <h2>Order Summary</h2>
                    <div class="selected-items-info" id="selectedInfo">
                        <i class="fas fa-info-circle"></i> No items selected
                    </div>
                    <div class="summary-row">
                        <span>Selected Subtotal:</span>
                        <span id="selectedSubtotal">৳0</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span id="shipping">৳0</span>
                    </div>
                    <div class="summary-row total">
                        <span>Selected Total:</span>
                        <span id="selectedGrandTotal">৳0</span>
                    </div>
                    <a href="#" id="checkoutBtn" class="checkout-btn disabled">
                        <i class="fas fa-arrow-right"></i> Checkout Selected Items
                    </a>
                    <a href="index.php" class="continue-shopping-link">← Continue Shopping</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

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
            
            // Update the data-quantity attribute
            const cartItem = document.getElementById('cart-item-' + productId);
            cartItem.setAttribute('data-quantity', newQuantity);
            
            // Update checkbox data
            const checkbox = document.querySelector(`.item-checkbox[data-id="${productId}"]`);
            if (checkbox && checkbox.checked) {
                checkbox.setAttribute('data-quantity', newQuantity);
                updateSelectedTotals();
            }
            
            updateCartTotals();
        } else {
            alert(data.message || 'Error updating quantity');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating cart');
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
                updateCartCount();
                updateSelectedTotals();
                
                if (document.querySelectorAll('.cart-item').length === 0) {
                    location.reload();
                }
            } else {
                alert('Error removing item');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing item');
        });
    }
}

// Update cart totals (all items)
function updateCartTotals() {
    fetch('process/get_cart_totals.php')
    .then(response => response.json())
    .then(data => {
        // Update the total items count in title
        const itemCount = document.querySelectorAll('.cart-item').length;
        document.querySelector('.cart-title span').innerHTML = `<i class="fas fa-shopping-cart"></i> Shopping Cart (${itemCount} items)`;
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
    document.getElementById('shipping').innerHTML = selectedCount > 0 ? (shipping === 0 ? 'Free' : '৳100') : '৳0';
    document.getElementById('selectedGrandTotal').innerHTML = '৳' + grandTotal.toLocaleString();
    
    // Update selected info text
    const selectedInfo = document.getElementById('selectedInfo');
    if (selectedCount === 0) {
        selectedInfo.innerHTML = '<i class="fas fa-info-circle"></i> No items selected';
        document.getElementById('checkoutBtn').classList.add('disabled');
    } else {
        selectedInfo.innerHTML = `<i class="fas fa-check-circle"></i> ${selectedCount} item${selectedCount > 1 ? 's' : ''} selected`;
        document.getElementById('checkoutBtn').classList.remove('disabled');
    }
    
    // Show/hide delete selected button
    const deleteBtn = document.getElementById('deleteSelectedBtn');
    if (selectedCount > 0) {
        deleteBtn.style.display = 'flex';
    } else {
        deleteBtn.style.display = 'none';
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
            fetch('process/remove_from_cart_process.php', {
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
                    deletedCount++;
                    
                    if (deletedCount === productIds.length) {
                        updateCartTotals();
                        updateCartCount();
                        updateSelectedTotals();
                        
                        if (document.querySelectorAll('.cart-item').length === 0) {
                            location.reload();
                        }
                    }
                }
            });
        });
    }
}

// Update cart count badge
function updateCartCount() {
    fetch('process/get_cart_count.php')
    .then(response => response.json())
    .then(data => {
        document.querySelectorAll('.badge').forEach(badge => {
            badge.textContent = data.count;
        });
    })
    .catch(error => console.error('Error:', error));
}

// Proceed to checkout with selected items
function checkoutSelected() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Please select items to checkout');
        return;
    }
    
    const selectedItems = [];
    checkboxes.forEach(checkbox => {
        selectedItems.push({
            id: checkbox.getAttribute('data-id'),
            quantity: parseInt(checkbox.getAttribute('data-quantity'))
        });
    });
    
    // Store selected items in session storage
    sessionStorage.setItem('checkoutItems', JSON.stringify(selectedItems));
    
    // Redirect to checkout
    window.location.href = 'checkout.php?selected=1';
}

// Update checkbox data when quantity changes
function updateCheckboxData(productId, quantity) {
    const checkbox = document.querySelector(`.item-checkbox[data-id="${productId}"]`);
    if (checkbox) {
        checkbox.setAttribute('data-quantity', quantity);
    }
}

// Event listeners
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
            
            // Update select all checkbox state
            const allCheckboxes = document.querySelectorAll('.item-checkbox');
            const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allChecked;
            }
        });
    });
    
    // Minus buttons
    document.querySelectorAll('.minus-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let productId = this.getAttribute('data-id');
            let qtyInput = document.getElementById('qty-' + productId);
            let currentQty = parseInt(qtyInput.value);
            if (currentQty > 1) {
                updateQuantity(productId, currentQty - 1);
            }
        });
    });
    
    // Plus buttons
    document.querySelectorAll('.plus-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let productId = this.getAttribute('data-id');
            let qtyInput = document.getElementById('qty-' + productId);
            let currentQty = parseInt(qtyInput.value);
            updateQuantity(productId, currentQty + 1);
        });
    });
    
    // Remove buttons
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let productId = this.getAttribute('data-id');
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
    
    // Initialize 
    updateSelectedTotals();
    updateCartCount();
});
</script>

</body>
</html>