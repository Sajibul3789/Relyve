<?php
session_start();
include_once 'config/db_connect.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    die("Access denied");
}

$total_users = $conn->query("SELECT COUNT(*) as t FROM users")->fetch_assoc()['t'];
$total_orders = $conn->query("SELECT COUNT(*) as t FROM orders")->fetch_assoc()['t'];
$total_sales = $conn->query("SELECT SUM(total_amount) as t FROM orders WHERE order_status = 'delivered'")->fetch_assoc()['t'] ?? 0;

$orderData = [];
$res = $conn->query("SELECT DATE(created_at) d, COUNT(*) c FROM orders GROUP BY d");
while($r = $res->fetch_assoc()){
    $orderData[] = $r;
}

$salesData = [];
$res = $conn->query("SELECT DATE(created_at) d, SUM(total_amount) s FROM orders GROUP BY d");
while($r = $res->fetch_assoc()){
    $salesData[] = $r;
}

if(isset($_GET['delete'])){
    $conn->query("DELETE FROM products WHERE id=".$_GET['delete']);
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard - Relyve</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; }
    .dashboard { display: flex; }
    .sidebar { width: 260px; background: #1f2937; color: white; min-height: 100vh; padding: 20px; position: fixed; }
    .sidebar h2 { margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px solid #374151; }
    .sidebar a { display: flex; align-items: center; gap: 12px; color: #9ca3af; text-decoration: none; padding: 12px 15px; margin: 5px 0; border-radius: 10px; transition: 0.3s; }
    .sidebar a:hover, .sidebar a.active { background: #374151; color: white; }
    .main { flex: 1; margin-left: 260px; padding: 20px; }
    .stats { display: flex; gap: 20px; margin-bottom: 30px; }
    .box { background: white; padding: 25px; border-radius: 15px; text-align: center; flex: 1; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .box h3 { color: #666; font-size: 14px; margin-bottom: 10px; }
    .box p { font-size: 32px; font-weight: bold; color: #f97316; margin: 0; }
    .charts { display: flex; gap: 20px; margin-bottom: 30px; }
    .chart-box { background: white; padding: 20px; border-radius: 15px; flex: 1; }
    .orders { background: white; padding: 20px; border-radius: 15px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
    th { background: #f8f9fa; }
    .delete-btn { background: #ef4444; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 12px; }
    .edit-btn { background: #3b82f6; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 12px; margin-right: 5px; }
    .add-btn { background: #22c55e; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-block; margin-bottom: 20px; }
    @media (max-width: 768px) { .stats, .charts { flex-direction: column; } }
</style>
</head>
<body>
<div class="dashboard">
    <div class="sidebar">
        <h2>Relyve Admin</h2>
        <a href="admin.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="admin/orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
        <a href="admin/products.php"><i class="fas fa-box"></i> Products</a>
        <a href="admin/users.php"><i class="fas fa-users"></i> Users</a>
        <a href="index.php"><i class="fas fa-store"></i> Store</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    
    <div class="main">
        <h1 style="margin-bottom: 20px;">Admin Dashboard</h1>
        
        <div class="stats">
            <div class="box"><h3>Total Users</h3><p><?= $total_users ?></p></div>
            <div class="box"><h3>Total Orders</h3><p><?= $total_orders ?></p></div>
            <div class="box"><h3>Total Revenue</h3><p>৳<?= number_format($total_sales) ?></p></div>
        </div>
        
        <div class="charts">
            <div class="chart-box"><h3>Orders Overview</h3><canvas id="ordersChart"></canvas></div>
            <div class="chart-box"><h3>Sales Overview</h3><canvas id="salesChart"></canvas></div>
        </div>
        
        <div class="orders">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Products</h2>
                <a href="admin/products.php" class="add-btn">+ Add New Product</a>
            </div>
            <table>
                <thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead>
                <tbody>
                <?php
                $res = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 10");
                while($p = $res->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= $p['name'] ?></td>
                    <td>৳<?= number_format($p['price']) ?></td>
                    <td><?= $p['stock'] ?></td>
                    <td>
                        <a href="admin/products.php?edit=<?= $p['id'] ?>" class="edit-btn">Edit</a>
                        <a href="?delete=<?= $p['id'] ?>" onclick="return confirm('Delete?')" class="delete-btn">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
new Chart(document.getElementById('ordersChart'), {
    type: 'line', data: { labels: [<?php foreach($orderData as $d) echo "'".$d['d']."',"; ?>],
    datasets: [{ label: 'Orders', data: [<?php foreach($orderData as $d) echo $d['c'].","; ?>], borderColor: '#f97316', borderWidth: 2 }] }
});
new Chart(document.getElementById('salesChart'), {
    type: 'bar', data: { labels: [<?php foreach($salesData as $d) echo "'".$d['d']."',"; ?>],
    datasets: [{ label: 'Sales (৳)', data: [<?php foreach($salesData as $d) echo $d['s'].","; ?>], backgroundColor: '#f97316' }] }
});
</script>
</body>
</html>