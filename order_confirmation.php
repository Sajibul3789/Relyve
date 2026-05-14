<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';

if(!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: index.php");
    exit();
}

$order_id = (int)$_GET['order_id'];
$user_id = $_SESSION['user_id'];

$order_sql = "SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id";
$order_result = mysqli_query($conn, $order_sql);
$order = mysqli_fetch_assoc($order_result);

if(!$order) {
    header("Location: index.php");
    exit();
}

$items_sql = "SELECT * FROM order_items WHERE order_id = $order_id";
$items_result = mysqli_query($conn, $items_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* ============================================
           CONFIRMATION CONTAINER
        ============================================ */
        .confirmation-container {
            max-width: 900px;
            margin: var(--spacing-2xl) auto;
            padding: 0 var(--spacing-xl);
        }

        /* Success Animation */
        .success-animation {
            text-align: center;
            margin-bottom: var(--spacing-2xl);
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--spacing-lg);
            animation: scaleIn 0.5s ease 0.2s both;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        .success-icon i {
            font-size: 3rem;
            color: var(--white);
        }

        .success-animation h1 {
            font-size: 2rem;
            margin-bottom: var(--spacing-sm);
            color: var(--gray-900);
        }

        .success-animation p {
            color: var(--gray-500);
            font-size: 1rem;
        }

        /* Order Info Card */
        .order-info {
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            margin: var(--spacing-xl) 0;
            box-shadow: var(--shadow-md);
        }

        /* Order Header */
        .order-header {
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            padding: var(--spacing-lg) var(--spacing-xl);
            border-bottom: 1px solid var(--gray-200);
        }

        .order-header h2 {
            font-size: 1.3rem;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .order-header h2 i {
            color: var(--primary);
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--spacing-md);
            padding: var(--spacing-xl);
            background: var(--white);
        }

        .info-item {
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            padding: var(--spacing-md) var(--spacing-lg);
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .info-item:hover {
            transform: translateY(-2px);
            border-color: var(--primary-light);
            box-shadow: var(--shadow-sm);
        }

        .info-item label {
            font-size: 0.7rem;
            color: var(--gray-500);
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: var(--spacing-xs);
        }

        .info-item .value {
            font-weight: 600;
            font-size: 1rem;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .info-item .value i {
            color: var(--primary);
            font-size: 0.9rem;
        }

        /* Order Status Badge */
        .order-status {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-xs);
            padding: 4px 12px;
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-pending { background: #fef3c7; color: #d97706; }
        .status-processing { background: #dbeafe; color: #2563eb; }
        .status-shipped { background: #e0e7ff; color: #4f46e5; }
        .status-delivered { background: #dcfce7; color: #16a34a; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }

        /* Items List */
        .items-list {
            padding: 0 var(--spacing-xl) var(--spacing-xl) var(--spacing-xl);
            border-top: 1px solid var(--gray-200);
        }

        .items-list h3 {
            font-size: 1.1rem;
            margin-bottom: var(--spacing-lg);
            padding-top: var(--spacing-lg);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .items-list h3 i {
            color: var(--primary);
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-md) 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .item-row:last-child {
            border-bottom: none;
        }

        .item-name {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }

        .item-name i {
            width: 30px;
            height: 30px;
            background: var(--gray-100);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 0.8rem;
        }

        .item-details {
            flex: 1;
        }

        .item-title {
            font-weight: 500;
            color: var(--gray-800);
            margin-bottom: 2px;
        }

        .item-meta {
            font-size: 0.7rem;
            color: var(--gray-500);
        }

        .item-price {
            font-weight: 600;
            color: var(--primary);
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-lg) 0;
            margin-top: var(--spacing-md);
            border-top: 2px solid var(--gray-200);
            font-weight: 700;
            font-size: 1.2rem;
        }

        .total-row span:last-child {
            color: var(--primary);
            font-size: 1.3rem;
        }

        /* Shipping Info */
        .shipping-info {
            padding: var(--spacing-xl);
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            border-top: 1px solid var(--gray-200);
        }

        .shipping-info h3 {
            font-size: 1rem;
            margin-bottom: var(--spacing-md);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .shipping-info h3 i {
            color: var(--primary);
        }

        .shipping-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-md);
        }

        .shipping-address, .shipping-phone {
            padding: var(--spacing-sm) 0;
        }

        .shipping-address p, .shipping-phone p {
            margin-bottom: 0;
            font-size: 0.9rem;
            color: var(--gray-700);
            line-height: 1.6;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: var(--spacing-md);
            justify-content: center;
            margin-top: var(--spacing-xl);
        }

        .btn-primary, .btn-secondary {
            padding: 12px 28px;
            border-radius: var(--radius-lg);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--gray-700);
            border: 1px solid var(--gray-200);
        }

        .btn-secondary:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 768px) {
            .confirmation-container {
                padding: 0 var(--spacing-md);
                margin: var(--spacing-xl) auto;
            }
            .success-icon {
                width: 70px;
                height: 70px;
            }
            .success-icon i {
                font-size: 2rem;
            }
            .success-animation h1 {
                font-size: 1.5rem;
            }
            .info-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-sm);
                padding: var(--spacing-lg);
            }
            .items-list {
                padding: 0 var(--spacing-lg) var(--spacing-lg) var(--spacing-lg);
            }
            .shipping-info {
                padding: var(--spacing-lg);
            }
            .shipping-details {
                grid-template-columns: 1fr;
                gap: var(--spacing-sm);
            }
            .action-buttons {
                flex-direction: column;
                gap: var(--spacing-sm);
            }
            .btn-primary, .btn-secondary {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .info-item {
                padding: var(--spacing-sm) var(--spacing-md);
            }
            .item-row {
                flex-direction: column;
                align-items: flex-start;
                gap: var(--spacing-sm);
            }
            .item-price {
                align-self: flex-end;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="confirmation-container">
        <!-- Success Animation -->
        <div class="success-animation">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h1>Thank You for Your Order!</h1>
            <p>Your order has been placed successfully. We'll notify you once it's shipped.</p>
        </div>
        
        <!-- Order Info Card -->
        <div class="order-info">
            <div class="order-header">
                <h2>
                    <i class="fas fa-receipt"></i>
                    Order Details
                </h2>
            </div>
            
            <!-- Info Grid -->
            <div class="info-grid">
                <div class="info-item">
                    <label>Order Number</label>
                    <div class="value">
                        <i class="fas fa-hashtag"></i>
                        <?php echo $order['order_number']; ?>
                    </div>
                </div>
                <div class="info-item">
                    <label>Order Date</label>
                    <div class="value">
                        <i class="far fa-calendar-alt"></i>
                        <?php echo date('F j, Y', strtotime($order['created_at'])); ?>
                    </div>
                </div>
                <div class="info-item">
                    <label>Payment Method</label>
                    <div class="value">
                        <i class="fas fa-credit-card"></i>
                        <?php echo ucfirst($order['payment_method']); ?>
                    </div>
                </div>
                <div class="info-item">
                    <label>Order Status</label>
                    <div class="value">
                        <span class="order-status status-<?php echo $order['order_status']; ?>">
                            <i class="fas <?php 
                                echo $order['order_status'] == 'pending' ? 'fa-clock' : 
                                     ($order['order_status'] == 'processing' ? 'fa-spinner' :
                                     ($order['order_status'] == 'shipped' ? 'fa-truck' :
                                     ($order['order_status'] == 'delivered' ? 'fa-check-circle' : 'fa-times-circle'))); 
                            ?>"></i>
                            <?php echo ucfirst($order['order_status']); ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Items List -->
            <div class="items-list">
                <h3>
                    <i class="fas fa-box"></i>
                    Order Items
                </h3>
                <?php while($item = mysqli_fetch_assoc($items_result)): ?>
                    <div class="item-row">
                        <div class="item-name">
                            <i class="fas fa-tag"></i>
                            <div class="item-details">
                                <div class="item-title"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                <div class="item-meta">Quantity: <?php echo $item['quantity']; ?></div>
                            </div>
                        </div>
                        <div class="item-price">৳<?php echo number_format($item['price'] * $item['quantity']); ?></div>
                    </div>
                <?php endwhile; ?>
                
                <div class="total-row">
                    <span>Total Amount</span>
                    <span>৳<?php echo number_format($order['total_amount']); ?></span>
                </div>
            </div>
            
            <!-- Shipping Info -->
            <div class="shipping-info">
                <h3>
                    <i class="fas fa-truck"></i>
                    Shipping Information
                </h3>
                <div class="shipping-details">
                    <div class="shipping-address">
                        <p><strong>Delivery Address:</strong></p>
                        <p><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                    </div>
                    <div class="shipping-phone">
                        <p><strong>Contact Number:</strong></p>
                        <p><i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($order['shipping_phone']); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="index.php" class="btn-primary">
                <i class="fas fa-shopping-cart"></i> Continue Shopping
            </a>
            <a href="my_orders.php" class="btn-secondary">
                <i class="fas fa-list"></i> View My Orders
            </a>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>