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
        .details-container {
            max-width: 800px;
            margin: 60px auto;
            padding: 0 20px;
        }
        .order-header {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .order-header h1 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }
        .info-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .info-card h3 {
            font-size: 1rem;
            margin-bottom: 15px;
            color: var(--text-light);
        }
        .items-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .items-card h3 {
            margin-bottom: 15px;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            font-weight: 600;
            border-top: 2px solid #f0f0f0;
            margin-top: 10px;
        }
        .back-btn {
            display: inline-block;
            background: #6b7280;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 20px;
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
    <div class="details-container">
        <div class="order-header">
            <h1>Order #<?php echo $order['order_number']; ?></h1>
            <p>Placed on <?php echo date('F j, Y \a\t g:i A', strtotime($order['created_at'])); ?></p>
            <span class="status-badge status-<?php echo $order['order_status']; ?>" style="background: <?php echo $order['order_status'] == 'delivered' ? '#dcfce7' : ($order['order_status'] == 'cancelled' ? '#fee2e2' : '#fef3c7'); ?>; color: <?php echo $order['order_status'] == 'delivered' ? '#16a34a' : ($order['order_status'] == 'cancelled' ? '#dc2626' : '#d97706'); ?>">
                <?php echo strtoupper($order['order_status']); ?>
            </span>
        </div>
        
        <div class="info-grid">
            <div class="info-card">
                <h3><i class="fas fa-truck"></i> Shipping Address</h3>
                <p><?php echo nl2br($order['shipping_address']); ?></p>
                <p><?php echo $order['shipping_city']; ?> - <?php echo $order['shipping_zip']; ?></p>
                <p>Phone: <?php echo $order['shipping_phone']; ?></p>
            </div>
            <div class="info-card">
                <h3><i class="fas fa-credit-card"></i> Payment Information</h3>
                <p>Method: <?php echo ucfirst($order['payment_method']); ?></p>
                <p>Status: <?php echo ucfirst($order['payment_status']); ?></p>
                <?php if($order['notes']): ?>
                    <p>Notes: <?php echo $order['notes']; ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="items-card">
            <h3>Order Items</h3>
            <?php while($item = mysqli_fetch_assoc($items_result)): ?>
                <div class="item-row">
                    <span><?php echo $item['product_name']; ?> <strong>x<?php echo $item['quantity']; ?></strong></span>
                    <span>৳<?php echo number_format($item['price'] * $item['quantity']); ?></span>
                </div>
            <?php endwhile; ?>
            <div class="total-row">
                <span>Total</span>
                <span>৳<?php echo number_format($order['total_amount']); ?></span>
            </div>
        </div>
        
        <a href="my_orders.php" class="back-btn">← Back to Orders</a>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>