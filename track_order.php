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
        .track-container {
            max-width: 800px;
            margin: 60px auto;
            padding: 0 20px;
        }
        .track-form {
            background: white;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
        }
        .track-form h1 {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .track-btn {
            background: #f97316;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }
        .order-status {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-top: 30px;
        }
        .status-timeline {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
            position: relative;
        }
        .status-timeline::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 10%;
            right: 10%;
            height: 3px;
            background: #e5e7eb;
            z-index: 0;
        }
        .status-step {
            text-align: center;
            position: relative;
            z-index: 1;
            flex: 1;
        }
        .status-icon {
            width: 50px;
            height: 50px;
            background: #e5e7eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            color: #9ca3af;
        }
        .status-step.completed .status-icon {
            background: #22c55e;
            color: white;
        }
        .status-step.active .status-icon {
            background: #f97316;
            color: white;
        }
        .error {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<main>
    <div class="track-container">
        <?php if(!$order): ?>
            <div class="track-form">
                <h1>Track Your Order</h1>
                <p>Enter your order number and email to track your order</p>
                
                <?php if($error): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <input type="text" name="order_number" placeholder="Order Number (e.g., RELYVE123456789)" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email Address" required>
                    </div>
                    <button type="submit" name="track" class="track-btn">Track Order</button>
                </form>
            </div>
        <?php else: ?>
            <div class="order-status">
                <h1>Order Status</h1>
                <p>Order #<?php echo $order['order_number']; ?></p>
                
                <div class="status-timeline">
                    <?php
                    $statuses = ['pending' => 'Order Placed', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered'];
                    $current_status = $order['order_status'];
                    $found = false;
                    foreach($statuses as $key => $label):
                        $completed = ($found || $key == $current_status);
                        if($key == $current_status) $found = true;
                    ?>
                        <div class="status-step <?php echo $completed ? 'completed' : ''; ?>">
                            <div class="status-icon">
                                <i class="fas <?php echo $key == 'pending' ? 'fa-shopping-cart' : ($key == 'processing' ? 'fa-cogs' : ($key == 'shipped' ? 'fa-truck' : 'fa-check')); ?>"></i>
                            </div>
                            <div><?php echo $label; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div style="margin-top: 30px; padding: 20px; background: #f9fafb; border-radius: 12px;">
                    <p><strong>Estimated Delivery:</strong> <?php echo date('F j, Y', strtotime($order['created_at'] . ' +5 days')); ?></p>
                    <p><strong>Shipping Address:</strong> <?php echo $order['shipping_address']; ?></p>
                </div>
                
                <a href="track-order.php" class="track-btn" style="display: inline-block; margin-top: 20px;">Track Another Order</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>