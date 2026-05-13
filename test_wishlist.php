<?php
session_start();
include 'config/db_connect.php';

echo "<h1>Wishlist Debug</h1>";

if(!isset($_SESSION['user_id'])) {
    echo "<p style='color:red'>You are not logged in!</p>";
    echo '<a href="login_form.php">Login first</a>';
    exit();
}

$user_id = $_SESSION['user_id'];
echo "<p>Logged in as User ID: $user_id</p>";

// Check wishlist
$wishlist_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM wishlist WHERE user_id = $user_id");
$wishlist_row = mysqli_fetch_assoc($wishlist_result);
$wishlist_count = $wishlist_row['count'] ?? 0;

echo "<p><strong>Wishlist count from database:</strong> $wishlist_count</p>";

// Show wishlist items
$items_result = mysqli_query($conn, "SELECT * FROM wishlist WHERE user_id = $user_id");
echo "<h3>Wishlist Items:</h3>";
if(mysqli_num_rows($items_result) > 0) {
    echo "<ul>";
    while($item = mysqli_fetch_assoc($items_result)) {
        echo "<li>Product ID: {$item['product_id']}, Added: {$item['created_at']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No items in wishlist</p>";
}

// Check cart
$cart_result = mysqli_query($conn, "SELECT COALESCE(SUM(quantity), 0) as count FROM cart WHERE user_id = $user_id");
$cart_row = mysqli_fetch_assoc($cart_result);
$cart_count = $cart_row['count'] ?? 0;

echo "<p><strong>Cart count from database:</strong> $cart_count</p>";

// Test the API endpoint
echo "<h3>Testing API Endpoint:</h3>";
$api_url = "process/get_wishlist_count.php";
echo "<button onclick=\"fetch('$api_url').then(r=>r.json()).then(d=>alert('API returned: '+d.count)).catch(e=>alert('Error: '+e))\">Test get_wishlist_count.php</button>";

echo "<br><br>";
echo '<a href="index.php">Go to Homepage</a>';
?>