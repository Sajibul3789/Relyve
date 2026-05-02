<?php
session_start();
header('Content-Type: application/json');
include '../config/db_connect.php';

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? 0;
$quantity = $_POST['quantity'] ?? 1;

// Update cart
$sql = "UPDATE cart SET quantity = $quantity WHERE user_id = $user_id AND product_id = $product_id";
mysqli_query($conn, $sql);

// Get new total
$price_sql = "SELECT p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = $user_id AND c.product_id = $product_id";
$result = mysqli_query($conn, $price_sql);
$item = mysqli_fetch_assoc($result);
$item_total = $item['price'] * $item['quantity'];

echo json_encode(['success' => true, 'item_total' => number_format($item_total)]);
?>