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
        /* ============================================
           SUPPORT CONTAINER
        ============================================ */
        .support-container {
            max-width: 1000px;
            margin: var(--spacing-2xl) auto;
            padding: 0 var(--spacing-xl);
        }

        /* Breadcrumb */
        .breadcrumb {
            margin-bottom: var(--spacing-xl);
        }

        .breadcrumb a {
            color: var(--gray-500);
            text-decoration: none;
            font-size: 0.85rem;
            transition: var(--transition);
        }

        .breadcrumb a:hover {
            color: var(--primary);
        }

        .breadcrumb span {
            color: var(--gray-400);
            margin: 0 var(--spacing-xs);
        }

        .breadcrumb .current {
            color: var(--primary);
            font-weight: 500;
        }

        /* Support Header */
        .support-header {
            text-align: center;
            margin-bottom: var(--spacing-2xl);
            padding-bottom: var(--spacing-xl);
            border-bottom: 2px solid var(--gray-200);
        }

        .support-header h1 {
            font-size: 2rem;
            margin-bottom: var(--spacing-md);
            color: var(--gray-900);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-md);
        }

        .support-header h1 i {
            color: var(--primary);
            font-size: 2rem;
        }

        .support-header p {
            color: var(--gray-500);
            font-size: 1rem;
        }

        /* Support Grid */
        .support-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--spacing-xl);
            margin-bottom: var(--spacing-2xl);
        }

        .support-card {
            background: var(--white);
            padding: var(--spacing-xl);
            border-radius: var(--radius-xl);
            text-align: center;
            transition: var(--transition);
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
        }

        .support-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-light);
        }

        .support-card i {
            font-size: 3rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: var(--spacing-md);
        }

        .support-card h3 {
            font-size: 1.2rem;
            margin-bottom: var(--spacing-md);
            color: var(--gray-800);
        }

        .support-card p {
            color: var(--gray-600);
            font-size: 0.85rem;
            margin-bottom: var(--spacing-xs);
        }

        .support-card .contact-detail {
            font-weight: 600;
            color: var(--primary);
            font-size: 1rem;
            margin-top: var(--spacing-sm);
        }

        .support-card .hours {
            font-size: 0.75rem;
            color: var(--gray-400);
        }

        /* Live Chat Button */
        .live-chat-btn {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: 8px 20px;
            border-radius: var(--radius-full);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            transition: var(--transition);
            margin-top: var(--spacing-md);
        }

        .live-chat-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* FAQ Section */
        .faq-section {
            background: var(--white);
            border-radius: var(--radius-xl);
            padding: var(--spacing-xl);
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
        }

        .faq-section h2 {
            font-size: 1.3rem;
            margin-bottom: var(--spacing-xl);
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding-bottom: var(--spacing-md);
            border-bottom: 2px solid var(--primary-light);
            display: inline-flex;
        }

        .faq-section h2 i {
            color: var(--primary);
        }

        .faq-item {
            margin-bottom: var(--spacing-md);
            border: 1px solid var(--gray-100);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: var(--transition);
        }

        .faq-item:hover {
            border-color: var(--primary-light);
        }

        .faq-question {
            font-weight: 600;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-md) var(--spacing-lg);
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            transition: var(--transition);
            color: var(--gray-800);
        }

        .faq-question:hover {
            background: linear-gradient(135deg, var(--primary-light), var(--white));
            color: var(--primary-dark);
        }

        .faq-question i {
            transition: transform 0.3s ease;
            color: var(--primary);
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }

        .faq-answer {
            display: none;
            padding: var(--spacing-lg);
            color: var(--gray-600);
            line-height: 1.7;
            background: var(--white);
            border-top: 1px solid var(--gray-100);
            font-size: 0.9rem;
        }

        .faq-item.active .faq-answer {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Contact Form Section */
        .contact-form-section {
            margin-top: var(--spacing-2xl);
            padding: var(--spacing-xl);
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
        }

        .contact-form-section h3 {
            font-size: 1.1rem;
            margin-bottom: var(--spacing-lg);
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .contact-form-section h3 i {
            color: var(--primary);
        }

        .contact-form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-md);
        }

        .contact-form-group {
            margin-bottom: var(--spacing-md);
        }

        .contact-form-group input,
        .contact-form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            font-family: inherit;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .contact-form-group input:focus,
        .contact-form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }

        .contact-form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .submit-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: 12px 30px;
            border: none;
            border-radius: var(--radius-lg);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 768px) {
            .support-container {
                padding: 0 var(--spacing-md);
                margin: var(--spacing-xl) auto;
            }
            .support-header h1 {
                font-size: 1.6rem;
            }
            .support-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
            }
            .contact-form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }

        @media (max-width: 480px) {
            .support-card {
                padding: var(--spacing-lg);
            }
            .faq-question {
                padding: var(--spacing-sm) var(--spacing-md);
                font-size: 0.9rem;
            }
            .faq-answer {
                padding: var(--spacing-md);
                font-size: 0.85rem;
            }
            .contact-form-section {
                padding: var(--spacing-lg);
            }
        }
    </style>
