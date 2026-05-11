<?php
session_start();
include '../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($password)) {
        header("Location: ../register_form.php?error=All fields are required.");
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../register_form.php?error=Invalid email address.");
        exit();
    }
    
    if (strlen($password) < 8) {
        header("Location: ../register_form.php?error=Password must be at least 8 characters.");
        exit();
    }
    
    if ($password !== $confirm_password) {
        header("Location: ../register_form.php?error=Passwords do not match.");
        exit();
    }
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        header("Location: ../register_form.php?error=This email is already registered.");
        $stmt->close();
        exit();
    }
    $stmt->close();
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, password, role) VALUES (?, ?, ?, ?, ?, 'user')");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $hashed_password);
    
    if ($stmt->execute()) {
        header("Location: ../login_form.php?success=Registration successful! Please sign in.");
    } else {
        header("Location: ../register_form.php?error=Something went wrong. Please try again.");
    }
    
    $stmt->close();
    $conn->close();
    exit();
}

header("Location: ../register_form.php");
exit();
?>