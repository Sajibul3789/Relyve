<?php
session_start();
header('Content-Type: application/json');
include_once '../config/db_connect.php';

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if($product_id <= 0) {
    echo json_encode(['success' => false]);
    exit();
}

$sql = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
$result = mysqli_query($conn, $sql);

echo json_encode(['success' => $result]);
?>