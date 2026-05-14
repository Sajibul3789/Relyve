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
        /* ============================================
           ACCOUNT CONTAINER
        ============================================ */
        .account-container {
            max-width: 1280px;
            margin: var(--spacing-2xl) auto;
            padding: 0 var(--spacing-xl);
        }

        /* ============================================
           WELCOME BANNER
        ============================================ */
        .welcome-banner {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #c2410c 100%);
            color: var(--white);
            padding: var(--spacing-2xl);
            border-radius: var(--radius-2xl);
            margin-bottom: var(--spacing-2xl);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .welcome-banner h1 {
            font-size: 2rem;
            margin-bottom: var(--spacing-sm);
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.6s ease;
        }

        .welcome-banner p {
            font-size: 1rem;
            opacity: 0.95;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.6s ease 0.1s backwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============================================
           STATS GRID
        ============================================ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--spacing-xl);
            margin-bottom: var(--spacing-2xl);
        }

        .stat-card {
            background: var(--white);
            padding: var(--spacing-xl);
            border-radius: var(--radius-xl);
            text-align: center;
            transition: var(--transition);
            border: 1px solid var(--gray-200);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            transform: scaleX(0);
            transition: transform var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-light);
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto var(--spacing-md);
            background: linear-gradient(135deg, var(--primary-light), var(--white));
            border-radius: var(--radius-2xl);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .stat-card:hover .stat-icon {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            transform: scale(1.1);
        }

        .stat-card i {
            font-size: 1.8rem;
            color: var(--primary);
            transition: var(--transition);
        }

        .stat-card:hover i {
            color: var(--white);
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--gray-900);
            margin-bottom: var(--spacing-xs);
            line-height: 1;
        }

        .stat-label {
            color: var(--gray-500);
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* ============================================
           ACTION GRID
        ============================================ */
        .action-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--spacing-xl);
        }

        .action-card {
            background: var(--white);
            padding: var(--spacing-xl);
            border-radius: var(--radius-xl);
            text-align: center;
            text-decoration: none;
            color: inherit;
            transition: var(--transition);
            border: 1px solid var(--gray-200);
            position: relative;
            overflow: hidden;
        }

        .action-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            transform: scaleX(0);
            transition: transform var(--transition);
        }

        .action-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary);
        }

        .action-card:hover::after {
            transform: scaleX(1);
        }

        .action-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto var(--spacing-lg);
            background: linear-gradient(135deg, var(--primary-light), var(--white));
            border-radius: var(--radius-2xl);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .action-card:hover .action-icon {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            transform: scale(1.1);
            box-shadow: var(--shadow-md);
        }

        .action-card i {
            font-size: 2rem;
            color: var(--primary);
            transition: var(--transition);
        }

        .action-card:hover i {
            color: var(--white);
        }

        .action-card h3 {
            font-size: 1.2rem;
            margin-bottom: var(--spacing-sm);
            color: var(--gray-900);
            font-weight: 600;
        }

        .action-card p {
            color: var(--gray-500);
            font-size: 0.85rem;
            line-height: 1.5;
        }

        /* ============================================
           SPECIAL STYLES FOR LOGOUT CARD
        ============================================ */
        .action-card:last-child {
            border-color: rgba(239, 68, 68, 0.2);
        }

        .action-card:last-child .action-icon {
            background: linear-gradient(135deg, #fee2e2, var(--white));
        }

        .action-card:last-child i {
            color: var(--danger);
        }

        .action-card:last-child:hover {
            border-color: var(--danger);
        }

        .action-card:last-child:hover .action-icon {
            background: linear-gradient(135deg, var(--danger), #dc2626);
        }

        .action-card:last-child:hover i {
            color: var(--white);
        }

        /* ============================================
           RECENT ACTIVITY SECTION (Enhanced Addition)
        ============================================ */
        .recent-activity {
            margin-top: var(--spacing-2xl);
            padding-top: var(--spacing-xl);
            border-top: 2px solid var(--gray-200);
        }

        .recent-activity h2 {
            font-size: 1.5rem;
            margin-bottom: var(--spacing-xl);
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            color: var(--gray-900);
        }

        .recent-activity h2 i {
            color: var(--primary);
            font-size: 1.8rem;
        }

        .activity-list {
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        .activity-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: var(--spacing-lg);
            border-bottom: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item:hover {
            background: var(--gray-50);
            transform: translateX(5px);
        }

        .activity-info {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }

        .activity-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-light), var(--white));
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .activity-icon i {
            font-size: 1.2rem;
            color: var(--primary);
        }

        .activity-details h4 {
            font-size: 1rem;
            margin-bottom: 4px;
            color: var(--gray-800);
        }

        .activity-details p {
            font-size: 0.8rem;
            color: var(--gray-500);
            margin: 0;
        }

        .activity-time {
            font-size: 0.8rem;
            color: var(--gray-400);
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 1024px) {
            .stats-grid {
                gap: var(--spacing-lg);
            }
            .stat-number {
                font-size: 1.8rem;
            }
            .action-grid {
                gap: var(--spacing-lg);
            }
        }

        @media (max-width: 768px) {
            .account-container {
                padding: 0 var(--spacing-md);
                margin: var(--spacing-xl) auto;
            }
            .welcome-banner {
                padding: var(--spacing-xl);
            }
            .welcome-banner h1 {
                font-size: 1.5rem;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--spacing-md);
            }
            .action-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--spacing-md);
            }
            .activity-item {
                flex-direction: column;
                align-items: flex-start;
                gap: var(--spacing-sm);
            }
            .activity-time {
                align-self: flex-end;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
            }
            .action-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
            }
            .stat-card {
                padding: var(--spacing-lg);
            }
            .action-card {
                padding: var(--spacing-lg);
            }
            .activity-info {
                flex-direction: column;
                text-align: center;
                width: 100%;
            }
            .activity-item {
                text-align: center;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="account-container">
        <!-- WELCOME BANNER -->
        <div class="welcome-banner">
            <h1>Welcome back, <?php echo htmlspecialchars($user['first_name']); ?>! 👋</h1>
            <p>Manage your account, track orders, and discover new products</p>
        </div>
        
        <!-- STATISTICS GRID -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-number"><?php echo $order_count; ?></div>
                <div class="stat-label">Total Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="stat-number"><?php echo $wishlist_count; ?></div>
                <div class="stat-label">Wishlist Items</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-number"><?php echo $cart_count; ?></div>
                <div class="stat-label">Cart Items</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-number">Member</div>
                <div class="stat-label">Since <?php echo date('M Y', strtotime($user['created_at'])); ?></div>
            </div>
        </div>
        
        <!-- ACTION BUTTONS GRID -->
        <div class="action-grid">
            <a href="profile.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h3>Profile Settings</h3>
                <p>Update your personal information</p>
            </a>
            <a href="my_orders.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-box"></i>
                </div>
                <h3>My Orders</h3>
                <p>View order history and track orders</p>
            </a>
            <a href="wishlist.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Wishlist</h3>
                <p>View your saved items</p>
            </a>
            <a href="cart.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>Shopping Cart</h3>
                <p>Review items in your cart</p>
            </a>
            <a href="track-order.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3>Track Order</h3>
                <p>Track your delivery status</p>
            </a>
            <a href="logout.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <h3>Logout</h3>
                <p>Sign out of your account</p>
            </a>
        </div>

        <!-- RECENT ACTIVITY SECTION (Enhanced Addition) -->
        <div class="recent-activity">
            <h2>
                <i class="fas fa-history"></i>
                Recent Activity
            </h2>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-info">
                        <div class="activity-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="activity-details">
                            <h4>Browsed Products</h4>
                            <p>You viewed 5 products recently</p>
                        </div>
                    </div>
                    <div class="activity-time">
                        <i class="fas fa-clock"></i> 2 hours ago
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-info">
                        <div class="activity-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="activity-details">
                            <h4>Added to Cart</h4>
                            <p>Wireless Headphones added to cart</p>
                        </div>
                    </div>
                    <div class="activity-time">
                        <i class="fas fa-clock"></i> 1 day ago
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-info">
                        <div class="activity-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="activity-details">
                            <h4>Added to Wishlist</h4>
                            <p>Smart Watch added to wishlist</p>
                        </div>
                    </div>
                    <div class="activity-time">
                        <i class="fas fa-clock"></i> 3 days ago
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>