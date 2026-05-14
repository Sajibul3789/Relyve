<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$orders_sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$orders_result = mysqli_query($conn, $orders_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* ============================================
           ORDERS CONTAINER
        ============================================ */
        .orders-container {
            max-width: 1200px;
            margin: var(--spacing-2xl) auto;
            padding: 0 var(--spacing-xl);
        }

        /* Page Header */
        .page-header {
            margin-bottom: var(--spacing-2xl);
            padding-bottom: var(--spacing-lg);
            border-bottom: 2px solid var(--gray-200);
        }

        .page-header h1 {
            font-size: 2rem;
            margin-bottom: var(--spacing-xs);
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }

        .page-header h1 i {
            color: var(--primary);
        }

        .page-header p {
            color: var(--gray-500);
            font-size: 0.9rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--spacing-lg);
            margin-bottom: var(--spacing-2xl);
        }

        .stat-card {
            background: var(--white);
            padding: var(--spacing-lg);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            text-align: center;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-light);
        }

        .stat-card i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: var(--spacing-sm);
        }

        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--gray-900);
            display: block;
            margin-bottom: var(--spacing-xs);
        }

        .stat-card .stat-label {
            font-size: 0.8rem;
            color: var(--gray-500);
        }

        /* Order Card */
        .order-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            margin-bottom: var(--spacing-lg);
            overflow: hidden;
            transition: var(--transition);
        }

        .order-card:hover {
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: var(--spacing-md);
            padding: var(--spacing-lg) var(--spacing-xl);
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            border-bottom: 1px solid var(--gray-200);
        }

        .order-info h3 {
            font-size: 1rem;
            margin-bottom: var(--spacing-xs);
            color: var(--gray-800);
        }

        .order-number {
            font-weight: 600;
            color: var(--primary);
            font-size: 1rem;
        }

        .order-date {
            color: var(--gray-500);
            font-size: 0.75rem;
        }

        .order-status {
            padding: 6px 14px;
            border-radius: var(--radius-full);
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-xs);
        }

        .status-pending { background: #fef3c7; color: #d97706; }
        .status-processing { background: #dbeafe; color: #2563eb; }
        .status-shipped { background: #e0e7ff; color: #4f46e5; }
        .status-delivered { background: #dcfce7; color: #16a34a; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }

        .order-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: var(--spacing-lg);
            padding: var(--spacing-lg) var(--spacing-xl);
        }

        .order-details {
            display: flex;
            gap: var(--spacing-xl);
            flex-wrap: wrap;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .detail-item i {
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

        .detail-item .detail-label {
            font-size: 0.7rem;
            color: var(--gray-500);
            display: block;
        }

        .detail-item .detail-value {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--gray-800);
        }

        .order-total {
            text-align: right;
        }

        .total-label {
            font-size: 0.7rem;
            color: var(--gray-500);
            display: block;
        }

        .total-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }

        .view-btn {
            background: var(--white);
            color: var(--primary);
            padding: 8px 20px;
            border-radius: var(--radius-lg);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            transition: var(--transition);
            border: 1px solid var(--primary);
        }

        .view-btn:hover {
            background: var(--primary);
            color: var(--white);
            transform: translateY(-2px);
        }

        /* Empty State */
        .empty-orders {
            text-align: center;
            padding: var(--spacing-3xl);
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
        }

        .empty-orders i {
            font-size: 4rem;
            color: var(--gray-300);
            margin-bottom: var(--spacing-lg);
        }

        .empty-orders h2 {
            font-size: 1.5rem;
            margin-bottom: var(--spacing-md);
            color: var(--gray-800);
        }

        .empty-orders p {
            color: var(--gray-500);
            margin-bottom: var(--spacing-xl);
        }

        .shop-now-btn {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: 12px 30px;
            border-radius: var(--radius-lg);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .shop-now-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--spacing-md);
            }
        }

        @media (max-width: 768px) {
            .orders-container {
                padding: 0 var(--spacing-md);
                margin: var(--spacing-xl) auto;
            }
            .page-header h1 {
                font-size: 1.5rem;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .order-body {
                flex-direction: column;
                align-items: flex-start;
            }
            .order-details {
                flex-direction: column;
                gap: var(--spacing-md);
            }
            .order-total {
                text-align: left;
                width: 100%;
            }
            .view-btn {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-sm);
            }
            .stat-card {
                padding: var(--spacing-md);
            }
            .stat-card .stat-value {
                font-size: 1.5rem;
            }
            .order-header, .order-body {
                padding: var(--spacing-md);
            }
        }
    </style>
</head>
<body>

<main>
    <div class="orders-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>
                <i class="fas fa-shopping-bag"></i>
                My Orders
            </h1>
            <p>Track and manage all your orders in one place</p>
        </div>

        <?php if(mysqli_num_rows($orders_result) > 0): 
            // Calculate order statistics
            $total_orders = mysqli_num_rows($orders_result);
            $total_spent = 0;
            $pending_orders = 0;
            $delivered_orders = 0;
            
            mysqli_data_seek($orders_result, 0);
            while($stat_order = mysqli_fetch_assoc($orders_result)) {
                $total_spent += $stat_order['total_amount'];
                if($stat_order['order_status'] == 'pending') $pending_orders++;
                if($stat_order['order_status'] == 'delivered') $delivered_orders++;
            }
            mysqli_data_seek($orders_result, 0);
        ?>
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="stat-value"><?php echo $total_orders; ?></span>
                    <span class="stat-label">Total Orders</span>
                </div>
                <div class="stat-card">
                    <i class="fas fa-taka-sign"></i>
                    <span class="stat-value">৳<?php echo number_format($total_spent); ?></span>
                    <span class="stat-label">Total Spent</span>
                </div>
                <div class="stat-card">
                    <i class="fas fa-clock"></i>
                    <span class="stat-value"><?php echo $pending_orders; ?></span>
                    <span class="stat-label">Pending Orders</span>
                </div>
                <div class="stat-card">
                    <i class="fas fa-check-circle"></i>
                    <span class="stat-value"><?php echo $delivered_orders; ?></span>
                    <span class="stat-label">Delivered</span>
                </div>
            </div>

            <!-- Orders List -->
            <?php while($order = mysqli_fetch_assoc($orders_result)): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <h3 class="order-number">#<?php echo $order['order_number']; ?></h3>
                            <div class="order-date">
                                <i class="far fa-calendar-alt"></i> 
                                <?php echo date('F j, Y', strtotime($order['created_at'])); ?>
                            </div>
                        </div>
                        <div>
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
                    
                    <div class="order-body">
                        <div class="order-details">
                            <div class="detail-item">
                                <i class="fas fa-credit-card"></i>
                                <div>
                                    <span class="detail-label">Payment</span>
                                    <span class="detail-value"><?php echo ucfirst($order['payment_method']); ?></span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-receipt"></i>
                                <div>
                                    <span class="detail-label">Payment Status</span>
                                    <span class="detail-value"><?php echo ucfirst($order['payment_status']); ?></span>
                                </div>
                            </div>
                            <?php if($order['order_status'] == 'delivered'): ?>
                            <div class="detail-item">
                                <i class="fas fa-calendar-check"></i>
                                <div>
                                    <span class="detail-label">Delivered On</span>
                                    <span class="detail-value">
                                        <?php echo date('M j, Y', strtotime($order['updated_at'] ?? $order['created_at'])); ?>
                                    </span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="order-total">
                            <span class="total-label">Total Amount</span>
                            <div class="total-amount">৳<?php echo number_format($order['total_amount']); ?></div>
                        </div>
                        
                        <a href="order_details.php?id=<?php echo $order['id']; ?>" class="view-btn">
                            View Details <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <!-- Empty State -->
            <div class="empty-orders">
                <i class="fas fa-shopping-bag"></i>
                <h2>No orders yet</h2>
                <p>You haven't placed any orders yet. Start shopping to see your orders here!</p>
                <a href="index.php" class="shop-now-btn">
                    <i class="fas fa-shopping-cart"></i> Start Shopping
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>