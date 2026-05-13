<?php
include_once 'config/db_connect.php';

// Check if admin exists
$check = mysqli_query($conn, "SELECT id FROM users WHERE email = 'admin@relyve.com'");
$password = password_hash('admin123', PASSWORD_DEFAULT);

if(mysqli_num_rows($check) > 0) {
    // Update existing admin password
    mysqli_query($conn, "UPDATE users SET password = '$password', role = 'admin' WHERE email = 'admin@relyve.com'");
    echo "✅ Admin password updated!<br>";
} else {
    // Create new admin
    mysqli_query($conn, "INSERT INTO users (first_name, last_name, email, phone, password, role) VALUES ('Admin', 'User', 'admin@relyve.com', '01700000000', '$password', 'admin')");
    echo "✅ Admin user created!<br>";
}

echo "<br>";
echo "<strong>Login Credentials:</strong><br>";
echo "Email: admin@relyve.com<br>";
echo "Password: admin123<br>";
echo "<br>";
echo "<a href='admin_login.php' style='background:#f97316; color:white; padding:10px 20px; text-decoration:none; border-radius:8px;'>Go to Admin Login →</a>";
?>