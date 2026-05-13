<?php
session_start();
header('Content-Type: application/json');
include_once '../config/db_connect.php';

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login']);
    exit();
}

$user_id = $_SESSION['user_id'];
$order_number = 'RELYVE' . time() . rand(100, 999);

$first_name = mysqli_real_escape_string($conn, $_POST['first_name'] ?? '');
$last_name = mysqli_real_escape_string($conn, $_POST['last_name'] ?? '');
$email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
$phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
$address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
$city = mysqli_real_escape_string($conn, $_POST['city'] ?? '');
$zip = mysqli_real_escape_string($conn, $_POST['zip'] ?? '');
$notes = mysqli_real_escape_string($conn, $_POST['notes'] ?? '');
$payment_method = mysqli_real_escape_string($conn, $_POST['payment_method'] ?? 'cod');
$shipping_address = "$address, $city, $zip";

// Get cart total using JOIN
$total_sql = "SELECT SUM(p.price * c.quantity) as total FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = $user_id";
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total = $total_row['total'] ?? 0;

if($total <= 0) {
    echo json_encode(['success' => false, 'message' => 'Cart is empty']);
    exit();
}

$shipping = $total >= 5000 ? 0 : 100;
$grand_total = $total + $shipping;

// Start transaction for data integrity (optional but recommended)
mysqli_begin_transaction($conn);

try {
    // Insert order
    $order_sql = "INSERT INTO orders (order_number, user_id, total_amount, payment_method, shipping_address, shipping_city, shipping_zip, shipping_phone, notes)
                  VALUES ('$order_number', $user_id, $grand_total, '$payment_method', '$shipping_address', '$city', '$zip', '$phone', '$notes')";
    
    if(!mysqli_query($conn, $order_sql)) {
        throw new Exception('Failed to create order: ' . mysqli_error($conn));
    }
    
    $order_id = mysqli_insert_id($conn);
    
    // Get cart items using JOIN
    $cart_sql = "SELECT c.*, p.name, p.price, p.stock FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = $user_id";
    $cart_result = mysqli_query($conn, $cart_sql);
    
    if(mysqli_num_rows($cart_result) == 0) {
        throw new Exception('Cart is empty');
    }
    
    while($item = mysqli_fetch_assoc($cart_result)) {
        // Check if enough stock
        if($item['stock'] < $item['quantity']) {
            throw new Exception("Not enough stock for {$item['name']}. Only {$item['stock']} left.");
        }
        
        // Update stock
        $update_stock = mysqli_query($conn, "UPDATE products SET stock = stock - {$item['quantity']} WHERE id = {$item['product_id']} AND stock >= {$item['quantity']}");
        if(!$update_stock || mysqli_affected_rows($conn) == 0) {
            throw new Exception("Failed to update stock for {$item['name']}");
        }
        
        // Insert order item
        $item_sql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
                     VALUES ($order_id, {$item['product_id']}, '{$item['name']}', {$item['quantity']}, {$item['price']})";
        if(!mysqli_query($conn, $item_sql)) {
            throw new Exception('Failed to save order item: ' . mysqli_error($conn));
        }
    }
    
    // Clear cart
    if(!mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id")) {
        throw new Exception('Failed to clear cart');
    }
    
    // Commit transaction
    mysqli_commit($conn);
    
    echo json_encode(['success' => true, 'order_id' => $order_id]);
    
} catch (Exception $e) {
    // Rollback on error
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>