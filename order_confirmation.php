<?php
session_start();
include 'includes/navbar.php';
include 'config/db_connect.php';

if(!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: index.php");
    exit();
}

$order_id = $_GET['order_id'];
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
    <link rel="stylesheet" href="assets/css/order_confirmation.css">
</head>
<body>

<main>
    <div class="container">
        <div class="confirmation-container">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Thank You for Your Order!</h1>
            <p>Your order has been placed successfully.</p>
            
            <div class="order-info">
                <div class="info-card">
                    <h3>Order Number</h3>
                    <p><?php echo $order['order_number']; ?></p>
                </div>
                <div class="info-card">
                    <h3>Order Date</h3>
                    <p><?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                </div>
                <div class="info-card">
                    <h3>Total Amount</h3>
                    <p>৳<?php echo number_format($order['total_amount']); ?></p>
                </div>
                <div class="info-card">
                    <h3>Payment Method</h3>
                    <p><?php echo ucfirst($order['payment_method']); ?></p>
                </div>
            </div>
            
            <div class="order-details">
                <h2>Order Details</h2>
                <div class="items-list">
                    <?php while($item = mysqli_fetch_assoc($items_result)): ?>
                    <div class="order-detail-item">
                        <span><?php echo $item['product_name']; ?> x<?php echo $item['quantity']; ?></span>
                        <span>৳<?php echo number_format($item['price'] * $item['quantity']); ?></span>
                    </div>
                    <?php endwhile; ?>
                </div>
                
                <div class="shipping-info">
                    <h3>Shipping Address</h3>
                    <p><?php echo nl2br($order['shipping_address']); ?></p>
                    <p><?php echo $order['shipping_city']; ?> - <?php echo $order['shipping_zip']; ?></p>
                    <p>Phone: <?php echo $order['shipping_phone']; ?></p>
                </div>
                
                <div class="action-buttons">
                    <a href="index.php" class="continue-btn">Continue Shopping</a>
                    <a href="my-orders.php" class="orders-btn">View My Orders</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>