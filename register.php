<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">Relyve</div>

<div class="card">
<h2>Register</h2>

<form method="POST">
<input name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>


<input name="adminkey" placeholder="Admin Key (optional)">

<button name="register">Register</button>
</form>

<?php
if(isset($_POST['register'])){
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $key = $_POST['adminkey'];

   
    $secret = "RELYVE_ADMIN_123";

    if($key === $secret){
        $role = "admin";
    } else {
        $role = "user";
    }

    $stmt = $conn->prepare("INSERT INTO users(username,password,role,admin_key) VALUES(?,?,?,?)");
    $stmt->bind_param("ssss", $username, $password, $role, $key);

    if($stmt->execute()){
        echo "<p style='color:green;'>Registered successfully!</p>";
    } else {
        echo "<p style='color:red;'>Username already exists</p>";
    }
}
?>

<a href="login.php">Login</a>
</div>

</body>
</html>