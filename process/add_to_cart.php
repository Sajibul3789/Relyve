<?php
session_start();
include_once '../config/db_connect.php';
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to add items to cart']);
    exit();
}

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : (isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0);
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : (isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1);

if($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit();
}

// Check if product exists and has stock
$product_query = mysqli_query($conn, "SELECT id, name, stock, price FROM products WHERE id = $product_id");
$product = mysqli_fetch_assoc($product_query);

if(!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit();
}

if($product['stock'] < $quantity) {
    echo json_encode(['success' => false, 'message' => 'Only ' . $product['stock'] . ' items available']);
    exit();
}

// Check if item already in cart
$check_sql = "SELECT id, quantity FROM cart WHERE user_id = {$_SESSION['user_id']} AND product_id = $product_id";
$check_result = mysqli_query($conn, $check_sql);
$item_exists = mysqli_num_rows($check_result) > 0;

if($item_exists) {
    $existing = mysqli_fetch_assoc($check_result);
    $new_quantity = $existing['quantity'] + $quantity;
    $update_sql = "UPDATE cart SET quantity = $new_quantity WHERE user_id = {$_SESSION['user_id']} AND product_id = $product_id";
    $result = mysqli_query($conn, $update_sql);
    
    if($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Cart updated!',
            'action' => 'updated',
            'product_name' => $product['name']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating cart']);
    }
} else {
    $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ({$_SESSION['user_id']}, $product_id, $quantity)";
    $result = mysqli_query($conn, $insert_sql);
    
    if($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Product added to cart!',
            'action' => 'added',
            'product_name' => $product['name']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding to cart']);
    }
}
?>