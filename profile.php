<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Get user data
$user_sql = "SELECT * FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);

// Handle profile update
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    $update_sql = "UPDATE users SET first_name='$first_name', last_name='$last_name', phone='$phone' WHERE id=$user_id";
    if(mysqli_query($conn, $update_sql)) {
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $success = "Profile updated successfully!";
        // Refresh user data
        $user_result = mysqli_query($conn, $user_sql);
        $user = mysqli_fetch_assoc($user_result);
    } else {
        $error = "Error updating profile";
    }
}

// Handle password change
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if(password_verify($current_password, $user['password'])) {
        if($new_password == $confirm_password) {
            if(strlen($new_password) >= 8) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_pass = "UPDATE users SET password='$hashed_password' WHERE id=$user_id";
                if(mysqli_query($conn, $update_pass)) {
                    $success = "Password changed successfully!";
                } else {
                    $error = "Error changing password";
                }
            } else {
                $error = "Password must be at least 8 characters";
            }
        } else {
            $error = "New passwords do not match";
        }
    } else {
        $error = "Current password is incorrect";
    }
}

// Get order statistics
$total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE user_id = $user_id"))['count'];
$total_spent = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders WHERE user_id = $user_id AND order_status != 'cancelled'"))['total'] ?? 0;
$member_since = date('F Y', strtotime($user['created_at']));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* ============================================
           PROFILE CONTAINER
        ============================================ */
        .profile-container {
            max-width: 1000px;
            margin: var(--spacing-2xl) auto;
            padding: 0 var(--spacing-xl);
        }

        /* Profile Header */
        .profile-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: var(--spacing-2xl);
            border-radius: var(--radius-xl);
            text-align: center;
            margin-bottom: var(--spacing-xl);
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
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

        .profile-avatar {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--spacing-lg);
            font-size: 3rem;
            color: var(--white);
            border: 3px solid rgba(255,255,255,0.3);
            position: relative;
            z-index: 1;
        }

        .profile-header h2 {
            color: var(--white);
            margin-bottom: var(--spacing-xs);
            position: relative;
            z-index: 1;
        }

        .profile-header p {
            color: rgba(255,255,255,0.9);
            position: relative;
            z-index: 1;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-xl);
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
        }

        .stat-card .stat-label {
            font-size: 0.8rem;
            color: var(--gray-500);
        }

        /* Profile Tabs */
        .profile-tabs {
            display: flex;
            gap: var(--spacing-sm);
            margin-bottom: var(--spacing-xl);
            border-bottom: 2px solid var(--gray-200);
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 12px 24px;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            color: var(--gray-500);
            transition: var(--transition);
            position: relative;
        }

        .tab-btn:hover {
            color: var(--primary);
        }

        .tab-btn.active {
            color: var(--primary);
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary);
        }

        /* Tab Content */
        .tab-content {
            display: none;
            background: var(--white);
            padding: var(--spacing-xl);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tab-content.active {
            display: block;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: var(--spacing-lg);
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
            font-family: inherit;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }

        .form-group input:disabled {
            background: var(--gray-50);
            color: var(--gray-500);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-lg);
        }

        /* Password Field with Strength Indicator */
        .password-strength {
            margin-top: var(--spacing-xs);
            height: 3px;
            border-radius: var(--radius-full);
            overflow: hidden;
            background: var(--gray-200);
        }

        .strength-bar {
            width: 0%;
            height: 100%;
            transition: width 0.3s ease;
        }

        /* Save Button */
        .save-btn {
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

        .save-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Message Styles */
        .success-msg, .error-msg {
            padding: var(--spacing-md) var(--spacing-lg);
            border-radius: var(--radius-lg);
            margin-bottom: var(--spacing-lg);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            font-size: 0.85rem;
            animation: slideIn 0.3s ease;
        }

        .success-msg {
            background: #dcfce7;
            color: #166534;
            border-left: 3px solid var(--success);
        }

        .error-msg {
            background: #fee2e2;
            color: #991b1b;
            border-left: 3px solid var(--danger);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Order Box */
        .order-box {
            background: var(--gray-50);
            padding: var(--spacing-md);
            border-radius: var(--radius-lg);
            margin-bottom: var(--spacing-md);
            transition: var(--transition);
            border: 1px solid var(--gray-200);
        }

        .order-box:hover {
            background: var(--white);
            border-color: var(--primary-light);
            box-shadow: var(--shadow-sm);
        }

        .order-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: var(--spacing-md);
        }

        .order-number {
            font-weight: 600;
            color: var(--gray-800);
        }

        .order-date {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        .order-status {
            padding: 4px 12px;
            border-radius: var(--radius-full);
            font-size: 0.7rem;
            font-weight: 600;
        }

        .order-amount {
            font-weight: 700;
            color: var(--primary);
        }

        .view-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.85rem;
            transition: var(--transition);
        }

        .view-link:hover {
            text-decoration: underline;
        }

        /* Empty State */
        .empty-orders {
            text-align: center;
            padding: var(--spacing-2xl);
            color: var(--gray-500);
        }

        .empty-orders i {
            font-size: 3rem;
            margin-bottom: var(--spacing-md);
            color: var(--gray-300);
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 768px) {
            .profile-container {
                padding: 0 var(--spacing-md);
                margin: var(--spacing-xl) auto;
            }
            .stats-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-sm);
            }
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .profile-tabs {
                justify-content: center;
            }
            .tab-btn {
                padding: 10px 16px;
                font-size: 0.85rem;
            }
            .tab-content {
                padding: var(--spacing-lg);
            }
            .order-info {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .profile-header {
                padding: var(--spacing-lg);
            }
            .profile-avatar {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
            <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><i class="fas fa-calendar-alt"></i> Member since <?php echo $member_since; ?></p>
        </div>

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
                <i class="fas fa-calendar-alt"></i>
                <span class="stat-value"><?php echo $member_since; ?></span>
                <span class="stat-label">Member Since</span>
            </div>
        </div>

        <!-- Tabs -->
        <div class="profile-tabs">
            <button class="tab-btn active" data-tab="info">
                <i class="fas fa-user"></i> Personal Info
            </button>
            <button class="tab-btn" data-tab="password">
                <i class="fas fa-lock"></i> Change Password
            </button>
            <button class="tab-btn" data-tab="orders">
                <i class="fas fa-history"></i> Order History
            </button>
        </div>

        <!-- Messages -->
        <?php if($success): ?>
            <div class="success-msg">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Personal Info Tab -->
        <div class="tab-content active" id="info-tab">
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> First Name</label>
                        <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Last Name</label>
                        <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Phone Number</label>
                    <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                </div>
                <button type="submit" name="update_profile" class="save-btn">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>

        <!-- Change Password Tab -->
        <div class="tab-content" id="password-tab">
            <form method="POST" id="passwordForm">
                <div class="form-group">
                    <label><i class="fas fa-key"></i> Current Password</label>
                    <input type="password" name="current_password" id="current_password" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> New Password</label>
                    <input type="password" name="new_password" id="new_password" required>
                    <div class="password-strength">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-check-circle"></i> Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                    <span id="matchMessage" style="font-size: 0.7rem; margin-top: 4px; display: block;"></span>
                </div>
                <button type="submit" name="change_password" class="save-btn">
                    <i class="fas fa-key"></i> Change Password
                </button>
            </form>
        </div>

        <!-- Order History Tab -->
        <div class="tab-content" id="orders-tab">
            <?php
            $orders_sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 5";
            $orders_result = mysqli_query($conn, $orders_sql);
            if(mysqli_num_rows($orders_result) > 0):
                while($order = mysqli_fetch_assoc($orders_result)):
            ?>
                <div class="order-box">
                    <div class="order-info">
                        <div>
                            <div class="order-number">#<?php echo $order['order_number']; ?></div>
                            <div class="order-date">
                                <i class="far fa-calendar-alt"></i> <?php echo date('M j, Y', strtotime($order['created_at'])); ?>
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
                        <div class="order-amount">৳<?php echo number_format($order['total_amount']); ?></div>
                        <div>
                            <a href="order_details.php?id=<?php echo $order['id']; ?>" class="view-link">
                                View Details <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php 
                endwhile;
            else:
            ?>
                <div class="empty-orders">
                    <i class="fas fa-shopping-bag"></i>
                    <p>No orders yet.</p>
                    <a href="index.php" class="save-btn" style="display: inline-block; margin-top: var(--spacing-md);">
                        <i class="fas fa-shopping-cart"></i> Start Shopping
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if(mysqli_num_rows($orders_result) > 0): ?>
                <div style="text-align: center; margin-top: var(--spacing-lg);">
                    <a href="my_orders.php" class="view-link">
                        View All Orders <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const tab = this.getAttribute('data-tab');
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        document.getElementById(tab + '-tab').classList.add('active');
    });
});

