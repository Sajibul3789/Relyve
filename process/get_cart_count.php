<?php
session_start();
header('Content-Type: application/json');
include '../config/db_connect.php';

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['count' => 0]);
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT SUM(quantity) as count FROM cart WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

echo json_encode(['count' => $row['count'] ?? 0]);
?>