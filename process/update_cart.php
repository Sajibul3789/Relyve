<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config/db_connect.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Get POST data
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

// Validate
if($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit();
}

if($quantity <= 0) {
    // Remove from cart
    $delete_sql = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    if(mysqli_query($conn, $delete_sql)) {
        echo json_encode(['success' => true, 'removed' => true, 'item_total' => '0']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error removing item: ' . mysqli_error($conn)]);
    }
    exit();
}

// Check if product exists and get price
$product_query = mysqli_query($conn, "SELECT id, price, stock, name FROM products WHERE id = $product_id");
if(!$product_query || mysqli_num_rows($product_query) == 0) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit();
}

$product = mysqli_fetch_assoc($product_query);

// Check stock
if($product['stock'] < $quantity) {
    echo json_encode(['success' => false, 'message' => 'Only ' . $product['stock'] . ' items available in stock']);
    exit();
}

// Check if item is in cart
$cart_check = mysqli_query($conn, "SELECT id FROM cart WHERE user_id = $user_id AND product_id = $product_id");
if(mysqli_num_rows($cart_check) > 0) {
    // Update existing cart item
    $update_sql = "UPDATE cart SET quantity = $quantity WHERE user_id = $user_id AND product_id = $product_id";
    $result = mysqli_query($conn, $update_sql);
} else {
    // Insert new cart item (should not happen normally)
    $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)";
    $result = mysqli_query($conn, $insert_sql);
}

if($result) {
    // Calculate new item total
    $item_total = $product['price'] * $quantity;
    
    echo json_encode([
        'success' => true,
        'item_total' => number_format($item_total),
        'quantity' => $quantity,
        'product_name' => $product['name']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
}
?>