<?php include 'config.php'; ?>
<?php
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
$uid = $_SESSION['user']['id'];
?>
<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>My Orders</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">My Orders</div>

<div class="container">

<?php
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param("i", $uid);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows > 0){
    while($o = $res->fetch_assoc()){
        echo "<div class='card'>";
        echo "<h3>Order #{$o['id']}</h3>";
        echo "<p>Total: $ {$o['total']}</p>";
        echo "<p>Status: {$o['status']}</p>";
        echo "</div>";
    }
} else {
    echo "<p>No orders yet</p>";
}
?>

</div>

</body>
</html>