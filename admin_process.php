<?php
session_start();
include_once 'config/db_connect.php';

if(isset($_POST['admin_login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE email = '$email' AND role = 'admin'";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        if(password_verify($password, $admin['password'])) {
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['first_name'] = $admin['first_name'];
            $_SESSION['last_name'] = $admin['last_name'];
            $_SESSION['email'] = $admin['email'];
            $_SESSION['role'] = $admin['role'];
            header("Location: admin.php");
            exit();
        } else {
            header("Location: admin_login.php?error=Incorrect password");
            exit();
        }
    } else {
        header("Location: admin_login.php?error=Admin account not found");
        exit();
    }
}
header("Location: admin_login.php");
exit();
?>