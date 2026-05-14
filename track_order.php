<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';

$order = null;
$error = '';

if(isset($_POST['track'])) {
    $order_number = mysqli_real_escape_string($conn, $_POST['order_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $sql = "SELECT o.*, u.email FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.order_number = '$order_number' AND u.email = '$email'";
    $result = mysqli_query($conn, $sql);
    $order = mysqli_fetch_assoc($result);
    
    if(!$order) {
        $error = "No order found with that order number and email";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* ============================================
           TRACK CONTAINER
        ============================================ */
        .track-container {
            max-width: 900px;
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

        /* Track Form Card */
        .track-form {
            background: var(--white);
            border-radius: var(--radius-xl);
            padding: var(--spacing-2xl);
            text-align: center;
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-md);
        }

        .track-form h1 {
            font-size: 1.8rem;
            margin-bottom: var(--spacing-sm);
            color: var(--gray-900);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-sm);
        }

        .track-form h1 i {
            color: var(--primary);
        }

        .track-form p {
            color: var(--gray-500);
            margin-bottom: var(--spacing-xl);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: var(--spacing-lg);
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: var(--spacing-sm);
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--gray-700);
        }

        .form-group label i {
            margin-right: var(--spacing-xs);
            color: var(--primary);
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }

        .track-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: 12px 30px;
            border: none;
            border-radius: var(--radius-lg);
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .track-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Error Message */
        .error {
            background: #fee2e2;
            color: #dc2626;
            padding: var(--spacing-md);
            border-radius: var(--radius-lg);
            margin-bottom: var(--spacing-lg);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            border-left: 4px solid #dc2626;
        }

        /* Order Status Card */
        .order-status {
            background: var(--white);
            border-radius: var(--radius-xl);
            padding: var(--spacing-2xl);
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-md);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .order-header {
            text-align: center;
            margin-bottom: var(--spacing-xl);
            padding-bottom: var(--spacing-lg);
            border-bottom: 2px solid var(--gray-200);
        }

        .order-header h1 {
            font-size: 1.5rem;
            margin-bottom: var(--spacing-sm);
            color: var(--gray-900);
        }

        .order-number {
            font-size: 1.1rem;
            color: var(--primary);
            font-weight: 600;
        }

        /* Status Timeline */
        .status-timeline {
            display: flex;
            justify-content: space-between;
            margin: var(--spacing-xl) 0;
            position: relative;
            flex-wrap: wrap;
        }

        .status-timeline::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 10%;
            right: 10%;
            height: 3px;
            background: var(--gray-200);
            z-index: 0;
        }

        .status-step {
            text-align: center;
            position: relative;
            z-index: 1;
            flex: 1;
            min-width: 80px;
        }

        .status-icon {
            width: 50px;
            height: 50px;
            background: var(--gray-200);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--spacing-sm);
            color: var(--gray-500);
            transition: var(--transition);
            border: 3px solid var(--white);
            box-shadow: var(--shadow-sm);
        }

        .status-step.completed .status-icon {
            background: var(--success);
            color: var(--white);
        }

        .status-step.active .status-icon {
            background: var(--primary);
            color: var(--white);
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .status-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--gray-600);
        }

        .status-step.completed .status-label,
        .status-step.active .status-label {
            color: var(--gray-800);
            font-weight: 600;
        }

        /* Order Details */
        .order-details {
            margin-top: var(--spacing-xl);
            padding: var(--spacing-lg);
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-sm) 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 500;
            color: var(--gray-700);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .detail-label i {
            color: var(--primary);
            width: 20px;
        }

        .detail-value {
            color: var(--gray-800);
            font-weight: 500;
        }

        /* Info Box */
        .info-box {
            margin-top: var(--spacing-lg);
            padding: var(--spacing-md);
            background: #fef3c7;
            border-radius: var(--radius-lg);
            border-left: 4px solid #f59e0b;
        }

        .info-box p {
            margin-bottom: 0;
            color: #92400e;
            font-size: 0.85rem;
        }

        .info-box i {
            margin-right: var(--spacing-sm);
            color: #f59e0b;
        }

        /* Help Section */
        .help-section {
            margin-top: var(--spacing-xl);
            text-align: center;
            padding: var(--spacing-lg);
            background: linear-gradient(135deg, var(--primary-light), var(--white));
            border-radius: var(--radius-lg);
        }

        .help-section p {
            margin-bottom: var(--spacing-sm);
            color: var(--gray-700);
        }

        .help-section a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 768px) {
            .track-container {
                padding: 0 var(--spacing-md);
                margin: var(--spacing-xl) auto;
            }
            .track-form {
                padding: var(--spacing-lg);
            }
            .track-form h1 {
                font-size: 1.4rem;
            }
            .order-status {
                padding: var(--spacing-lg);
            }
            .status-timeline::before {
                display: none;
            }
            .status-timeline {
                flex-direction: column;
                gap: var(--spacing-lg);
            }
            .status-step {
                display: flex;
                align-items: center;
                gap: var(--spacing-md);
                text-align: left;
            }
            .status-icon {
                margin: 0;
            }
            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: var(--spacing-xs);
            }
        }

        @media (max-width: 480px) {
            .track-form {
                padding: var(--spacing-md);
            }
        }
    </style>
