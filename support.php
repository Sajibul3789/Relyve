<?php
session_start();
include_once 'includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Support - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .support-container {
            max-width: 1000px;
            margin: 60px auto;
            padding: 0 20px;
        }
        .support-header {
            text-align: center;
            margin-bottom: 50px;
        }
        .support-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .support-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-bottom: 50px;
        }
        .support-card {
            background: white;
            padding: 30px;
            border-radius: 16px;
            text-align: center;
            transition: var(--transition);
        }
        .support-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        .support-card i {
            font-size: 3rem;
            color: #f97316;
            margin-bottom: 15px;
        }
        .support-card h3 {
            margin-bottom: 10px;
        }
        .support-card p {
            color: #666;
            font-size: 0.9rem;
        }
        .faq-section {
            background: white;
            border-radius: 16px;
            padding: 30px;
        }
        .faq-item {
            margin-bottom: 20px;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 15px;
        }
        .faq-question {
            font-weight: 600;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
        }
        .faq-answer {
            display: none;
            margin-top: 10px;
            color: #666;
            padding-left: 20px;
        }
        .faq-item.active .faq-answer {
            display: block;
        }
        @media (max-width: 768px) {
            .support-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="support-container">
        <div class="support-header">
            <h1>How can we help you?</h1>
            <p>We're here to assist you with any questions or concerns</p>
        </div>
        
        <div class="support-grid">
            <div class="support-card">
                <i class="fas fa-phone-alt"></i>
                <h3>Call Us</h3>
                <p>+880 1234-567890</p>
                <p>Sun-Thu: 9AM - 8PM</p>
            </div>
            <div class="support-card">
                <i class="fas fa-envelope"></i>
                <h3>Email Us</h3>
                <p>support@relyve.com</p>
                <p>Response within 24 hours</p>
            </div>
            <div class="support-card">
                <i class="fas fa-comments"></i>
                <h3>Live Chat</h3>
                <p>Chat with our support team</p>
                <p>Available 24/7</p>
            </div>
        </div>
        
        <div class="faq-section">
            <h2 style="margin-bottom: 20px;">Frequently Asked Questions</h2>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>How do I track my order?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">You can track your order by visiting the Track Order page and entering your order number and email address.</div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>What is your return policy?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">We offer a 7-day return policy on all products. Items must be unused and in original packaging.</div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>How long does shipping take?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Delivery typically takes 2-5 business days depending on your location. Dhaka deliveries take 1-2 days.</div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>What payment methods do you accept?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">We accept Cash on Delivery, Credit/Debit Cards (Visa, Mastercard), and bKash mobile payments.</div>
            </div>
        </div>
    </div>
</main>

<script>
document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', () => {
        question.parentElement.classList.toggle('active');
    });
});
</script>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>