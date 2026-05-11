<?php
session_start();
include 'config/db_connect.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

$user_id = $_SESSION['user_id'];
// Using simple SELECT without foreign keys
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
        .orders-container {
            max-width: 1000px;
            margin: 60px auto;
            padding: 0 20px;
        }
        .orders-title {
            font-size: 2rem;
            margin-bottom: 30px;
        }
        .order-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .order-number {
            font-weight: 600;
            color: var(--primary);
        }
        .order-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-processing { background: #dbeafe; color: #2563eb; }
        .status-shipped { background: #e0e7ff; color: #4f46e5; }
        .status-delivered { background: #dcfce7; color: #16a34a; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }
        .order-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .order-total {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
        }
        .view-btn {
            background: var(--primary);
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }
        .view-btn:hover {
            background: var(--primary-dark);
        }
        .empty-orders {
            text-align: center;
            padding: 80px;
            background: white;
            border-radius: 20px;
        }
        .empty-orders i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<main>
    <div class="orders-container">
        <h1 class="orders-title">My Orders</h1>
        
        <?php if(mysqli_num_rows($orders_result) > 0): ?>
            <?php while($order = mysqli_fetch_assoc($orders_result)): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-number"><?php echo $order['order_number']; ?></div>
                            <div class="order-date"><?php echo date('F j, Y', strtotime($order['created_at'])); ?></div>
                        </div>
                        <div>
                            <span class="order-status status-<?php echo $order['order_status']; ?>">
                                <?php echo ucfirst($order['order_status']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="order-details">
                        <div>
                            <div>Payment: <?php echo ucfirst($order['payment_method']); ?></div>
                            <div>Items: <?php 
                                $item_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM order_items WHERE order_id = {$order['id']}"));
                                echo $item_count['count'];
                            ?> products</div>
                        </div>
                        <div class="order-total">৳<?php echo number_format($order['total_amount']); ?></div>
                        <a href="order_details.php?id=<?php echo $order['id']; ?>" class="view-btn">View Details →</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-orders">
                <i class="fas fa-shopping-bag"></i>
                <h2>No orders yet</h2>
                <p>You haven't placed any orders yet</p>
                <a href="index.php" class="p-btn" style="display: inline-block; width: auto; padding: 12px 30px; margin-top: 20px;">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>