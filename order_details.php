<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

$order_sql = "SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id";
$order_result = mysqli_query($conn, $order_sql);
$order = mysqli_fetch_assoc($order_result);

if(!$order) {
    header("Location: my_orders.php");
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
    <title>Order Details - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* ============================================
           DETAILS CONTAINER
        ============================================ */
        .details-container {
            max-width: 1000px;
            margin: var(--spacing-2xl) auto;
            padding: 0 var(--spacing-xl);
        }

        /* Breadcrumb */
        .breadcrumb {
            margin-bottom: var(--spacing-xl);
        }

        .breadcrumb a {
            color: var(--gray-500);
            text-decoration: none;
            font-size: 0.85rem;
            transition: var(--transition);
        }

        .breadcrumb a:hover {
            color: var(--primary);
        }

        .breadcrumb span {
            color: var(--gray-400);
            margin: 0 var(--spacing-xs);
        }

        .breadcrumb .current {
            color: var(--primary);
            font-weight: 500;
        }

        /* Order Header Card */
        .order-header {
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            padding: var(--spacing-xl);
            margin-bottom: var(--spacing-xl);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .order-header:hover {
            box-shadow: var(--shadow-md);
        }

        .order-header h1 {
            font-size: 1.5rem;
            margin-bottom: var(--spacing-sm);
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .order-header h1 i {
            color: var(--primary);
        }

        .order-date {
            color: var(--gray-500);
            font-size: 0.85rem;
            margin-bottom: var(--spacing-md);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: 6px 16px;
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-badge i {
            font-size: 0.7rem;
        }

        .status-pending { background: #fef3c7; color: #d97706; }
        .status-processing { background: #dbeafe; color: #2563eb; }
        .status-shipped { background: #e0e7ff; color: #4f46e5; }
        .status-delivered { background: #dcfce7; color: #16a34a; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--spacing-xl);
            margin-bottom: var(--spacing-xl);
        }

        /* Info Cards */
        .info-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            padding: var(--spacing-xl);
            transition: var(--transition);
        }

        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-light);
        }

        .info-card h3 {
            font-size: 1rem;
            margin-bottom: var(--spacing-lg);
            color: var(--gray-700);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding-bottom: var(--spacing-sm);
            border-bottom: 2px solid var(--gray-200);
        }

        .info-card h3 i {
            color: var(--primary);
            font-size: 1rem;
        }

        .info-card p {
            margin-bottom: var(--spacing-sm);
            color: var(--gray-700);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .info-card p strong {
            color: var(--gray-800);
            width: 100px;
            display: inline-block;
        }

        /* Items Card */
        .items-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            padding: var(--spacing-xl);
            margin-bottom: var(--spacing-xl);
            transition: var(--transition);
        }

        .items-card:hover {
            box-shadow: var(--shadow-md);
        }

        .items-card h3 {
            font-size: 1.1rem;
            margin-bottom: var(--spacing-lg);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding-bottom: var(--spacing-md);
            border-bottom: 2px solid var(--gray-200);
        }

        .items-card h3 i {
            color: var(--primary);
        }

        /* Item Rows */
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

        .item-info {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            flex: 1;
        }

        .item-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-light), var(--white));
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .item-icon i {
            color: var(--primary);
            font-size: 1rem;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 500;
            color: var(--gray-800);
            margin-bottom: 4px;
        }

        .item-meta {
            font-size: 0.7rem;
            color: var(--gray-500);
        }

        .item-quantity {
            color: var(--gray-600);
            font-size: 0.85rem;
            margin: 0 var(--spacing-lg);
        }

        .item-price {
            font-weight: 600;
            color: var(--primary);
            font-size: 1rem;
            min-width: 100px;
            text-align: right;
        }

        /* Total Row */
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-lg) 0 var(--spacing-md);
            margin-top: var(--spacing-md);
            border-top: 2px solid var(--gray-200);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .total-row span:last-child {
            color: var(--primary);
            font-size: 1.3rem;
        }

        /* Order Timeline */
        .order-timeline {
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            padding: var(--spacing-xl);
            margin-bottom: var(--spacing-xl);
        }

        .order-timeline h3 {
            font-size: 1rem;
            margin-bottom: var(--spacing-lg);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .order-timeline h3 i {
            color: var(--primary);
        }

        .timeline-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
        }

        .timeline-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gray-200);
            z-index: 0;
        }

        .timeline-step {
            text-align: center;
            position: relative;
            z-index: 1;
            flex: 1;
        }

        .step-icon {
            width: 40px;
            height: 40px;
            background: var(--white);
            border: 2px solid var(--gray-200);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--spacing-sm);
            transition: var(--transition);
        }

        .timeline-step.completed .step-icon {
            background: var(--success);
            border-color: var(--success);
            color: var(--white);
        }

        .timeline-step.active .step-icon {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--white);
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .step-label {
            font-size: 0.7rem;
            color: var(--gray-500);
        }

        .timeline-step.completed .step-label,
        .timeline-step.active .step-label {
            color: var(--gray-800);
            font-weight: 500;
        }

        /* Back Button */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            background: var(--gray-100);
            color: var(--gray-700);
            padding: 10px 24px;
            border-radius: var(--radius-lg);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .back-btn:hover {
            background: var(--primary);
            color: var(--white);
            transform: translateX(-5px);
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 768px) {
            .details-container {
                padding: 0 var(--spacing-md);
                margin: var(--spacing-xl) auto;
            }
            .info-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
            }
            .order-header h1 {
                font-size: 1.2rem;
                flex-direction: column;
                align-items: flex-start;
            }
            .item-row {
                flex-direction: column;
                align-items: flex-start;
                gap: var(--spacing-sm);
            }
            .item-price {
                text-align: left;
                width: 100%;
            }
            .timeline-steps {
                flex-direction: column;
                gap: var(--spacing-lg);
            }
            .timeline-steps::before {
                display: none;
            }
            .timeline-step {
                display: flex;
                align-items: center;
                gap: var(--spacing-md);
                text-align: left;
            }
            .step-icon {
                margin: 0;
            }
        }

        @media (max-width: 480px) {
            .info-card p strong {
                width: 80px;
            }
            .item-info {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="details-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <span><i class="fas fa-chevron-right"></i></span>
            <a href="my_orders.php">My Orders</a>
            <span><i class="fas fa-chevron-right"></i></span>
            <span class="current">Order Details</span>
        </div>

        <!-- Order Header -->
        <div class="order-header">
            <h1>
                <span><i class="fas fa-receipt"></i> Order #<?php echo $order['order_number']; ?></span>
                <span class="status-badge status-<?php echo $order['order_status']; ?>">
                    <i class="fas <?php 
                        echo $order['order_status'] == 'pending' ? 'fa-clock' : 
                             ($order['order_status'] == 'processing' ? 'fa-spinner' :
                             ($order['order_status'] == 'shipped' ? 'fa-truck' :
                             ($order['order_status'] == 'delivered' ? 'fa-check-circle' : 'fa-times-circle'))); 
                    ?>"></i>
                    <?php echo strtoupper($order['order_status']); ?>
                </span>
            </h1>
            <div class="order-date">
                <i class="far fa-calendar-alt"></i>
                Placed on <?php echo date('F j, Y \a\t g:i A', strtotime($order['created_at'])); ?>
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="order-timeline">
            <h3><i class="fas fa-chart-line"></i> Order Timeline</h3>
            <div class="timeline-steps">
                <?php
                $status_order = ['pending', 'processing', 'shipped', 'delivered'];
                $current_status = $order['order_status'];
                $current_index = array_search($current_status, $status_order);
                ?>
                <div class="timeline-step <?php echo $current_index >= 0 ? 'completed' : ''; ?>">
                    <div class="step-icon"><i class="fas fa-clock"></i></div>
                    <div class="step-label">Order Placed</div>
                </div>
                <div class="timeline-step <?php echo $current_index >= 1 ? 'completed' : ($current_index == 0 ? 'active' : ''); ?>">
                    <div class="step-icon"><i class="fas fa-cog"></i></div>
                    <div class="step-label">Processing</div>
                </div>
                <div class="timeline-step <?php echo $current_index >= 2 ? 'completed' : ($current_index == 1 ? 'active' : ''); ?>">
                    <div class="step-icon"><i class="fas fa-truck"></i></div>
                    <div class="step-label">Shipped</div>
                </div>
                <div class="timeline-step <?php echo $current_index >= 3 ? 'completed' : ($current_index == 2 ? 'active' : ''); ?>">
                    <div class="step-icon"><i class="fas fa-check"></i></div>
                    <div class="step-label">Delivered</div>
                </div>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="info-grid">
            <!-- Shipping Address Card -->
            <div class="info-card">
                <h3><i class="fas fa-truck"></i> Shipping Address</h3>
                <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                <p><strong>City:</strong> <?php echo htmlspecialchars($order['shipping_city']); ?></p>
                <p><strong>Postal Code:</strong> <?php echo htmlspecialchars($order['shipping_zip']); ?></p>
                <p><strong>Phone:</strong> <i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($order['shipping_phone']); ?></p>
            </div>

            <!-- Payment Information Card -->
            <div class="info-card">
                <h3><i class="fas fa-credit-card"></i> Payment Information</h3>
                <p><strong>Method:</strong> 
                    <i class="fas <?php echo $order['payment_method'] == 'cod' ? 'fa-money-bill-wave' : ($order['payment_method'] == 'bkash' ? 'fa-mobile-alt' : 'fa-credit-card'); ?>"></i>
                    <?php echo ucfirst($order['payment_method']); ?>
                </p>
                <p><strong>Status:</strong> 
                    <span style="color: <?php echo $order['payment_status'] == 'paid' ? '#16a34a' : '#d97706'; ?>">
                        <?php echo ucfirst($order['payment_status']); ?>
                    </span>
                </p>
                <?php if($order['notes']): ?>
                    <p><strong>Notes:</strong> <?php echo htmlspecialchars($order['notes']); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Order Items Card -->
        <div class="items-card">
            <h3><i class="fas fa-box"></i> Order Items</h3>
            
            <?php while($item = mysqli_fetch_assoc($items_result)): ?>
                <div class="item-row">
                    <div class="item-info">
                        <div class="item-icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div class="item-details">
                            <div class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                            <div class="item-meta">SKU: #<?php echo $item['product_id']; ?></div>
                        </div>
                    </div>
                    <div class="item-quantity">x<?php echo $item['quantity']; ?></div>
                    <div class="item-price">৳<?php echo number_format($item['price'] * $item['quantity']); ?></div>
                </div>
            <?php endwhile; ?>
            
            <div class="total-row">
                <span>Total Amount</span>
                <span>৳<?php echo number_format($order['total_amount']); ?></span>
            </div>
        </div>

        <!-- Back Button -->
        <a href="my_orders.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>