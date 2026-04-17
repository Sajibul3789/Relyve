<?php
session_start();
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ART Holic - Register</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/register_form.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/footer.css">
</head>

<body>
    <div class="main-back">
        <div class="form-container">
            <div class="form-header">
                <h1>Create Account</h1>
                <p>Join our art community today</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" class="input" name="username" id="username" placeholder="Choose a Username" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" class="input" name="email" id="email" placeholder="Enter your Email" required>
                </div>

                <div class="form-group">
                    <label for="password">Create Password</label>
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="input" name="password" id="password" placeholder="Create Password" required>
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="input" name="confirm-password" id="confirm-password" placeholder="Confirm Password" required>
                </div>

                <button type="submit" class="submit" name="register">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </form>

            <div class="auth-link">
                <p>Already have an account?</p>
                <a href="login_form.php">Sign In</a>
            </div>

            <div class="social-login">
                <p>Or Register with</p>
                <div class="social-buttons">
                    <a href="#" class="social-btn facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-btn google">
                        <i class="fab fa-google"></i>
                    </a>
                    <a href="#" class="social-btn twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>