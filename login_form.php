<?php
session_start();
include 'includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Sign In - Relyve </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/login_form.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <main>
        <div class="auth-page">
            <div class="auth-container">

                <!-- Form Header -->
                <div class="auth-header">
                    <div class="logo-icon"> R </div>
                    <h1> Welcome Back </h1>
                    <p> Sign in to access your account and exclusive deals </p>
                </div>

                <!-- Form Body -->
                <div class="auth-body">

                    <?php if (isset($_GET['error'])): ?>
                        <div class="error">
                            <i class="fas fa-exclamation-circle"> </i>
                            <?php echo htmlspecialchars($_GET['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['success'])): ?>
                        <div class="success">
                            <i class="fas fa-check-circle"> </i>
                            <?php echo htmlspecialchars($_GET['success']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="login_process.php" method="POST" id="loginForm">

                        <div class="form-group">
                            <label for="email"> Email Address </label>
                            <input type="email" id="email" name="email" class="form-input"
                                placeholder="you@example.com" required>
                        </div>

                        <div class="form-group">
                            <label for="password"> Password </label>
                            <input type="password" id="password" name="password" class="form-input"
                                placeholder="Enter your password" required>
                        </div>

                        <div class="form-options">
                            <div class="remember-me">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember"> Remember me </label>
                            </div>
                            <a href="forgot-password.php" class="forgot-link">Forgot Password?</a>
                        </div>

                        <button type="submit" class="auth-btn"> Sign In </button>
                    </form>

                    <div class="divider">
                        <span> OR CONTINUE WITH </span>
                    </div>

                    <button onclick="alert('Google Sign-in coming soon!')" class="google-btn">
                        <i class="fab fa-google"></i>
                        Sign in with Google
                    </button>

                    <div class="auth-footer">
                        Don't have an account?
                        <a href="register_form.php"> Create Account </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>

    <!-- Simple Client-side Validation -->
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            if (email === '' || password === '') {
                e.preventDefault();
                alert("Please fill in both email and password.");
            }
        });
    </script>
</body>

</html>