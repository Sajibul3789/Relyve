<?php
session_start();
header('Content-Type: application/json');
include_once '../config/db_connect.php';

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

if($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit();
}

if($quantity <= 0) {
    $delete_sql = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    mysqli_query($conn, $delete_sql);
    echo json_encode(['success' => true, 'removed' => true, 'item_total' => '0']);
    exit();
}

$product_query = mysqli_query($conn, "SELECT id, price, stock FROM products WHERE id = $product_id");
$product = mysqli_fetch_assoc($product_query);

if(!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit();
}

if($product['stock'] < $quantity) {
    echo json_encode(['success' => false, 'message' => 'Only ' . $product['stock'] . ' items available']);
    exit();
}

$update_sql = "UPDATE cart SET quantity = $quantity WHERE user_id = $user_id AND product_id = $product_id";
$result = mysqli_query($conn, $update_sql);

if($result) {
    $item_total = $product['price'] * $quantity;
    echo json_encode([
        'success' => true,
        'item_total' => number_format($item_total),
        'quantity' => $quantity
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>