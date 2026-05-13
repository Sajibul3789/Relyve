<?php 
session_start();
include_once 'config/db_connect.php'; 
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login - Relyve</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; justify-content: center; align-items: center; }
    .login-card { background: rgba(255,255,255,0.95); border-radius: 20px; padding: 40px; width: 400px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
    .login-card h2 { margin-bottom: 10px; color: #333; }
    .login-card p { color: #666; margin-bottom: 30px; }
    .form-group { margin-bottom: 20px; text-align: left; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 500; }
    .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; font-size: 14px; }
    .form-group input:focus { outline: none; border-color: #f97316; }
    button { width: 100%; padding: 12px; background: #f97316; color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; }
    button:hover { background: #ea580c; }
    .error { background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 10px; margin-bottom: 20px; }
    .back-link { display: block; margin-top: 20px; color: #666; text-decoration: none; }
    .back-link:hover { color: #f97316; }
</style>
</head>
<body>
<div class="login-card">
    <h2>Admin Login</h2>
    <p>Access the admin dashboard</p>
    <?php if(isset($_GET['error'])): ?>
        <div class="error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <form method="POST" action="admin_process.php">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="admin@relyve.com" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password" required>
        </div>
        <button type="submit" name="admin_login">Login to Dashboard</button>
    </form>
    <a href="login_form.php" class="back-link">← Back to User Login</a>
</div>
</body>
</html>