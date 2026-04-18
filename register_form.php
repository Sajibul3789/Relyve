<?php
session_start();
include 'includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Create Account - Relyve </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/register_form.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <main>
        <div class="auth-page">
            <div class="auth-container">

                <!-- Form Header -->
                <div class="auth-header">
                    <div class="logo-icon"> R </div>
                    <h1> Create Your Account </h1>
                    <p> Join thousands of happy shoppers getting the best deals on Relyve </p>
                </div>

                <!-- Form Body -->
                <div class="auth-body">

                    <?php if (isset($_GET['error'])): ?>
                        <div class="error">
                            <i class="fas fa-exclamation-circle"> </i>
                            <?php echo htmlspecialchars($_GET['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="register.php" method="POST" id="registerForm">

                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name"> First Name </label>
                                <input type="text" id="first_name" name="first_name" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name"> Last Name </label>
                                <input type="text" id="last_name" name="last_name" class="form-input" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email"> Email Address </label>
                            <input type="email" id="email" name="email" class="form-input" placeholder="you@example.com" required>
                        </div>

                        <div class="form-group">
                            <label for="phone"> Phone Number </label>
                            <input type="tel" id="phone" name="phone" class="form-input" placeholder="+880 1XXX-XXXXXX" required>
                        </div>

                        <div class="form-group">
                            <label for="password"> Create Password </label>
                            <input type="password" id="password" name="password" class="form-input" placeholder="Minimum 8 characters" required>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password"> Confirm Password </label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
                        </div>

                        <div class="checkbox-group">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms" style="cursor: pointer; font-size: 0.85rem; padding: 0;">
                                I agree to the <a href="#"> Terms of Service </a> and <a href="#"> Privacy Policy </a>
                            </label>
                        </div>

                        <button type="submit" class="auth-btn"> Create Account </button>
                    </form>

                    <div class="divider">
                        <span> OR CONTINUE WITH </span>
                    </div>

                    <button onclick="alert('Google Sign-up coming soon!')" class="google-btn">
                        <i class="fab fa-google"></i>
                        Sign up with Google
                    </button>

                    <div class="auth-footer">
                        Already have an account?
                        <a href="login.php"> Sign in </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"> </script>

    <!-- Input Validation -->
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e)
        {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password.length < 8)
            {
                e.preventDefault();
                alert("Password must be at least 8 characters long.");
            }
            else if (password !== confirmPassword)
            {
                e.preventDefault();
                alert("Passwords do not match. Please check and try again.");
            }
        });
    </script>
</body>

</html>