<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* ============================================
           FAQ HERO SECTION
        ============================================ */
        .faq-hero {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #c2410c 100%);
            color: var(--white);
            padding: var(--spacing-3xl) 0 var(--spacing-2xl);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .faq-hero::before {
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

        .faq-hero h1 {
            font-size: 2.8rem;
            color: var(--white);
            margin-bottom: var(--spacing-md);
            position: relative;
            z-index: 1;
            animation: fadeInDown 0.6s ease;
        }

        .faq-hero p {
            color: var(--gray-300);
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
           FAQ SECTION
        ============================================ */
        .faq-section {
            padding: var(--spacing-3xl) 0;
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--white) 100%);
            border: 0.1rem solid var(--gray-200);
        }
        
        /* ============================================
           FAQ CATEGORY BUTTONS
        ============================================ */
        .faq-categories {
            display: flex;
            justify-content: center;
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-2xl);
            flex-wrap: wrap;
        }

        .faq-cat-btn {
            padding: 12px 28px;
            background: var(--white);
            border: 2px solid var(--gray-300);
            border-radius: var(--radius-2xl);
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--gray-700);
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .faq-cat-btn i {
            font-size: 1rem;
            transition: var(--transition);
        }

        .faq-cat-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }

        .faq-cat-btn.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }

        .faq-cat-btn.active i {
            color: var(--white);
        }

        /* ============================================
           FAQ GROUPS
        ============================================ */
        .faq-group {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .faq-group.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============================================
           FAQ ITEMS
        ============================================ */
        .faq-item {
            background: var(--white);
            border-radius: var(--radius-2xl);
            margin-bottom: var(--spacing-md);
            overflow: hidden;
            border: 0.15rem solid var(--gray-300);
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .faq-item:hover {
            box-shadow: var(--shadow-md);
            border-color: var(--primary-light);
        }

        .faq-question {
            padding: var(--spacing-lg) var(--spacing-xl);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            font-size: 1rem;
            color: var(--gray-800);
            transition: var(--transition);
            background: var(--white);
        }

        .faq-question:hover {
            background: linear-gradient(135deg, var(--primary-light), var(--white));
            color: var(--primary-dark);
        }

        .faq-question span {
            flex: 1;
            padding-right: var(--spacing-md);
        }

        .faq-question i {
            transition: transform 0.3s ease;
            color: var(--primary);
            font-size: 1rem;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: var(--primary-light);
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
            background: var(--primary);
            color: var(--white);
        }

        .faq-answer {
            padding: 0 var(--spacing-xl) var(--spacing-xl) var(--spacing-xl);
            display: none;
            color: var(--gray-600);
            line-height: 1.7;
            border-top: 1px solid var(--gray-100);
            background: var(--gray-50);
            font-size: 0.95rem;
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

        /* ============================================
           FAQ STATS SECTION
        ============================================ */
        .faq-stats {
            margin-top: var(--spacing-3xl);
            padding: var(--spacing-2xl);
            background: linear-gradient(135deg, var(--white), var(--gray-50));
            border-radius: var(--radius-2xl);
            border: 0.15rem solid var(--gray-300);
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--spacing-xl);
            margin-top: var(--spacing-xl);
        }

        .stat-card {
            text-align: center;
            padding: var(--spacing-lg);
        }

        .stat-card i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: var(--spacing-md);
        }

        .stat-card .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            display: block;
            margin-bottom: var(--spacing-xs);
        }

        .stat-card .stat-label {
            color: var(--gray-500);
            font-size: 0.85rem;
        }

        /* ============================================
           CONTACT SUPPORT SECTION
        ============================================ */
        .contact-support {
            margin-top: var(--spacing-3xl);
            text-align: center;
            padding: var(--spacing-2xl);
            background: linear-gradient(135deg, var(--primary-light), var(--white));
            border-radius: var(--radius-2xl);
            border: 0.15rem solid var(--gray-300);
        }

        .contact-support h3 {
            font-size: 1.3rem;
            margin-bottom: var(--spacing-md);
            color: var(--gray-800);
        }

        .contact-support p {
            color: var(--gray-600);
            margin-bottom: var(--spacing-lg);
        }

        .support-buttons {
            display: flex;
            justify-content: center;
            gap: var(--spacing-md);
            flex-wrap: wrap;
        }

        .support-btn {
            padding: 12px 28px;
            background: var(--white);
            border: 2px solid var(--primary);
            border-radius: var(--radius-lg);
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .support-btn:hover {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .support-btn.primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
        }

        .support-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 768px) {
            .faq-hero h1 {
                font-size: 2rem;
            }
            .faq-categories {
                gap: var(--spacing-sm);
            }
            .faq-cat-btn {
                padding: 8px 18px;
                font-size: 0.85rem;
            }
            .faq-question {
                padding: var(--spacing-md) var(--spacing-lg);
                font-size: 0.9rem;
            }
            .faq-answer {
                padding: 0 var(--spacing-lg) var(--spacing-lg) var(--spacing-lg);
            }
            .stats-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
            }
            .support-buttons {
                flex-direction: column;
                align-items: center;
            }
            .support-btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .faq-categories {
                gap: var(--spacing-xs);
            }
            .faq-cat-btn {
                padding: 6px 14px;
                font-size: 0.75rem;
            }
            .faq-question {
                padding: var(--spacing-md);
            }
        }
    </style>
