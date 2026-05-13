<?php
session_start();
header('Content-Type: application/json');
include_once '../config/db_connect.php';


if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login']);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product']);
    exit();
}

$sql = "DELETE FROM wishlist WHERE user_id = $user_id AND product_id = $product_id";
$result = mysqli_query($conn, $sql);

// Get updated wishlist count
$count_sql = "SELECT COUNT(*) as count FROM wishlist WHERE user_id = $user_id";
$count_result = mysqli_query($conn, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
$wishlist_count = $count_row['count'] ?? 0;

if($result) {
    echo json_encode([
        'success' => true,
        'wishlist_count' => $wishlist_count
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>