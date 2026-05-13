<?php
session_start();
include '../config/db_connect.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die("Access denied");
}

// Update order status
if(isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE orders SET order_status = '$status' WHERE id = $order_id");
    header("Location: orders.php");
    exit();
}

$orders_sql = "SELECT * FROM orders ORDER BY created_at DESC";
$orders_result = mysqli_query($conn, $orders_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f5f5;
        }
        .admin-container {
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: #1f2937;
            color: white;
            min-height: 100vh;
            padding: 20px;
        }
        .sidebar h2 {
            margin-bottom: 30px;
        }
        .sidebar a {
            display: block;
            color: #9ca3af;
            text-decoration: none;
            padding: 10px;
            margin: 5px 0;
            border-radius: 8px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #374151;
            color: white;
        }
        .main-content {
            flex: 1;
            padding: 20px;
        }
        .orders-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            width: 100%;
        }
        .orders-table th, .orders-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .orders-table th {
            background: #f9fafb;
            font-weight: 600;
        }
        .status-select {
            padding: 5px 10px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }
        .update-btn {
            background: #f97316;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 6px;
            cursor: pointer;
        }
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-pending { background: #fef3c7; color: #d97706; }
        .badge-processing { background: #dbeafe; color: #2563eb; }
        .badge-shipped { background: #e0e7ff; color: #4f46e5; }
        .badge-delivered { background: #dcfce7; color: #16a34a; }
        .badge-cancelled { background: #fee2e2; color: #dc2626; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>Relyve Admin</h2>
            <a href="index.php">Dashboard</a>
            <a href="orders.php" class="active">Orders</a>
            <a href="products.php">Products</a>
            <a href="users.php">Users</a>
            <a href="../logout.php">Logout</a>
        </div>
        
        <div class="main-content">
            <h1 style="margin-bottom: 20px;">Manage Orders</h1>
            
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($order = mysqli_fetch_assoc($orders_result)): ?>
                    <tr>
                        <td><?php echo $order['order_number']; ?></td>
                        <td>User #<?php echo $order['user_id']; ?></td>
                        <td>৳<?php echo number_format($order['total_amount']); ?></td>
                        <td><?php echo ucfirst($order['payment_method']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $order['order_status']; ?>">
                                <?php echo ucfirst($order['order_status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                        <td>
                            <form method="POST" style="display: flex; gap: 5px;">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <select name="status" class="status-select">
                                    <option value="pending" <?php echo $order['order_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="processing" <?php echo $order['order_status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="shipped" <?php echo $order['order_status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="delivered" <?php echo $order['order_status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="cancelled" <?php echo $order['order_status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_status" class="update-btn">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>