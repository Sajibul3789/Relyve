<?php
session_start();
header('Content-Type: application/json');
include '../config/db_connect.php';

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to add to wishlist']);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0);

if($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product']);
    exit();
}

// Check if already in wishlist
$check_sql = "SELECT id FROM wishlist WHERE user_id = $user_id AND product_id = $product_id";
$check_result = mysqli_query($conn, $check_sql);

if(mysqli_num_rows($check_result) > 0) {
    echo json_encode(['success' => false, 'message' => 'Product already in wishlist']);
    exit();
}

// Add to wishlist
$sql = "INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)";
if(mysqli_query($conn, $sql)) {
    echo json_encode(['success' => true, 'message' => 'Added to wishlist']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error adding to wishlist']);
}
?>