</head>
<body>

<main>
    <div class="track-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <span><i class="fas fa-chevron-right"></i></span>
            <span class="current">Track Order</span>
        </div>

        <?php if(!$order): ?>
            <!-- Track Order Form -->
            <div class="track-form">
                <h1>
                    <i class="fas fa-search"></i>
                    Track Your Order
                </h1>
                <p>Enter your order number and email to track your order status</p>
                
                <?php if($error): ?>
                    <div class="error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label><i class="fas fa-hashtag"></i> Order Number</label>
                        <input type="text" name="order_number" placeholder="e.g., RELYVE123456789" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email Address</label>
                        <input type="email" name="email" placeholder="you@example.com" required>
                    </div>
                    <button type="submit" name="track" class="track-btn">
                        <i class="fas fa-search"></i> Track Order
                    </button>
                </form>
            </div>
        <?php else: ?>
            <!-- Order Status Display -->
            <div class="order-status">
                <div class="order-header">
                    <h1><i class="fas fa-box"></i> Order Status</h1>
                    <div class="order-number">#<?php echo $order['order_number']; ?></div>
                </div>
                
                <!-- Status Timeline -->
                <div class="status-timeline">
                    <?php
                    $statuses = [
                        'pending' => ['label' => 'Order Placed', 'icon' => 'fa-shopping-cart'],
                        'processing' => ['label' => 'Processing', 'icon' => 'fa-cogs'],
                        'shipped' => ['label' => 'Shipped', 'icon' => 'fa-truck'],
                        'delivered' => ['label' => 'Delivered', 'icon' => 'fa-check']
                    ];
                    $current_status = $order['order_status'];
                    $found = false;
                    foreach($statuses as $key => $status):
                        $completed = ($found || $key == $current_status);
                        $isActive = ($key == $current_status);
                        if($key == $current_status) $found = true;
                    ?>
                        <div class="status-step <?php echo $completed ? 'completed' : ''; ?> <?php echo $isActive ? 'active' : ''; ?>">
                            <div class="status-icon">
                                <i class="fas <?php echo $status['icon']; ?>"></i>
                            </div>
                            <div class="status-label"><?php echo $status['label']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Order Details -->
                <div class="order-details">
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-calendar"></i> Order Date</span>
                        <span class="detail-value"><?php echo date('F j, Y', strtotime($order['created_at'])); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-truck"></i> Estimated Delivery</span>
                        <span class="detail-value"><?php echo date('F j, Y', strtotime($order['created_at'] . ' +5 days')); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-credit-card"></i> Payment Method</span>
                        <span class="detail-value"><?php echo ucfirst($order['payment_method']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-taka-sign"></i> Total Amount</span>
                        <span class="detail-value">৳<?php echo number_format($order['total_amount']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-map-marker-alt"></i> Shipping Address</span>
                        <span class="detail-value"><?php echo htmlspecialchars($order['shipping_address']); ?></span>
                    </div>
                </div>
                
                <!-- Info Box -->
                <div class="info-box">
                    <p><i class="fas fa-info-circle"></i> <strong>Need help?</strong> If you have any questions about your order, please contact our customer support team.</p>
                </div>
                
                <!-- Help Section -->
                <div class="help-section">
                    <p><i class="fas fa-headset"></i> Need assistance with your order?</p>
                    <p>Contact us at <a href="mailto:support@relyve.com">support@relyve.com</a> or call <a href="tel:+8801234567890">+880 1234-567890</a></p>
                </div>
                
                <div style="text-align: center; margin-top: var(--spacing-xl);">
                    <a href="track-order.php" class="track-btn">
                        <i class="fas fa-search"></i> Track Another Order
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>