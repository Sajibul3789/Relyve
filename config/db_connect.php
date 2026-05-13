<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'relyve_db';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

// =====================================================
// PRODUCT FUNCTIONS
// =====================================================

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

function getProductById($conn, $id) {
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

// =====================================================
// CART FUNCTIONS
// =====================================================

function add_to_cart($conn, $user_id, $product_id, $quantity = 1) {
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

function getCartItems($conn, $user_id) {
    $sql = "SELECT c.*, p.name, p.price, p.image_url, p.stock
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = $user_id";
    return mysqli_query($conn, $sql);
}

function getCartTotal($conn, $user_id) {
    $sql = "SELECT SUM(p.price * c.quantity) as total 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

function clearCart($conn, $user_id) {
    $sql = "DELETE FROM cart WHERE user_id = $user_id";
    return mysqli_query($conn, $sql);
}

function getCartCount($conn, $user_id) {
    $sql = "SELECT SUM(quantity) as count FROM cart WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['count'] ?? 0;
}

// =====================================================
// ORDER FUNCTIONS
// =====================================================

function getUserOrders($conn, $user_id) {
    $sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
    return mysqli_query($conn, $sql);
}

function getOrderItems($conn, $order_id) {
    $sql = "SELECT * FROM order_items WHERE order_id = $order_id";
    return mysqli_query($conn, $sql);
}

// =====================================================
// HOT DEALS FUNCTIONS
// =====================================================

function getHotDeals($conn, $limit = 8) {
    $sql = "SELECT *, 
            ((old_price - IFNULL(deal_price, price)) / old_price * 100) as discount_percent
            FROM products 
            WHERE is_hot_deal = 1 
            AND (deal_end_date IS NULL OR deal_end_date > NOW())
            AND (deal_start_date IS NULL OR deal_start_date <= NOW())
            AND stock > 0
            ORDER BY discount_percent DESC, created_at DESC 
            LIMIT $limit";
    return mysqli_query($conn, $sql);
}

function getAllHotDeals($conn) {
    $sql = "SELECT *, 
            ((old_price - IFNULL(deal_price, price)) / old_price * 100) as discount_percent
            FROM products 
            WHERE is_hot_deal = 1 
            AND (deal_end_date IS NULL OR deal_end_date > NOW())
            AND (deal_start_date IS NULL OR deal_start_date <= NOW())
            AND stock > 0
            ORDER BY discount_percent DESC";
    return mysqli_query($conn, $sql);
}

function getHotDealById($conn, $product_id) {
    $sql = "SELECT *, 
            ((old_price - IFNULL(deal_price, price)) / old_price * 100) as discount_percent
            FROM products 
            WHERE id = $product_id AND is_hot_deal = 1";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

function isHotDeal($conn, $product_id) {
    $sql = "SELECT id FROM products 
            WHERE id = $product_id 
            AND is_hot_deal = 1 
            AND (deal_end_date IS NULL OR deal_end_date > NOW())
            AND stock > 0";
    $result = mysqli_query($conn, $sql);
    return mysqli_num_rows($result) > 0;
}

function getDealPrice($product) {
    if(isset($product['is_hot_deal']) && $product['is_hot_deal'] && isset($product['deal_price']) && $product['deal_price']) {
        return $product['deal_price'];
    }
    return $product['price'] ?? 0;
}

function getOriginalPrice($product) {
    if(isset($product['deal_price']) && $product['deal_price']) {
        return $product['price'];
    }
    return $product['old_price'] ?? $product['price'] ?? 0;
}

function getDiscountPercent($product) {
    $original = getOriginalPrice($product);
    $deal = getDealPrice($product);
    if($original > $deal && $original > 0) {
        return round((($original - $deal) / $original) * 100);
    }
    return 0;
}

function incrementDealSold($conn, $product_id, $quantity) {
    $sql = "UPDATE products SET deal_sold = deal_sold + $quantity 
            WHERE id = $product_id AND is_hot_deal = 1";
    return mysqli_query($conn, $sql);
}

function getDealRemainingQuantity($product) {
    if(isset($product['deal_quantity']) && $product['deal_quantity']) {
        $sold = $product['deal_sold'] ?? 0;
        return $product['deal_quantity'] - $sold;
    }
    return $product['stock'] ?? 0;
}

// =====================================================
// WISHLIST FUNCTIONS
// =====================================================

function getWishlistCount($conn, $user_id) {
    $sql = "SELECT COUNT(*) as count FROM wishlist WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['count'] ?? 0;
}

function isInWishlist($conn, $user_id, $product_id) {
    $sql = "SELECT id FROM wishlist WHERE user_id = $user_id AND product_id = $product_id";
    $result = mysqli_query($conn, $sql);
    return mysqli_num_rows($result) > 0;
}

function addToWishlist($conn, $user_id, $product_id) {
    if(isInWishlist($conn, $user_id, $product_id)) {
        return false;
    }
    $sql = "INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)";
    return mysqli_query($conn, $sql);
}

function removeFromWishlist($conn, $user_id, $product_id) {
    $sql = "DELETE FROM wishlist WHERE user_id = $user_id AND product_id = $product_id";
    return mysqli_query($conn, $sql);
}

function getWishlistItems($conn, $user_id) {
    $sql = "SELECT p.*, w.created_at as date_added
            FROM wishlist w
            JOIN products p ON w.product_id = p.id
            WHERE w.user_id = $user_id
            ORDER BY w.created_at DESC";
    return mysqli_query($conn, $sql);
}
?>