// Password strength checker
function checkPasswordStrength(password) {
    let strength = 0;
    if(password.length >= 8) strength++;
    if(/[A-Z]/.test(password)) strength++;
    if(/[0-9]/.test(password)) strength++;
    
    const strengthBar = document.getElementById('strengthBar');
    let width = (strength / 3) * 100;
    let color = '#dc2626';
    
    if(strength === 1) color = '#f97316';
    else if(strength === 2) color = '#fbbf24';
    else if(strength === 3) color = '#22c55e';
    
    strengthBar.style.width = width + '%';
    strengthBar.style.background = color;
}

// Password match checker
function checkPasswordMatch() {
    const password = document.getElementById('new_password')?.value;
    const confirm = document.getElementById('confirm_password')?.value;
    const matchMessage = document.getElementById('matchMessage');
    
    if(confirm && password !== confirm) {
        matchMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> Passwords do not match';
        matchMessage.style.color = '#dc2626';
        return false;
    } else if(confirm && password === confirm) {
        matchMessage.innerHTML = '<i class="fas fa-check-circle"></i> Passwords match';
        matchMessage.style.color = '#16a34a';
        return true;
    } else {
        matchMessage.innerHTML = '';
        return false;
    }
}

// Event listeners for password fields
const newPassword = document.getElementById('new_password');
const confirmPassword = document.getElementById('confirm_password');

if(newPassword) {
    newPassword.addEventListener('input', function() {
        checkPasswordStrength(this.value);
        checkPasswordMatch();
    });
}

if(confirmPassword) {
    confirmPassword.addEventListener('input', checkPasswordMatch);
}

// Add status styles for order status
const style = document.createElement('style');
style.textContent = `
    .status-pending { background: #fef3c7; color: #d97706; }
    .status-processing { background: #dbeafe; color: #2563eb; }
    .status-shipped { background: #e0e7ff; color: #4f46e5; }
    .status-delivered { background: #dcfce7; color: #16a34a; }
    .status-cancelled { background: #fee2e2; color: #dc2626; }
`;
document.head.appendChild(style);
</script>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>