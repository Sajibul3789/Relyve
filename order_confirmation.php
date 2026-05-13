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
        .confirmation-container {
            max-width: 800px;
            margin: 60px auto;
            padding: 0 20px;
            text-align: center;
        }
        .success-icon {
            font-size: 5rem;
            color: #22c55e;
            margin-bottom: 20px;
        }
        .confirmation-container h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .order-info {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin: 30px 0;
            text-align: left;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        .info-item {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .info-item label {
            font-size: 0.8rem;
            color: #666;
            display: block;
        }
        .info-item .value {
            font-weight: 600;
            font-size: 1rem;
            margin-top: 5px;
        }
        .items-list {
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 10px;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            font-weight: 700;
            font-size: 1.2rem;
            border-top: 2px solid #eee;
            margin-top: 10px;
        }
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        .btn-primary, .btn-secondary {
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }
        .btn-primary {
            background: #f97316;
            color: white;
        }
        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="confirmation-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Thank You for Your Order!</h1>
        <p>Your order has been placed successfully.</p>
        
        <div class="order-info">
            <div class="info-grid">
                <div class="info-item">
                    <label>Order Number</label>
                    <div class="value"><?php echo $order['order_number']; ?></div>
                </div>
                <div class="info-item">
                    <label>Order Date</label>
                    <div class="value"><?php echo date('F j, Y', strtotime($order['created_at'])); ?></div>
                </div>
                <div class="info-item">
                    <label>Payment Method</label>
                    <div class="value"><?php echo ucfirst($order['payment_method']); ?></div>
                </div>
                <div class="info-item">
                    <label>Order Status</label>
                    <div class="value"><?php echo ucfirst($order['order_status']); ?></div>
                </div>
            </div>
            
            <div class="items-list">
                <h3>Order Items</h3>
                <?php while($item = mysqli_fetch_assoc($items_result)): ?>
                    <div class="item-row">
                        <span><?php echo $item['product_name']; ?> x<?php echo $item['quantity']; ?></span>
                        <span>৳<?php echo number_format($item['price'] * $item['quantity']); ?></span>
                    </div>
                <?php endwhile; ?>
                <div class="total-row">
                    <span>Total Amount</span>
                    <span>৳<?php echo number_format($order['total_amount']); ?></span>
                </div>
            </div>
            
            <div class="shipping-info" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                <h3>Shipping Address</h3>
                <p><?php echo nl2br($order['shipping_address']); ?></p>
                <p>Phone: <?php echo $order['shipping_phone']; ?></p>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="index.php" class="btn-primary">Continue Shopping</a>
            <a href="my_orders.php" class="btn-secondary">View My Orders</a>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>