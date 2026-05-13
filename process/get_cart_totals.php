<?php
session_start();
header('Content-Type: application/json');
include_once '../config/db_connect.php';

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['subtotal' => 0, 'grand_total' => 0]);
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT SUM(p.price * c.quantity) as total 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = $user_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total = $row['total'] ?? 0;

$shipping = $total >= 5000 ? 0 : 100;
$grand_total = $total + $shipping;

echo json_encode([
    'subtotal' => $total, 
    'grand_total' => $grand_total
]);
?>