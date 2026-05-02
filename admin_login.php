<?php include 'config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Relyve</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">Relyve Admin Panel</div>

<div class="card">

    <h2>Admin Login</h2>

    <form method="POST">
        <input name="username" placeholder="Admin Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="login">Login</button>
    </form>

    <?php
    if(isset($_POST['login'])){

       
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND role='admin'");
        $stmt->bind_param("s", $_POST['username']);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();

        if($user && password_verify($_POST['password'], $user['password'])){
            $_SESSION['user'] = $user;
            header("Location: admin.php");
            exit();
        } else {
            echo "<p style='color:red;'>Invalid admin credentials</p>";
        }
    }
    ?>

    <br>
    <a href="login.php">User Login</a>

</div>

</body>
</html>