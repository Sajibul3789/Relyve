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
        .faq-hero {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        .faq-hero h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        .faq-section {
            padding: 60px 0;
        }
        .faq-categories {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        .faq-cat-btn {
            padding: 10px 25px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 30px;
            cursor: pointer;
            transition: var(--transition);
        }
        .faq-cat-btn.active, .faq-cat-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .faq-group {
            display: none;
        }
        .faq-group.active {
            display: block;
        }
        .faq-item {
            background: white;
            border-radius: 12px;
            margin-bottom: 15px;
            overflow: hidden;
        }
        .faq-question {
            padding: 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            transition: var(--transition);
        }
        .faq-question:hover {
            background: #f9fafb;
        }
        .faq-question i {
            transition: transform 0.3s;
        }
        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }
        .faq-answer {
            padding: 0 20px 20px 20px;
            display: none;
            color: var(--text-light);
            line-height: 1.6;
            border-top: 1px solid #f0f0f0;
        }
        .faq-item.active .faq-answer {
            display: block;
        }
        @media (max-width: 768px) {
            .faq-categories {
                gap: 10px;
            }
            .faq-cat-btn {
                padding: 8px 16px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="faq-hero">
        <div class="container">
            <h1>Frequently Asked Questions</h1>
            <p>Find answers to common questions about Relyve</p>
        </div>
    </div>

    <div class="container faq-section">
        <div class="faq-categories">
            <button class="faq-cat-btn active" data-cat="general">General</button>
            <button class="faq-cat-btn" data-cat="orders">Orders</button>
            <button class="faq-cat-btn" data-cat="payment">Payment</button>
            <button class="faq-cat-btn" data-cat="shipping">Shipping</button>
            <button class="faq-cat-btn" data-cat="returns">Returns</button>
        </div>

        <!-- General Questions -->
        <div class="faq-group active" id="general">
            <div class="faq-item">
                <div class="faq-question">
                    <span>What is Relyve?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Relyve is Bangladesh's leading online shopping platform offering a wide range of products including smartphones, laptops, accessories, and more at competitive prices.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>Is Relyve a trusted platform?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Yes! Relyve is fully registered and trusted by thousands of customers across Bangladesh. We prioritize customer satisfaction and secure transactions.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>How do I create an account?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Click on the "Register" button at the top of the page, fill in your details, and you'll have an account in minutes. You can also sign up using Google.</div>
            </div>
        </div>

        <!-- Orders Questions -->
        <div class="faq-group" id="orders">
            <div class="faq-item">
                <div class="faq-question">
                    <span>How do I track my order?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Log into your account, go to "My Orders", and click on the order you want to track. You'll see real-time updates on your order status.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>Can I cancel my order?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Yes, you can cancel your order within 2 hours of placing it. Go to "My Orders" and click the cancel button next to your order.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>How do I modify my order?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Unfortunately, orders cannot be modified once placed. Please cancel and place a new order with the correct items.</div>
            </div>
        </div>

        <!-- Payment Questions -->
        <div class="faq-group" id="payment">
            <div class="faq-item">
                <div class="faq-question">
                    <span>What payment methods do you accept?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">We accept Cash on Delivery (COD), Credit/Debit Cards (Visa, Mastercard, Amex), and bKash mobile payments.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>Is online payment secure?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Absolutely! We use secure SSL encryption and PCI-compliant payment gateways to protect your financial information.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>Do you offer EMI options?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Yes, we offer 3,6,12 months EMI on select credit cards. Check product page for EMI eligibility.</div>
            </div>
        </div>

        <!-- Shipping Questions -->
        <div class="faq-group" id="shipping">
            <div class="faq-item">
                <div class="faq-question">
                    <span>How long does delivery take?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Delivery typically takes 2-5 business days depending on your location. Dhaka city deliveries are faster (1-2 days).</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>Do you ship nationwide?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Yes, we ship to all 64 districts of Bangladesh through our trusted courier partners.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>Is shipping free?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">We offer free shipping on all orders above ৳5,000. A flat rate of ৳100 applies to orders below this amount.</div>
            </div>
        </div>

        <!-- Returns Questions -->
        <div class="faq-group" id="returns">
            <div class="faq-item">
                <div class="faq-question">
                    <span>What is your return policy?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">We offer a 7-day return policy on all products. Items must be unused and in original packaging.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>How do I return an item?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Contact our customer support within 7 days of delivery. We'll arrange a pickup and process your refund.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>How long do refunds take?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">Refunds are processed within 5-7 business days after we receive and inspect the returned item.</div>
            </div>
        </div>
    </div>
</main>

<script>
document.querySelectorAll('.faq-cat-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.faq-cat-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const cat = this.getAttribute('data-cat');
        document.querySelectorAll('.faq-group').forEach(group => group.classList.remove('active'));
        document.getElementById(cat).classList.add('active');
    });
});

document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', function() {
        const item = this.parentElement;
        item.classList.toggle('active');
    });
});
</script>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>