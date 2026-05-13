<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_sql = "SELECT * FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);

// Get order count
$order_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE user_id = $user_id"))['count'];

// Get wishlist count
$wishlist_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM wishlist WHERE user_id = $user_id"))['count'];

// Get cart count
$cart_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) as count FROM cart WHERE user_id = $user_id"))['count'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .account-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .welcome-banner {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            padding: 40px;
            border-radius: 20px;
            margin-bottom: 30px;
        }
        .welcome-banner h1 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .stat-card i {
            font-size: 2rem;
            color: #f97316;
            margin-bottom: 10px;
        }
        .stat-card .number {
            font-size: 1.8rem;
            font-weight: 700;
        }
        .stat-card .label {
            color: #666;
            font-size: 0.85rem;
        }
        .action-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .action-card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            text-align: center;
            text-decoration: none;
            color: inherit;
            transition: var(--transition);
        }
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        .action-card i {
            font-size: 2.5rem;
            color: #f97316;
            margin-bottom: 15px;
        }
        .action-card h3 {
            margin-bottom: 8px;
        }
        .action-card p {
            color: #666;
            font-size: 0.85rem;
        }
        @media (max-width: 768px) {
            .stats-grid, .action-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 480px) {
            .stats-grid, .action-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="account-container">
        <div class="welcome-banner">
            <h1>Welcome back, <?php echo $user['first_name']; ?>!</h1>
            <p>Manage your account, track orders, and discover new products</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-shopping-bag"></i>
                <div class="number"><?php echo $order_count; ?></div>
                <div class="label">Total Orders</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-heart"></i>
                <div class="number"><?php echo $wishlist_count; ?></div>
                <div class="label">Wishlist Items</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-shopping-cart"></i>
                <div class="number"><?php echo $cart_count; ?></div>
                <div class="label">Cart Items</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-calendar-alt"></i>
                <div class="number">Member</div>
                <div class="label">Since <?php echo date('M Y', strtotime($user['created_at'])); ?></div>
            </div>
        </div>
        
        <div class="action-grid">
            <a href="profile.php" class="action-card">
                <i class="fas fa-user-circle"></i>
                <h3>Profile Settings</h3>
                <p>Update your personal information</p>
            </a>
            <a href="my_orders.php" class="action-card">
                <i class="fas fa-box"></i>
                <h3>My Orders</h3>
                <p>View order history and track orders</p>
            </a>
            <a href="wishlist.php" class="action-card">
                <i class="fas fa-heart"></i>
                <h3>Wishlist</h3>
                <p>View your saved items</p>
            </a>
            <a href="cart.php" class="action-card">
                <i class="fas fa-shopping-cart"></i>
                <h3>Shopping Cart</h3>
                <p>Review items in your cart</p>
            </a>
            <a href="track-order.php" class="action-card">
                <i class="fas fa-truck"></i>
                <h3>Track Order</h3>
                <p>Track your delivery status</p>
            </a>
            <a href="logout.php" class="action-card">
                <i class="fas fa-sign-out-alt"></i>
                <h3>Logout</h3>
                <p>Sign out of your account</p>
            </a>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>