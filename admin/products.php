<?php
session_start();
include '../config/db_connect.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../admin_login.php");
    exit();
}

// Handle delete
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
    header("Location: products.php");
    exit();
}

// Handle add/update
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = (float)$_POST['price'];
    $old_price = !empty($_POST['old_price']) ? (float)$_POST['old_price'] : null;
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $stock = (int)$_POST['stock'];
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);
    
    if(isset($_POST['product_id']) && $_POST['product_id'] > 0) {
        // Update
        $id = (int)$_POST['product_id'];
        $sql = "UPDATE products SET name='$name', description='$description', price=$price, old_price=" . ($old_price ? $old_price : 'NULL') . ", category='$category', stock=$stock, image_url='$image_url' WHERE id=$id";
        mysqli_query($conn, $sql);
    } else {
        // Insert
        $sql = "INSERT INTO products (name, description, price, old_price, category, stock, image_url) VALUES ('$name', '$description', $price, " . ($old_price ? $old_price : 'NULL') . ", '$category', $stock, '$image_url')";
        mysqli_query($conn, $sql);
    }
    header("Location: products.php");
    exit();
}

$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f3f4f6;
        }
        .admin-container {
            display: flex;
        }
        .sidebar {
            width: 260px;
            background: #1f2937;
            color: white;
            min-height: 100vh;
            padding: 20px;
            position: fixed;
        }
        .sidebar h2 {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #374151;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #9ca3af;
            text-decoration: none;
            padding: 12px 15px;
            margin: 5px 0;
            border-radius: 10px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #374151;
            color: white;
        }
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-add {
            background: #f97316;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .table-container {
            background: white;
            border-radius: 12px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #f9fafb;
        }
        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }
        .btn-edit, .btn-delete {
            padding: 5px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.8rem;
        }
        .btn-edit {
            background: #3b82f6;
            color: white;
            margin-right: 5px;
        }
        .btn-delete {
            background: #ef4444;
            color: white;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 16px;
            width: 500px;
            max-width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        .modal-content h2 {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .btn-save {
            background: #f97316;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .btn-cancel {
            background: #6b7280;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>Relyve Admin</h2>
            <a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
            <a href="products.php" class="active"><i class="fas fa-box"></i> Products</a>
            <a href="users.php"><i class="fas fa-users"></i> Users</a>
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>Manage Products</h1>
                <button class="btn-add" onclick="openAddModal()">+ Add New Product</button>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($product = mysqli_fetch_assoc($products)): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td><img src="<?php echo $product['image_url']; ?>" class="product-img" onerror="this.src='https://via.placeholder.com/50'"></td>
                            <td><?php echo $product['name']; ?></td>
                            <td>৳<?php echo number_format($product['price']); ?></td>
                            <td><?php echo $product['category']; ?></td>
                            <td><?php echo $product['stock']; ?></td>
                            <td>
                                <button class="btn-edit" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($product)); ?>)">Edit</button>
                                <a href="?delete=<?php echo $product['id']; ?>" class="btn-delete" onclick="return confirm('Delete this product?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Add/Edit Product Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <h2 id="modalTitle">Add New Product</h2>
            <form method="POST" id="productForm">
                <input type="hidden" name="product_id" id="product_id" value="0">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" id="product_name" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="product_description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Price (৳)</label>
                    <input type="number" name="price" id="product_price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Old Price (৳) - Optional</label>
                    <input type="number" name="old_price" id="product_old_price" step="0.01">
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" id="product_category" required>
                        <option value="smartphones">Smartphones</option>
                        <option value="laptops">Laptops</option>
                        <option value="tablets">Tablets</option>
                        <option value="accessories">Accessories</option>
                        <option value="tv_audio">TV & Audio</option>
                        <option value="watches">Watches</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" name="stock" id="product_stock" required>
                </div>
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="image_url" id="product_image" placeholder="https://...">
                </div>
                <div>
                    <button type="submit" class="btn-save">Save Product</button>
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add New Product';
            document.getElementById('product_id').value = '0';
            document.getElementById('product_name').value = '';
            document.getElementById('product_description').value = '';
            document.getElementById('product_price').value = '';
            document.getElementById('product_old_price').value = '';
            document.getElementById('product_category').value = 'smartphones';
            document.getElementById('product_stock').value = '10';
            document.getElementById('product_image').value = '';
            document.getElementById('productModal').style.display = 'flex';
        }
        
        function openEditModal(product) {
            document.getElementById('modalTitle').textContent = 'Edit Product';
            document.getElementById('product_id').value = product.id;
            document.getElementById('product_name').value = product.name;
            document.getElementById('product_description').value = product.description || '';
            document.getElementById('product_price').value = product.price;
            document.getElementById('product_old_price').value = product.old_price || '';
            document.getElementById('product_category').value = product.category;
            document.getElementById('product_stock').value = product.stock;
            document.getElementById('product_image').value = product.image_url || '';
            document.getElementById('productModal').style.display = 'flex';
        }
        
        function closeModal() {
            document.getElementById('productModal').style.display = 'none';
        }
        
        window.onclick = function(event) {
            if (event.target == document.getElementById('productModal')) {
                closeModal();
            }
        }
    </script>
</body>
</html>