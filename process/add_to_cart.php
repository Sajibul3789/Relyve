<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../config/db_connect.php';

// Set JSON header
header('Content-Type: application/json');

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false, 
        'message' => 'Please login to add items to cart',
        'debug' => 'User not logged in'
    ]);
    exit();
}

// Get product_id from GET or POST
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : (isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0);
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : (isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1);

// Debug log
error_log("Add to cart - Product ID: $product_id, Quantity: $quantity, User ID: {$_SESSION['user_id']}");

if($product_id <= 0) {
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid product ID',
        'debug' => "Product ID received: $product_id"
    ]);
    exit();
}

// Check if product exists
$product_query = mysqli_query($conn, "SELECT id, name, stock, price FROM products WHERE id = $product_id");

if(!$product_query) {
    echo json_encode([
        'success' => false, 
        'message' => 'Database error',
        'debug' => mysqli_error($conn)
    ]);
    exit();
}

$product = mysqli_fetch_assoc($product_query);

if(!$product) {
    echo json_encode([
        'success' => false, 
        'message' => 'Product not found',
        'debug' => "No product with ID: $product_id"
    ]);
    exit();
}

// Check stock
if($product['stock'] < $quantity) {
    echo json_encode([
        'success' => false, 
        'message' => 'Not enough stock available. Only ' . $product['stock'] . ' left.',
        'debug' => "Stock: {$product['stock']}, Requested: $quantity"
    ]);
    exit();
}

// Check if item already in cart
$check_sql = "SELECT id, quantity FROM cart WHERE user_id = {$_SESSION['user_id']} AND product_id = $product_id";
$check_result = mysqli_query($conn, $check_sql);

if(!$check_result) {
    echo json_encode([
        'success' => false,
        'message' => 'Error checking cart',
        'debug' => mysqli_error($conn)
    ]);
    exit();
}

$item_exists = mysqli_num_rows($check_result) > 0;
$response = [];

if($item_exists) {
    // Update existing cart item
    $existing = mysqli_fetch_assoc($check_result);
    $old_quantity = $existing['quantity'];
    $new_quantity = $old_quantity + $quantity;
    
    $update_sql = "UPDATE cart SET quantity = $new_quantity WHERE user_id = {$_SESSION['user_id']} AND product_id = $product_id";
    $result = mysqli_query($conn, $update_sql);
    
    if($result) {
        $response = [
            'success' => true,
            'message' => "Cart updated! {$product['name']} quantity increased from $old_quantity to $new_quantity",
            'action' => 'updated',
            'old_quantity' => $old_quantity,
            'new_quantity' => $new_quantity,
            'product_name' => $product['name']
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Error updating cart',
            'debug' => mysqli_error($conn)
        ];
    }
} else {
    // Add new item to cart
    $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ({$_SESSION['user_id']}, $product_id, $quantity)";
    $result = mysqli_query($conn, $insert_sql);
    
    if($result) {
        $response = [
            'success' => true,
            'message' => "{$product['name']} added to cart!",
            'action' => 'added',
            'quantity' => $quantity,
            'product_name' => $product['name']
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Error adding to cart',
            'debug' => mysqli_error($conn)
        ];
    }
}

// Get updated cart count
if($response['success']) {
    $count_sql = "SELECT SUM(quantity) as count FROM cart WHERE user_id = {$_SESSION['user_id']}";
    $count_result = mysqli_query($conn, $count_sql);
    $count_row = mysqli_fetch_assoc($count_result);
    $response['cart_count'] = $count_row['count'] ?? 0;
}

echo json_encode($response);
?>