<?php
session_start();
header('Content-Type: application/json');
include_once '../config/db_connect.php';

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['cartcount' => 0]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Direct query to ensure correct count
$sql = "SELECT SUM(quantity) as cartcount FROM cart WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

if($result) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode(['cartcount' => (int)($row['cartcount'] ?? 0)]);
} else {
    echo json_encode(['cartcount' => 0]);
}
?>