</head>
<body>

<main>
    <!-- HERO SECTION -->
    <div class="faq-hero">
        <div class="container">
            <h1><i class="fas fa-question-circle"></i> Frequently Asked Questions</h1>
            <p>Find answers to common questions about Relyve</p>
        </div>
    </div>

    <div class="container faq-section" style="max-width: var(--container-width); margin: 40px auto; padding: 50px;">
        <!-- CATEGORY BUTTONS -->
        <div class="faq-categories">
            <button class="faq-cat-btn active" data-cat="general">
                <i class="fas fa-info-circle"></i> General
            </button>
            <button class="faq-cat-btn" data-cat="orders">
                <i class="fas fa-shopping-bag"></i> Orders
            </button>
            <button class="faq-cat-btn" data-cat="payment">
                <i class="fas fa-credit-card"></i> Payment
            </button>
            <button class="faq-cat-btn" data-cat="shipping">
                <i class="fas fa-truck"></i> Shipping
            </button>
            <button class="faq-cat-btn" data-cat="returns">
                <i class="fas fa-exchange-alt"></i> Returns
            </button>
        </div>

        <!-- General Questions -->
        <div class="faq-group active" id="general">
            <div class="faq-item">
                <div class="faq-question">
                    <span>What is Relyve?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Relyve is Bangladesh's leading online shopping platform offering a wide range of products including smartphones, laptops, accessories, and more at competitive prices. We are committed to providing a seamless shopping experience with quality products and reliable delivery.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>Is Relyve a trusted platform?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Yes! Relyve is fully registered and trusted by thousands of satisfied customers across Bangladesh. We prioritize customer satisfaction, secure transactions, and authentic products. Our platform uses industry-standard security measures to protect your information.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>How do I create an account?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Creating an account is easy! Click on the "Register" button at the top of the page, fill in your basic information (name, email, phone number), set a password, and you'll have an account in minutes. You can also sign up using your Google account for faster registration.</div>
            </div>
        </div>

        <!-- Orders Questions -->
        <div class="faq-group" id="orders">
            <div class="faq-item">
                <div class="faq-question">
                    <span>How do I track my order?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Tracking your order is simple! Log into your account, go to "My Orders" section, and click on the specific order you want to track. You'll see real-time updates on your order status including processing, shipping, and delivery updates.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>Can I cancel my order?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Yes, you can cancel your order within 2 hours of placing it. Simply go to "My Orders", find the order you wish to cancel, and click the cancel button. Orders that have already been processed or shipped cannot be cancelled.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>How do I modify my order?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Unfortunately, orders cannot be modified once placed. If you need to change something, please cancel the existing order within the cancellation window and place a new order with the correct items or quantities.</div>
            </div>
        </div>

        <!-- Payment Questions -->
        <div class="faq-group" id="payment">
            <div class="faq-item">
                <div class="faq-question">
                    <span>What payment methods do you accept?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">We accept multiple payment methods for your convenience: Cash on Delivery (COD), Credit/Debit Cards (Visa, Mastercard, Amex), bKash mobile payments, and Nagad. All online payments are processed through secure payment gateways.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>Is online payment secure?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Absolutely! We use industry-standard SSL encryption and PCI-compliant payment gateways to protect your financial information. Your card details are never stored on our servers, ensuring maximum security for your transactions.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>Do you offer EMI options?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Yes, we offer EMI options on select credit cards. You can choose from 3, 6, or 12-month installment plans on eligible products. Check the product page for EMI availability and detailed terms.</div>
            </div>
        </div>

        <!-- Shipping Questions -->
        <div class="faq-group" id="shipping">
            <div class="faq-item">
                <div class="faq-question">
                    <span>How long does delivery take?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Delivery typically takes 2-5 business days depending on your location. Dhaka city deliveries are faster (1-2 days), while outside Dhaka may take 3-5 days. You'll receive tracking updates via SMS and email.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>Do you ship nationwide?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Yes, we ship to all 64 districts of Bangladesh through our trusted courier partners including Sundarban Courier, SA Paribahan, and others. We're committed to making our products accessible to customers across the country.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>Is shipping free?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">We offer free shipping on all orders above ৳5,000. A flat rate of ৳100 applies to orders below this amount. During special promotions and festive seasons, we may offer free shipping on all orders.</div>
            </div>
        </div>

        <!-- Returns Questions -->
        <div class="faq-group" id="returns">
            <div class="faq-item">
                <div class="faq-question">
                    <span>What is your return policy?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">We offer a 7-day return policy on all products. Items must be unused, in original condition, and with all original packaging and tags. Certain products like personal care items may have different return policies.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>How do I return an item?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">To return an item, contact our customer support within 7 days of delivery. Our team will guide you through the process and arrange for a pickup from your address. Once we receive and inspect the item, your refund will be processed.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>How long do refunds take?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Refunds are processed within 5-7 business days after we receive and inspect the returned item. For COD orders, refunds are issued via bank transfer or bKash. For online payments, refunds are credited back to the original payment method.</div>
            </div>
        </div>

        <!-- FAQ STATS SECTION -->
        <div class="faq-stats">
            <h3><i class="fas fa-chart-line"></i> Our Support Statistics</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-clock"></i>
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Customer Support</span>
                </div>
                <div class="stat-card">
                    <i class="fas fa-reply-all"></i>
                    <span class="stat-number">&lt; 2 hrs</span>
                    <span class="stat-label">Average Response Time</span>
                </div>
                <div class="stat-card">
                    <i class="fas fa-smile"></i>
                    <span class="stat-number">98%</span>
                    <span class="stat-label">Customer Satisfaction</span>
                </div>
            </div>
        </div>

        <!-- CONTACT SUPPORT SECTION -->
        <div class="contact-support">
            <h3><i class="fas fa-headset"></i> Still Have Questions?</h3>
            <p>Can't find the answer you're looking for? Our customer support team is here to help.</p>
            <div class="support-buttons">
                <a href="contact.php" class="support-btn primary">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
                <a href="tel:+8801234567890" class="support-btn">
                    <i class="fas fa-phone-alt"></i> Call Support
                </a>
                <a href="#" class="support-btn" id="liveChatBtn">
                    <i class="fas fa-comments"></i> Live Chat
                </a>
            </div>
        </div>
    </div>
</main>

<script>
// Category switching
document.querySelectorAll('.faq-cat-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Update active button
        document.querySelectorAll('.faq-cat-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Show selected category
        const cat = this.getAttribute('data-cat');
        document.querySelectorAll('.faq-group').forEach(group => group.classList.remove('active'));
        document.getElementById(cat).classList.add('active');
        
        // Scroll to top of FAQ section
        document.querySelector('.faq-section').scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
    });
});

// FAQ accordion functionality
document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', function() {
        const item = this.parentElement;
        const isActive = item.classList.contains('active');
        
        // Close all other items in the same group
        const parentGroup = item.closest('.faq-group');
        if (parentGroup) {
            parentGroup.querySelectorAll('.faq-item').forEach(faqItem => {
                if (faqItem !== item) {
                    faqItem.classList.remove('active');
                }
            });
        }
        
        // Toggle current item
        item.classList.toggle('active');
    });
});

// Live chat button
document.getElementById('liveChatBtn')?.addEventListener('click', function(e) {
    e.preventDefault();
    showNotification('Live chat feature coming soon!', 'info');
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
    const firstFaqItem = document.querySelector('.faq-group.active .faq-item');
    if (firstFaqItem) {
        firstFaqItem.classList.add('active');
    }
});
</script>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>