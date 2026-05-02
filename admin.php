<?php include 'config.php'; ?>

<?php
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    die("Access denied");
}


$total_users = $conn->query("SELECT COUNT(*) as t FROM users")->fetch_assoc()['t'];
$total_orders = $conn->query("SELECT COUNT(*) as t FROM orders")->fetch_assoc()['t'];
$total_sales = $conn->query("SELECT SUM(total) as t FROM orders")->fetch_assoc()['t'] ?? 0;


$orderData = [];
$res = $conn->query("SELECT DATE(created_at) d, COUNT(*) c FROM orders GROUP BY d");
while($r = $res->fetch_assoc()){
    $orderData[] = $r;
}


$salesData = [];
$res = $conn->query("SELECT DATE(created_at) d, SUM(total) s FROM orders GROUP BY d");
while($r = $res->fetch_assoc()){
    $salesData[] = $r;
}


if(isset($_GET['delete'])){
    $conn->query("DELETE FROM products WHERE id=".$_GET['delete']);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Premium Admin Dashboard</title>
<link rel="stylesheet" href="adminstyle.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="dashboard">


<div class="sidebar">
    <h2>Relyve</h2>
    <a class="active" href="#">Dashboard</a>
    <a href="index.php">Store</a>
    <a href="logout.php">Logout</a>
</div>


<div class="main">

<h1>Admin Dashboard</h1>


<div class="stats">
    <div class="box">
        <h3>Users</h3>
        <p><?= $total_users ?></p>
    </div>
    <div class="box">
        <h3>Orders</h3>
        <p><?= $total_orders ?></p>
    </div>
    <div class="box">
        <h3>Revenue</h3>
        <p>$<?= $total_sales ?></p>
    </div>
</div>


<div class="charts">

<div class="chart-box">
<h3>Orders</h3>
<canvas id="ordersChart"></canvas>
</div>

<div class="chart-box">
<h3>Sales</h3>
<canvas id="salesChart"></canvas>
</div>

</div>


<div class="orders">
<h2>Products</h2>

<?php
$res = $conn->query("SELECT * FROM products");
while($p = $res->fetch_assoc()){
    echo "<div class='order'>";
    echo "{$p['name']} - $ {$p['price']}";
    echo " <a href='?delete={$p['id']}' style='color:red;'>Delete</a>";
    echo "</div>";
}
?>
</div>

</div>
</div>

<script>

new Chart(document.getElementById('ordersChart'), {
    type: 'line',
    data: {
        labels: [<?php foreach($orderData as $d){ echo "'".$d['d']."',"; } ?>],
        datasets: [{
            label: 'Orders',
            data: [<?php foreach($orderData as $d){ echo $d['c'].","; } ?>],
            borderWidth: 2
        }]
    }
});


new Chart(document.getElementById('salesChart'), {
    type: 'bar',
    data: {
        labels: [<?php foreach($salesData as $d){ echo "'".$d['d']."',"; } ?>],
        datasets: [{
            label: 'Sales',
            data: [<?php foreach($salesData as $d){ echo $d['s'].","; } ?>]
        }]
    }
});
</script>

</body>
</html>