<?php
session_start();
header('Content-Type: application/json');
include '../config/db_connect.php';

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to add to wishlist']);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? 0;

mysqli_query($conn, $create_table);

// Add to wishlist
$sql = "INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)";
if(mysqli_query($conn, $sql)) {
    echo json_encode(['success' => true]);
} else {
    if(mysqli_errno($conn) == 1062) { // Duplicate entry
        echo json_encode(['success' => false, 'message' => 'Product already in wishlist']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding to wishlist']);
    }
}
?>