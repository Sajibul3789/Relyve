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
        .contact-hero {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        .contact-hero h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        .contact-section {
            padding: 60px 0;
        }
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 50px;
        }
        .contact-info {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: var(--shadow-sm);
        }
        .contact-info h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        .info-item {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            align-items: flex-start;
        }
        .info-item i {
            width: 40px;
            height: 40px;
            background: #fef3c7;
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        .info-item h4 {
            margin-bottom: 5px;
            font-size: 1rem;
        }
        .info-item p {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .social-links a {
            width: 40px;
            height: 40px;
            background: #f3f4f6;
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }
        .social-links a:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }
        .contact-form {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: var(--shadow-sm);
        }
        .contact-form h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-family: inherit;
            transition: var(--transition);
        }
        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
        }
        .submit-btn {
            background: var(--primary);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
        }
        .submit-btn:hover {
            background: var(--primary-dark);
        }
        .success-message {
            background: #dcfce7;
            color: #16a34a;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .map-container {
            margin-top: 60px;
            border-radius: 20px;
            overflow: hidden;
        }
        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="contact-hero">
        <div class="container">
            <h1>Get in Touch</h1>
            <p>We'd love to hear from you. Send us a message and we'll respond within 24 hours.</p>
        </div>
    </div>

    <div class="container contact-section">
        <div class="contact-grid">
            <div class="contact-info">
                <h2>Contact Information</h2>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Our Location</h4>
                        <p>123 Gulshan Avenue, Dhaka - 1212, Bangladesh</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone"></i>
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
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="contact-form">
                <h2>Send Us a Message</h2>
                <?php if($message_sent): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i> Thank you! Your message has been sent. We'll get back to you soon.
                    </div>
                <?php endif; ?>
                <form method="POST">
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
                    <button type="submit" class="submit-btn">Send Message</button>
                </form>
            </div>
        </div>

        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.902!2d90.391!3d23.746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8b0b1a1b0b1%3A0x0!2zMjPCsDQ0JzQ1LjYiTiA5MMKwMjMnMjcuNiJF!5e0!3m2!1sen!2sbd!4v1234567890" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>