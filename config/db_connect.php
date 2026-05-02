<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'relyve_db';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8 (Allows inserting Emoji, Bangla, Special sybols)
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

// Function to add to cart
function add_to_cart($conn, $user_id, $product_id, $quantity = 1) {
    $sql = "INSERT INTO cart (user_id, product_id, quantity) 
            VALUES ($user_id, $product_id, $quantity)
            ON DUPLICATE KEY UPDATE quantity = quantity + $quantity";
    return mysqli_query($conn, $sql);
}

// Function to get cart items
function getCartItems($conn, $user_id) {
    $sql = "SELECT c.*, p.name, p.price, p.image_url, p.stock 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = $user_id";
    return mysqli_query($conn, $sql);
}
?>