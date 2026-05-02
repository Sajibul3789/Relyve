<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Login - Relyve</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="login-body">

<div class="login-container">

    <div class="login-card">
        <h2>Welcome Back</h2>
        <p class="subtitle">Login to Relyve</p>

        <form method="POST">
            <input name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button name="login">Login</button>
        </form>

        <?php
        if(isset($_POST['login'])){
            $stmt=$conn->prepare("SELECT * FROM users WHERE username=?");
            $stmt->bind_param("s",$_POST['username']);
            $stmt->execute();
            $res=$stmt->get_result();
            $user=$res->fetch_assoc();

            if($user && password_verify($_POST['password'],$user['password'])){
                $_SESSION['user']=$user;

                if($user['role'] == 'admin'){
                    header("Location: admin.php");
                } else {
                    header("Location: home.php");
                }
                exit();
            } else {
                echo "<p class='error'>Invalid login</p>";
            }
        }
        ?>

        <a href="register.php">Create account</a>

        <p style="margin-top:10px;">
            <a href="admin_login.php">Admin Login</a>
        </p>

    </div>

</div>

</body>
</html>