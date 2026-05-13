<?php
session_start();
header('Content-Type: application/json');
include_once '../config/db_connect.php';

error_log("=== get_wishlist_count.php called ===");
error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'not set'));

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['wishlistcount' => 0]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Direct query
$sql = "SELECT COUNT(*) as wishlistcount FROM wishlist WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

if($result) {
    $row = mysqli_fetch_assoc($result);
    $count = (int)$row['wishlistcount'];
    error_log("Wishlist count for user $user_id: $count");
    echo json_encode(['wishlistcount' => $count]);
} else {
    error_log("Error in get_wishlist_count: " . mysqli_error($conn));
    echo json_encode(['wishlistcount' => 0]);
}
?>