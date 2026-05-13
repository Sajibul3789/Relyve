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
        .profile-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .profile-header {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 3rem;
            color: var(--primary);
        }
        .profile-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 1px solid #e5e7eb;
        }
        .tab-btn {
            padding: 12px 24px;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            color: var(--text-light);
            transition: var(--transition);
        }
        .tab-btn.active {
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
        }
        .tab-content {
            display: none;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: var(--shadow-sm);
        }
        .tab-content.active {
            display: block;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-family: inherit;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .save-btn {
            background: var(--primary);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
        }
        .success-msg {
            background: #dcfce7;
            color: #16a34a;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .error-msg {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .info-box {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h2><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h2>
            <p><?php echo $user['email']; ?></p>
        </div>

        <div class="profile-tabs">
            <button class="tab-btn active" data-tab="info">Personal Info</button>
            <button class="tab-btn" data-tab="password">Change Password</button>
            <button class="tab-btn" data-tab="orders">Order History</button>
        </div>

        <?php if($success): ?>
            <div class="success-msg"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="error-msg"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Personal Info Tab -->
        <div class="tab-content active" id="info-tab">
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" value="<?php echo $user['email']; ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" value="<?php echo $user['phone']; ?>" required>
                </div>
                <button type="submit" name="update_profile" class="save-btn">Save Changes</button>
            </form>
        </div>

        <!-- Change Password Tab -->
        <div class="tab-content" id="password-tab">
            <form method="POST">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" name="change_password" class="save-btn">Change Password</button>
            </form>
        </div>

        <!-- Order History Tab -->
        <div class="tab-content" id="orders-tab">
            <?php
            $orders_sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 10";
            $orders_result = mysqli_query($conn, $orders_sql);
            if(mysqli_num_rows($orders_result) > 0):
                while($order = mysqli_fetch_assoc($orders_result)):
            ?>
                <div class="info-box">
                    <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                        <div>
                            <strong>Order #<?php echo $order['order_number']; ?></strong><br>
                            <small><?php echo date('M j, Y', strtotime($order['created_at'])); ?></small>
                        </div>
                        <div>
                            <span style="background: #fef3c7; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem;">
                                <?php echo ucfirst($order['order_status']); ?>
                            </span>
                        </div>
                        <div>
                            <strong>৳<?php echo number_format($order['total_amount']); ?></strong>
                        </div>
                        <div>
                            <a href="order_details.php?id=<?php echo $order['id']; ?>" style="color: var(--primary);">View Details →</a>
                        </div>
                    </div>
                </div>
            <?php 
                endwhile;
            else:
            ?>
                <p style="text-align: center; color: var(--text-light);">No orders yet. <a href="index.php" style="color: var(--primary);">Start shopping</a></p>
            <?php endif; ?>
            <a href="my_orders.php" style="display: block; text-align: center; margin-top: 20px; color: var(--primary);">View All Orders →</a>
        </div>
    </div>
</main>

<script>
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const tab = this.getAttribute('data-tab');
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        document.getElementById(tab + '-tab').classList.add('active');
    });
});
</script>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>