</head>
<body>

<main>
    <div class="support-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <span><i class="fas fa-chevron-right"></i></span>
            <span class="current">Customer Support</span>
        </div>

        <!-- Support Header -->
        <div class="support-header">
            <h1>
                <i class="fas fa-headset"></i>
                How can we help you?
            </h1>
            <p>We're here to assist you with any questions or concerns</p>
        </div>
        
        <!-- Support Grid -->
        <div class="support-grid">
            <div class="support-card">
                <i class="fas fa-phone-alt"></i>
                <h3>Call Us</h3>
                <p class="contact-detail">+880 1234-567890</p>
                <p class="hours">Sunday - Thursday: 9AM - 8PM</p>
                <p class="hours">Friday - Saturday: 10AM - 6PM</p>
            </div>
            <div class="support-card">
                <i class="fas fa-envelope"></i>
                <h3>Email Us</h3>
                <p class="contact-detail">support@relyve.com</p>
                <p>Response within 24 hours</p>
                <p class="hours">We reply to all inquiries promptly</p>
            </div>
            <div class="support-card">
                <i class="fas fa-comments"></i>
                <h3>Live Chat</h3>
                <p>Chat with our support team</p>
                <p>Available 24/7</p>
                <button class="live-chat-btn" onclick="startLiveChat()">
                    <i class="fas fa-comment-dots"></i> Start Live Chat
                </button>
            </div>
        </div>
        
        <!-- FAQ Section -->
        <div class="faq-section">
            <h2><i class="fas fa-question-circle"></i> Frequently Asked Questions</h2>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span><i class="fas fa-truck"></i> How do I track my order?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    You can track your order by visiting the <a href="track-order.php" style="color: var(--primary);">Track Order page</a> and entering your order number and email address. You'll also receive tracking updates via SMS and email once your order is shipped.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span><i class="fas fa-undo-alt"></i> What is your return policy?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    We offer a 7-day return policy on all products. Items must be unused, in original condition, and with all original packaging. Contact our support team within 7 days of delivery to initiate a return.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span><i class="fas fa-clock"></i> How long does shipping take?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    Delivery typically takes 2-5 business days depending on your location. Dhaka city deliveries take 1-2 days, while outside Dhaka takes 3-5 days. You'll receive tracking information once your order is shipped.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span><i class="fas fa-credit-card"></i> What payment methods do you accept?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    We accept Cash on Delivery (COD), Credit/Debit Cards (Visa, Mastercard, Amex), and bKash mobile payments. All online payments are processed through secure payment gateways.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span><i class="fas fa-exchange-alt"></i> Can I exchange an item?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    Yes, we offer exchanges for size or color variants subject to availability. Contact our support team within 7 days of delivery to initiate an exchange. Items must be unused and in original condition.
                </div>
            </div>
        </div>

        <!-- Contact Form Section -->
        <div class="contact-form-section">
            <h3><i class="fas fa-paper-plane"></i> Send Us a Message</h3>
            <form id="contactSupportForm">
                <div class="contact-form-row">
                    <div class="contact-form-group">
                        <input type="text" placeholder="Your Name" id="supportName" required>
                    </div>
                    <div class="contact-form-group">
                        <input type="email" placeholder="Your Email" id="supportEmail" required>
                    </div>
                </div>
                <div class="contact-form-group">
                    <input type="text" placeholder="Order Number (Optional)" id="supportOrder">
                </div>
                <div class="contact-form-group">
                    <textarea placeholder="Describe your issue or question..." id="supportMessage" required></textarea>
                </div>
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
        </div>
    </div>
</main>

<script>
// FAQ Accordion
document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', () => {
        const item = question.parentElement;
        const isActive = item.classList.contains('active');
        
        // Close other items
        document.querySelectorAll('.faq-item').forEach(faqItem => {
            if (faqItem !== item) {
                faqItem.classList.remove('active');
            }
        });
        
        // Toggle current item
        item.classList.toggle('active');
    });
});

// Live Chat Function
function startLiveChat() {
    showNotification('Live chat feature coming soon! Our support team will be available shortly.', 'info');
}

// Contact Form Submission
document.getElementById('contactSupportForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const name = document.getElementById('supportName').value;
    const email = document.getElementById('supportEmail').value;
    const message = document.getElementById('supportMessage').value;
    
    if(name && email && message) {
        showNotification('Thank you! Your message has been sent. We\'ll respond within 24 hours.', 'success');
        this.reset();
    } else {
        showNotification('Please fill in all required fields', 'error');
    }
});

// Show Notification Function
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

// Open first FAQ item by default
document.addEventListener('DOMContentLoaded', function() {
    const firstFaqItem = document.querySelector('.faq-item');
    if(firstFaqItem) {
        firstFaqItem.classList.add('active');
    }
});
</script>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>