<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';

$message_sent = false;
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    // Here you would typically send an email
    // For now, we'll just show success message
    $message_sent = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* ============================================
           CONTACT HERO SECTION
        ============================================ */
        .contact-hero {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #c2410c 100%);
            color: var(--white);
            padding: var(--spacing-3xl) 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .contact-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .contact-hero h1 {
            font-size: 2.8rem;
            margin-bottom: var(--spacing-md);
            position: relative;
            z-index: 1;
            animation: fadeInDown 0.6s ease;
        }

        .contact-hero p {
            font-size: 1.1rem;
            opacity: 0.95;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        /* ============================================
           CONTACT SECTION
        ============================================ */
        .contact-section {
            padding: var(--spacing-3xl) 0;
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--white) 100%);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: var(--spacing-2xl);
        }

        /* ============================================
           CONTACT INFO CARDS
        ============================================ */
        .contact-info {
            background: var(--white);
            padding: var(--spacing-xl);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-md);
            transition: var(--transition);
        }

        .contact-info:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }

        .contact-info h2 {
            font-size: 1.6rem;
            margin-bottom: var(--spacing-xl);
            color: var(--gray-900);
            position: relative;
            padding-bottom: var(--spacing-md);
        }

        .contact-info h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--primary);
            border-radius: var(--radius-full);
        }

        .info-item {
            display: flex;
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-xl);
            align-items: flex-start;
            transition: var(--transition);
            padding: var(--spacing-sm);
            border-radius: var(--radius-lg);
        }

        .info-item:hover {
            background: var(--gray-50);
            transform: translateX(5px);
        }

        .info-item i {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-light), var(--white));
            color: var(--primary);
            border-radius: var(--radius-2xl);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: var(--transition);
        }

        .info-item:hover i {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            transform: scale(1.1);
        }

        .info-item h4 {
            margin-bottom: var(--spacing-xs);
            font-size: 1rem;
            color: var(--gray-800);
            font-weight: 600;
        }

        .info-item p {
            color: var(--gray-600);
            font-size: 0.9rem;
            margin-bottom: 4px;
        }

        /* Social Links */
        .social-links {
            display: flex;
            gap: var(--spacing-md);
            margin-top: var(--spacing-xl);
            padding-top: var(--spacing-lg);
            border-top: 1px solid var(--gray-200);
        }

        .social-links a {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--gray-100), var(--white));
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            text-decoration: none;
            border: 1px solid var(--gray-200);
        }

        .social-links a:hover {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }

        /* ============================================
           CONTACT FORM
        ============================================ */
        .contact-form {
            background: var(--white);
            padding: var(--spacing-xl);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-md);
            transition: var(--transition);
        }

        .contact-form:hover {
            box-shadow: var(--shadow-xl);
        }

        .contact-form h2 {
            font-size: 1.6rem;
            margin-bottom: var(--spacing-xl);
            color: var(--gray-900);
            position: relative;
            padding-bottom: var(--spacing-md);
        }

        .contact-form h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--primary);
            border-radius: var(--radius-full);
        }

        .form-group {
            margin-bottom: var(--spacing-lg);
    }
        .form-group input, 
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-lg);
            font-family: inherit;
            font-size: 0.9rem;
            transition: var(--transition);
            background: var(--white);
        }

        .form-group input:focus, 
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .submit-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: 14px 35px;
            border: none;
            border-radius: var(--radius-lg);
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .submit-btn i {
            font-size: 1rem;
        }

        /* Success Message */
        .success-message {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
            padding: var(--spacing-md) var(--spacing-lg);
            border-radius: var(--radius-lg);
            margin-bottom: var(--spacing-xl);
            border: 1px solid #86efac;
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            font-weight: 500;
        }

        .success-message i {
            font-size: 1.2rem;
        }

        /* ============================================
           MAP CONTAINER
        ============================================ */
        .map-container {
            margin-top: var(--spacing-2xl);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
        }

        .map-container:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }

        .map-container iframe {
            display: block;
            filter: grayscale(0.1);
            transition: var(--transition);
        }

        .map-container:hover iframe {
            filter: grayscale(0);
        }

        /* ============================================
           FAQ PREVIEW SECTION
        ============================================ */
        .faq-preview {
            margin-top: var(--spacing-3xl);
            text-align: center;
        }

        .faq-preview h3 {
            font-size: 1.5rem;
            margin-bottom: var(--spacing-xl);
            color: var(--gray-900);
        }

        .faq-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--spacing-xl);
            margin-top: var(--spacing-xl);
        }

        .faq-item {
            background: var(--white);
            padding: var(--spacing-xl);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .faq-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .faq-item i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: var(--spacing-md);
        }

        .faq-item h4 {
            font-size: 1.1rem;
            margin-bottom: var(--spacing-sm);
            color: var(--gray-800);
        }

        .faq-item p {
            font-size: 0.85rem;
            color: var(--gray-600);
            margin-bottom: 0;
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 1024px) {
            .contact-grid {
                gap: var(--spacing-xl);
            }
            .faq-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--spacing-lg);
            }
        }

        @media (max-width: 768px) {
            .contact-hero h1 {
                font-size: 2rem;
            }
            .contact-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-xl);
            }
            .contact-info, .contact-form {
                padding: var(--spacing-lg);
            }
            .faq-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
            }
        }

        @media (max-width: 480px) {
            .info-item {
                padding: var(--spacing-xs);
            }
            .social-links {
                justify-content: center;
            }
            .submit-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<main>
    <!-- HERO SECTION -->
    <div class="contact-hero">
        <div class="container">
            <h1>Get in Touch</h1>
            <p>We'd love to hear from you. Send us a message and we'll respond within 24 hours.</p>
        </div>
    </div>

    <div class="container contact-section">
        <div class="contact-grid">
            <!-- CONTACT INFO -->
            <div class="contact-info">
                <h2>Contact Information</h2>
                
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Our Location</h4>
                        <p>123 Gulshan Avenue, Dhaka - 1212</p>
                        <p>Bangladesh</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="fas fa-phone-alt"></i>
                    <div>
                        <h4>Phone Number</h4>
                        <p>+880 1234-567890</p>
                        <p>+880 1234-567891</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email Address</h4>
                        <p>support@relyve.com</p>
                        <p>sales@relyve.com</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h4>Working Hours</h4>
                        <p>Sunday - Thursday: 9AM - 8PM</p>
                        <p>Friday - Saturday: 10AM - 6PM</p>
                    </div>
                </div>
                
                <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- CONTACT FORM -->
            <div class="contact-form">
                <h2>Send Us a Message</h2>
                
                <?php if($message_sent): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i> 
                        Thank you! Your message has been sent. We'll get back to you soon.
                    </div>
                <?php endif; ?>
                
                <form method="POST" id="contactForm">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Your Email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="subject" placeholder="Subject" required>
                    </div>
                    <div class="form-group">
                        <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>

        <!-- FAQ PREVIEW SECTION -->
        <div class="faq-preview">
            <h3>Frequently Asked Questions</h3>
            <div class="faq-grid">
                <div class="faq-item">
                    <i class="fas fa-truck"></i>
                    <h4>Shipping & Delivery</h4>
                    <p>Free shipping on orders over ৳5000. Delivery within 2-3 business days.</p>
                </div>
                <div class="faq-item">
                    <i class="fas fa-exchange-alt"></i>
                    <h4>Easy Returns</h4>
                    <p>30-day return policy. Full refund or exchange on eligible items.</p>
                </div>
                <div class="faq-item">
                    <i class="fas fa-headset"></i>
                    <h4>24/7 Support</h4>
                    <p>Our customer support team is available round the clock to assist you.</p>
                </div>
            </div>
        </div>

        <!-- MAP CONTAINER -->
        <div class="map-container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.902!2d90.391!3d23.746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8b0b1a1b0b1%3A0x0!2zMjPCsDQ0JzQ1LjYiTiA5MMKwMjMnMjcuNiJF!5e0!3m2!1sen!2sbd!4v1234567890" 
                width="100%" 
                height="400" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
</main>

<script>
// Form validation and submission enhancement
document.getElementById('contactForm')?.addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('.submit-btn');
    const originalText = submitBtn.innerHTML;
    
    // Validate form
    const inputs = this.querySelectorAll('input, textarea');
    let isValid = true;
    
    inputs.forEach(input => {
        if(input.required && !input.value.trim()) {
            input.style.borderColor = '#ef4444';
            isValid = false;
        } else {
            input.style.borderColor = '#e5e7eb';
        }
    });
    
    if(!isValid) {
        e.preventDefault();
        showNotification('Please fill in all required fields', 'error');
        return;
    }
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    
    // Form will submit normally after this
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }, 3000);
});

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

// CSS animations
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
</script>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>