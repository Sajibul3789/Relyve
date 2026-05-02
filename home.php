<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Relyve - Home</title>
<link rel="stylesheet" href="style.css">
</head>
<body>


<div class="header">Relyve</div>


<div class="nav">
<a href="home.php">Home</a>
<a href="index.php">Shop</a>
<a href="cart.php">Cart</a>
<a href="orders.php">My Orders</a>
<a href="logout.php">Logout</a>
</div>


<div class="hero">
    <h1>Welcome to Relyve</h1>
    <p>Your trusted online store</p>
    <a href="index.php"><button>Shop Now</button></a>
</div>


<div class="container">
<h2>Featured Products</h2>

<?php
$res=$conn->query("SELECT * FROM products LIMIT 4");
while($p=$res->fetch_assoc()){
?>
<div class="product">
<img src="<?= $p['image'] ?>" width="100%">
<h3><?= $p['name'] ?></h3>
<p>$<?= $p['price'] ?></p>
</div>
<?php } ?>

</div>

</body>
</html>