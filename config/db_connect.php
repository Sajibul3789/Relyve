<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'relyve_db';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8mb4");

// Function to get products
function getProducts($conn, $limit = null, $category = null) {
    $sql = "SELECT * FROM products";
    if ($category) {
        $sql .= " WHERE category = '$category'";
    }
    $sql .= " ORDER BY created_at DESC";
    if ($limit) {
        $sql .= " LIMIT $limit";
    }
    return mysqli_query($conn, $sql);
}

// Function to get product by ID
function getProductById($conn, $id) {
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

// Function to add to cart (using JOIN in queries, not foreign keys)
function add_to_cart($conn, $user_id, $product_id, $quantity = 1) {
    // Check if item already exists in cart
    $check_sql = "SELECT id, quantity FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    $check_result = mysqli_query($conn, $check_sql);
    
    if(mysqli_num_rows($check_result) > 0) {
        $existing = mysqli_fetch_assoc($check_result);
        $new_quantity = $existing['quantity'] + $quantity;
        $update_sql = "UPDATE cart SET quantity = $new_quantity WHERE user_id = $user_id AND product_id = $product_id";
        return mysqli_query($conn, $update_sql);
    } else {
        $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)";
        return mysqli_query($conn, $insert_sql);
    }
}

// Function to get cart items with JOIN (no foreign keys needed)
function getCartItems($conn, $user_id) {
    $sql = "SELECT c.*, p.name, p.price, p.image_url, p.stock
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = $user_id";
    return mysqli_query($conn, $sql);
}

// Function to get cart total with JOIN
function getCartTotal($conn, $user_id) {
    $sql = "SELECT SUM(p.price * c.quantity) as total 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

// Function to clear cart
function clearCart($conn, $user_id) {
    $sql = "DELETE FROM cart WHERE user_id = $user_id";
    return mysqli_query($conn, $sql);
}

// Function to get user orders with JOIN
function getUserOrders($conn, $user_id) {
    $sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
    return mysqli_query($conn, $sql);
}

// Function to get order items with JOIN
function getOrderItems($conn, $order_id) {
    $sql = "SELECT * FROM order_items WHERE order_id = $order_id";
    return mysqli_query($conn, $sql);
}
?>