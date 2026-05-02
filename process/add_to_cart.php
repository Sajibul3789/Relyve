<?php
session_start();
include '../config/db_connect.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: ../login_form.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['product_id'] ?? $_POST['product_id'] ?? 0;
$quantity = $_GET['quantity'] ?? $_POST['quantity'] ?? 1;

if($product_id > 0) {
    add_to_cart($conn, $user_id, $product_id, $quantity);
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>