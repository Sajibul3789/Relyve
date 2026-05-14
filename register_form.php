<?php
session_start();
include_once 'includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* ============================================
           AUTH PAGE STYLES
        ============================================ */
        .auth-page {
            min-height: calc(100vh - 200px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--spacing-2xl) var(--spacing-md);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }

        .auth-page::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 30s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .auth-page::after {
            content: '🎉';
            position: absolute;
            font-size: 300px;
            opacity: 0.05;
            bottom: -100px;
            right: -100px;
            pointer-events: none;
        }

        /* Auth Container */
        .auth-container {
            max-width: 520px;
            width: 100%;
            background: var(--white);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-2xl);
            overflow: hidden;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Auth Header */
        .auth-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            text-align: center;
            padding: var(--spacing-2xl) var(--spacing-xl);
            position: relative;
        }

        .auth-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="30" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="70" r="3" fill="rgba(255,255,255,0.1)"/></svg>');
            background-repeat: repeat;
            opacity: 0.3;
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            background: rgba(255,255,255,0.2);
            border-radius: var(--radius-2xl);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0 auto var(--spacing-lg);
            border: 2px solid rgba(255,255,255,0.3);
            backdrop-filter: blur(10px);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .auth-header h1 {
            color: var(--white);
            font-size: 1.8rem;
            margin-bottom: var(--spacing-sm);
            font-weight: 700;
        }

        .auth-header p {
            color: rgba(255,255,255,0.9);
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        /* Auth Body */
        .auth-body {
            padding: var(--spacing-2xl) var(--spacing-xl);
        }

        /* Form Row for 2 Columns */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-md);
            margin-bottom: 0;
        }

        /* Alert Messages */
        .error, .success {
            padding: var(--spacing-md) var(--spacing-lg);
            border-radius: var(--radius-lg);
            margin-bottom: var(--spacing-xl);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            font-size: 0.85rem;
            animation: slideInLeft 0.3s ease;
        }

        .error {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }

        .success {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
            border-left: 4px solid var(--success);
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Form Group */
        .form-group {
            margin-bottom: var(--spacing-xl);
        }

        .form-group label {
            display: block;
            margin-bottom: var(--spacing-sm);
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--gray-700);
        }

        .form-group label i {
            margin-right: var(--spacing-xs);
            color: var(--primary);
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-lg);
            font-size: 0.95rem;
            transition: var(--transition);
            background: var(--white);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }

        .form-input.error {
            border-color: var(--danger);
        }

        /* Password Strength Indicator */
        .password-strength {
            margin-top: var(--spacing-sm);
            height: 4px;
            border-radius: var(--radius-full);
            overflow: hidden;
            background: var(--gray-200);
        }

        .strength-bar {
            width: 0%;
            height: 100%;
            transition: width 0.3s ease, background 0.3s ease;
        }

        .strength-text {
            font-size: 0.7rem;
            margin-top: var(--spacing-xs);
            display: block;
        }

        /* Password Requirements */
        .password-requirements {
            margin-top: var(--spacing-sm);
            font-size: 0.7rem;
            color: var(--gray-500);
        }

        .password-requirements ul {
            list-style: none;
            margin-top: var(--spacing-xs);
        }

        .password-requirements li {
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
        }

        .password-requirements li i {
            font-size: 0.7rem;
            width: 14px;
        }

        .password-requirements li.valid {
            color: var(--success);
        }

        .password-requirements li.invalid {
            color: var(--gray-400);
        }

        /* Password Toggle */
        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--gray-500);
            transition: var(--transition);
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        /* Checkbox Group */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            margin-bottom: var(--spacing-xl);
        }

        .checkbox-group input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .checkbox-group label {
            font-size: 0.85rem;
            color: var(--gray-600);
            cursor: pointer;
            margin-bottom: 0;
        }

        .checkbox-group a {
            color: var(--primary);
            text-decoration: none;
        }

        .checkbox-group a:hover {
            text-decoration: underline;
        }

        /* Auth Button */
        .auth-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            border: none;
            border-radius: var(--radius-lg);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-bottom: var(--spacing-xl);
        }

        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .auth-btn.loading {
            position: relative;
            color: transparent;
        }

        .auth-btn.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid var(--white);
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Divider */
        .divider {
            text-align: center;
            position: relative;
            margin: var(--spacing-xl) 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--gray-200);
        }

        .divider span {
            background: var(--white);
            padding: 0 var(--spacing-md);
            position: relative;
            font-size: 0.75rem;
            color: var(--gray-500);
            text-transform: uppercase;
        }

        /* Google Button */
        .google-btn {
            width: 100%;
            padding: 14px;
            background: var(--white);
            color: var(--gray-700);
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-lg);
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-sm);
            margin-bottom: var(--spacing-xl);
        }

        .google-btn:hover {
            border-color: var(--primary);
            background: var(--primary-light);
            transform: translateY(-2px);
        }

        .google-btn i {
            color: #ea4335;
            font-size: 1.1rem;
        }

        /* Auth Footer */
        .auth-footer {
            text-align: center;
            padding-top: var(--spacing-lg);
            border-top: 1px solid var(--gray-200);
            font-size: 0.85rem;
            color: var(--gray-600);
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            margin-left: var(--spacing-xs);
            transition: var(--transition);
        }

        .auth-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 768px) {
            .auth-container {
                max-width: 100%;
            }
            .auth-header {
                padding: var(--spacing-xl) var(--spacing-lg);
            }
            .auth-body {
                padding: var(--spacing-xl) var(--spacing-lg);
            }
            .logo-icon {
                width: 55px;
                height: 55px;
                font-size: 2rem;
            }
            .auth-header h1 {
                font-size: 1.5rem;
            }
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }

        @media (max-width: 480px) {
            .auth-page {
                padding: var(--spacing-xl) var(--spacing-sm);
            }
            .checkbox-group {
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <main>
        <div class="auth-page">
            <div class="auth-container">

                <!-- Form Header -->
                <div class="auth-header">
                    <div class="logo-icon">R</div>
                    <h1>Create Your Account</h1>
                    <p>Join thousands of happy shoppers getting the best deals on Relyve</p>
                </div>

                <!-- Form Body -->
                <div class="auth-body">

                    <?php if (isset($_GET['error'])): ?>
                        <div class="error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo htmlspecialchars($_GET['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['success'])): ?>
                        <div class="success">
                            <i class="fas fa-check-circle"></i>
                            <?php echo htmlspecialchars($_GET['success']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="process/register_process.php" method="POST" id="registerForm">

                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">
                                    <i class="fas fa-user"></i> First Name
                                </label>
                                <input type="text" id="first_name" name="first_name" class="form-input" 
                                       placeholder="John" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">
                                    <i class="fas fa-user"></i> Last Name
                                </label>
                                <input type="text" id="last_name" name="last_name" class="form-input" 
                                       placeholder="Doe" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email Address
                            </label>
                            <input type="email" id="email" name="email" class="form-input" 
                                   placeholder="you@example.com" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">
                                <i class="fas fa-phone"></i> Phone Number
                            </label>
                            <input type="tel" id="phone" name="phone" class="form-input" 
                                   placeholder="+880 1XXX-XXXXXX" required>
                        </div>

                        <div class="form-group">
                            <label for="password">
                                <i class="fas fa-lock"></i> Create Password
                            </label>
                            <div class="password-wrapper">
                                <input type="password" id="password" name="password" class="form-input" 
                                       placeholder="Minimum 8 characters" required>
                                <span class="password-toggle" onclick="togglePassword('password')">
                                    <i class="far fa-eye"></i>
                                </span>
                            </div>
                            <div class="password-strength">
                                <div class="strength-bar" id="strengthBar"></div>
                            </div>
                            <span class="strength-text" id="strengthText"></span>
                            
                            <div class="password-requirements" id="passwordRequirements">
                                <ul>
                                    <li id="req-length"><i class="far fa-circle"></i> At least 8 characters</li>
                                    <li id="req-upper"><i class="far fa-circle"></i> At least 1 uppercase letter</li>
                                    <li id="req-lower"><i class="far fa-circle"></i> At least 1 lowercase letter</li>
                                    <li id="req-number"><i class="far fa-circle"></i> At least 1 number</li>
                                </ul>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">
                                <i class="fas fa-check-circle"></i> Confirm Password
                            </label>
                            <div class="password-wrapper">
                                <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
                                <span class="password-toggle" onclick="togglePassword('confirm_password')">
                                    <i class="far fa-eye"></i>
                                </span>
                            </div>
                            <span id="matchMessage" style="font-size: 0.7rem; margin-top: 4px; display: block;"></span>
                        </div>

                        <div class="checkbox-group">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">
                                I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                            </label>
                        </div>

                        <button type="submit" class="auth-btn" id="registerBtn">
                            <i class="fas fa-user-plus"></i> Create Account
                        </button>
                    </form>

                    <div class="divider">
                        <span>OR CONTINUE WITH</span>
                    </div>

                    <button onclick="handleGoogleSignUp()" class="google-btn">
                        <i class="fab fa-google"></i>
                        Sign up with Google
                    </button>

                    <div class="auth-footer">
                        Already have an account?
                        <a href="login_form.php">Sign in</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once 'includes/footer.php'; ?>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = passwordInput.parentElement.querySelector('.password-toggle i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            const requirements = {
                length: password.length >= 8,
                upper: /[A-Z]/.test(password),
                lower: /[a-z]/.test(password),
                number: /[0-9]/.test(password)
            };
            
            // Update requirement indicators
            updateRequirement('req-length', requirements.length);
            updateRequirement('req-upper', requirements.upper);
            updateRequirement('req-lower', requirements.lower);
            updateRequirement('req-number', requirements.number);
            
            // Calculate strength
            strength = Object.values(requirements).filter(Boolean).length;
            
            // Update strength bar
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let width = (strength / 4) * 100;
            let color = '#dc2626';
            let text = '';
            
            if (strength === 0) {
                color = '#dc2626';
                text = 'Very Weak';
            } else if (strength === 1) {
                color = '#f97316';
                text = 'Weak';
            } else if (strength === 2) {
                color = '#fbbf24';
                text = 'Fair';
            } else if (strength === 3) {
                color = '#60a5fa';
                text = 'Good';
            } else if (strength === 4) {
                color = '#22c55e';
                text = 'Strong';
            }
            
            strengthBar.style.width = width + '%';
            strengthBar.style.background = color;
            strengthText.textContent = text;
            strengthText.style.color = color;
            
            return strength === 4;
        }
        
        function updateRequirement(id, isValid) {
            const element = document.getElementById(id);
            if (isValid) {
                element.classList.add('valid');
                element.classList.remove('invalid');
                element.innerHTML = '<i class="fas fa-check-circle"></i> ' + element.innerHTML.split('>')[1];
            } else {
                element.classList.add('invalid');
                element.classList.remove('valid');
                element.innerHTML = '<i class="far fa-circle"></i> ' + element.innerHTML.split('>')[1];
            }
        }
        
        // Check password match
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const matchMessage = document.getElementById('matchMessage');
            
            if (confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    matchMessage.innerHTML = '<i class="fas fa-check-circle" style="color: #22c55e;"></i> Passwords match';
                    matchMessage.style.color = '#22c55e';
                    return true;
                } else {
                    matchMessage.innerHTML = '<i class="fas fa-exclamation-circle" style="color: #ef4444;"></i> Passwords do not match';
                    matchMessage.style.color = '#ef4444';
                    return false;
                }
            } else {
                matchMessage.innerHTML = '';
                return false;
            }
        }
        
        // Validate email
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        // Validate phone (Bangladesh format)
        function isValidPhone(phone) {
            const phoneRegex = /^(?:\+880|0)1[3-9]\d{8}$/;
            return phoneRegex.test(phone);
        }
        
        // Real-time validation
        document.getElementById('password').addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });
        
        document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);
        
        // Form validation and submission
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const terms = document.getElementById('terms').checked;
            const submitBtn = document.getElementById('registerBtn');
            
            // Validation checks
            if (firstName === '') {
                e.preventDefault();
                showNotification('Please enter your first name', 'error');
                document.getElementById('first_name').focus();
                return;
            }
            
            if (lastName === '') {
                e.preventDefault();
                showNotification('Please enter your last name', 'error');
                document.getElementById('last_name').focus();
                return;
            }
            
            if (email === '') {
                e.preventDefault();
                showNotification('Please enter your email address', 'error');
                document.getElementById('email').focus();
                return;
            }
            
            if (!isValidEmail(email)) {
                e.preventDefault();
                showNotification('Please enter a valid email address', 'error');
                document.getElementById('email').focus();
                return;
            }
            
            if (phone === '') {
                e.preventDefault();
                showNotification('Please enter your phone number', 'error');
                document.getElementById('phone').focus();
                return;
            }
            
            if (!isValidPhone(phone)) {
                e.preventDefault();
                showNotification('Please enter a valid Bangladesh phone number (e.g., 01XXXXXXXXX or +8801XXXXXXXXX)', 'error');
                document.getElementById('phone').focus();
                return;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                showNotification('Password must be at least 8 characters long', 'error');
                document.getElementById('password').focus();
                return;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                showNotification('Passwords do not match', 'error');
                document.getElementById('confirm_password').focus();
                return;
            }
            
            if (!terms) {
                e.preventDefault();
                showNotification('Please agree to the Terms of Service and Privacy Policy', 'error');
                return;
            }
            
            // Show loading state
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
        });
        
        // Google Sign Up handler
        function handleGoogleSignUp() {
            showNotification('Google Sign-up feature coming soon!', 'info');
        }
        
        // Show notification function
        function showNotification(message, type) {
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notif => notif.remove());
            
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            
            let icon = type === 'success' ? '<i class="fas fa-check-circle"></i> ' : 
                       type === 'error' ? '<i class="fas fa-exclamation-circle"></i> ' : 
                       '<i class="fas fa-info-circle"></i> ';
            
            notification.innerHTML = icon + message;
            notification.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                padding: 15px 25px;
                border-radius: 12px;
                color: white;
                font-weight: 500;
                z-index: 10000;
                animation: slideIn 0.3s ease;
                background: ${type === 'success' ? '#22c55e' : type === 'error' ? '#ef4444' : '#3b82f6'};
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                font-size: 14px;
                min-width: 250px;
                text-align: center;
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
        
        // Add CSS animations
        if(!document.querySelector('#notificationStyles')) {
            const style = document.createElement('style');
            style.id = 'notificationStyles';
            style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(style);
        }
        
        // Auto-focus on first name field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('first_name').focus();
        });
    </script>
</body>

</html>