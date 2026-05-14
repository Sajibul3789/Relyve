<?php
session_start();
include_once 'includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* ============================================
           POLICY CONTAINER
        ============================================ */
        .policy-container {
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

        /* Policy Header */
        .policy-header {
            text-align: center;
            margin-bottom: var(--spacing-2xl);
            padding-bottom: var(--spacing-xl);
            border-bottom: 2px solid var(--gray-200);
        }

        .policy-header h1 {
            font-size: 2rem;
            margin-bottom: var(--spacing-md);
            color: var(--gray-900);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-md);
        }

        .policy-header h1 i {
            color: var(--primary);
            font-size: 2rem;
        }

        .policy-header p {
            color: var(--gray-500);
            font-size: 1rem;
        }

        /* Policy Content Card */
        .policy-content {
            background: var(--white);
            border-radius: var(--radius-xl);
            padding: var(--spacing-2xl);
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-md);
            transition: var(--transition);
        }

        .policy-content:hover {
            box-shadow: var(--shadow-lg);
        }

        /* Section Headers */
        .policy-content h2 {
            font-size: 1.2rem;
            margin: var(--spacing-xl) 0 var(--spacing-md);
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            padding: var(--spacing-sm) var(--spacing-md);
            border-radius: var(--radius-lg);
            border-left: 3px solid var(--primary);
        }

        .policy-content h2:first-child {
            margin-top: 0;
        }

        .policy-content h2 i {
            color: var(--primary);
            font-size: 1rem;
        }

        /* Paragraphs */
        .policy-content p {
            color: var(--gray-600);
            line-height: 1.7;
            margin-bottom: var(--spacing-md);
            font-size: 0.95rem;
            padding-left: var(--spacing-md);
        }

        /* Lists */
        .policy-content ul {
            margin: var(--spacing-md) 0 var(--spacing-lg) var(--spacing-xl);
            padding-left: 0;
        }

        .policy-content li {
            color: var(--gray-600);
            margin-bottom: var(--spacing-sm);
            line-height: 1.6;
            position: relative;
            padding-left: var(--spacing-md);
            list-style: none;
        }

        .policy-content li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: var(--primary);
            font-weight: bold;
            font-size: 1rem;
        }

        /* Important Notice Box */
        .notice-box {
            background: linear-gradient(135deg, #fef3c7, #ffedd5);
            padding: var(--spacing-lg);
            border-radius: var(--radius-lg);
            margin: var(--spacing-xl) 0;
            border-left: 4px solid #f59e0b;
        }

        .notice-box p {
            margin-bottom: 0;
            color: #92400e;
            padding-left: 0;
        }

        .notice-box i {
            color: #f59e0b;
            margin-right: var(--spacing-sm);
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--spacing-lg);
            margin: var(--spacing-xl) 0;
        }

        .info-card {
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            padding: var(--spacing-lg);
            border-radius: var(--radius-lg);
            text-align: center;
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-light);
        }

        .info-card i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: var(--spacing-sm);
        }

        .info-card h4 {
            font-size: 0.9rem;
            margin-bottom: var(--spacing-xs);
            color: var(--gray-800);
        }

        .info-card p {
            font-size: 0.75rem;
            margin-bottom: 0;
            color: var(--gray-500);
            padding-left: 0;
        }

        /* Last Updated */
        .last-updated {
            margin-top: var(--spacing-xl);
            padding-top: var(--spacing-lg);
            border-top: 1px solid var(--gray-200);
            font-size: 0.8rem;
            color: var(--gray-400);
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-sm);
        }

        /* Contact Section */
        .contact-section {
            margin-top: var(--spacing-xl);
            padding: var(--spacing-lg);
            background: linear-gradient(135deg, var(--primary-light), var(--white));
            border-radius: var(--radius-lg);
            text-align: center;
            border: 1px solid var(--gray-200);
        }

        .contact-section p {
            margin-bottom: var(--spacing-sm);
            color: var(--gray-700);
            padding-left: 0;
        }

        .contact-section a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .contact-section a:hover {
            text-decoration: underline;
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 768px) {
            .policy-container {
                padding: 0 var(--spacing-md);
                margin: var(--spacing-xl) auto;
            }
            .policy-header h1 {
                font-size: 1.6rem;
            }
            .policy-content {
                padding: var(--spacing-lg);
            }
            .policy-content h2 {
                font-size: 1rem;
            }
            .info-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
            }
        }

        @media (max-width: 480px) {
            .policy-content {
                padding: var(--spacing-md);
            }
            .policy-content p {
                padding-left: var(--spacing-xs);
            }
            .policy-content h2 {
                padding: var(--spacing-xs) var(--spacing-sm);
            }
        }
    </style>
</head>
<body>

<main>
    <div class="policy-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <span><i class="fas fa-chevron-right"></i></span>
            <span class="current">Terms & Conditions</span>
        </div>

        <!-- Policy Header -->
        <div class="policy-header">
            <h1>
                <i class="fas fa-gavel"></i>
                Terms & Conditions
            </h1>
            <p>Please read these terms carefully before using our services</p>
        </div>
        
        <!-- Policy Content -->
        <div class="policy-content">
            <!-- Important Notice -->
            <div class="notice-box">
                <p><i class="fas fa-info-circle"></i> <strong>Important:</strong> By accessing and using Relyve.com, you agree to be bound by these Terms & Conditions. If you do not agree, please do not use our website.</p>
            </div>

            <!-- Key Points Grid -->
            <div class="info-grid">
                <div class="info-card">
                    <i class="fas fa-shield-alt"></i>
                    <h4>Secure Shopping</h4>
                    <p>100% secure transactions</p>
                </div>
                <div class="info-card">
                    <i class="fas fa-undo-alt"></i>
                    <h4>7-Day Returns</h4>
                    <p>Hassle-free returns</p>
                </div>
                <div class="info-card">
                    <i class="fas fa-truck"></i>
                    <h4>Fast Delivery</h4>
                    <p>Free shipping over ৳5000</p>
                </div>
            </div>
            
            <h2><i class="fas fa-check-circle"></i> 1. Acceptance of Terms</h2>
            <p>By accessing and using Relyve.com (the "Website"), you agree to be bound by these Terms & Conditions, our Privacy Policy, and all applicable laws and regulations. If you do not agree with any of these terms, you are prohibited from using or accessing this site. We reserve the right to modify these terms at any time without prior notice.</p>
            
            <h2><i class="fas fa-user-plus"></i> 2. Account Registration</h2>
            <p>To place orders on Relyve, you must create an account. You are responsible for maintaining the confidentiality of your account information and for all activities that occur under your account. You agree to provide accurate, current, and complete information during registration and to update such information to keep it accurate, current, and complete.</p>
            <ul>
                <li>You must be at least 18 years old to create an account</li>
                <li>You are responsible for all activities under your account</li>
                <li>Notify us immediately of any unauthorized use of your account</li>
                <li>We reserve the right to terminate accounts at our discretion</li>
            </ul>
            
            <h2><i class="fas fa-box-open"></i> 3. Product Information</h2>
            <p>We strive to display accurate product information, including prices, descriptions, and images. However, we do not guarantee that all information is error-free, complete, or current. Colors may vary depending on your screen settings. We reserve the right to correct any errors, inaccuracies, or omissions and to change or update information at any time without prior notice.</p>
            
            <h2><i class="fas fa-taka-sign"></i> 4. Pricing and Payment</h2>
            <p>All prices are listed in Bangladeshi Taka (৳) and include applicable taxes unless stated otherwise. We reserve the right to change prices at any time without notice. Payment can be made via:</p>
            <ul>
                <li>Cash on Delivery (COD)</li>
                <li>Credit/Debit Cards (Visa, Mastercard, Amex)</li>
                <li>bKash mobile payments</li>
                <li>Nagad (coming soon)</li>
            </ul>
            
            <h2><i class="fas fa-shopping-cart"></i> 5. Order Acceptance</h2>
            <p>We reserve the right to refuse or cancel any order for any reason, including but not limited to product availability, pricing errors, or suspected fraud. If your order is cancelled after payment, we will issue a full refund to the original payment method.</p>
            
            <h2><i class="fas fa-truck"></i> 6. Shipping and Delivery</h2>
            <p>Delivery times are estimates and not guaranteed. We are not responsible for delays caused by courier services, customs, or unforeseen circumstances. Shipping fees are non-refundable except in cases of our error. Risk of loss passes to you upon delivery to the shipping carrier.</p>
            
            <h2><i class="fas fa-undo-alt"></i> 7. Returns and Refunds</h2>
            <p>We offer a 7-day return policy on eligible products. To be eligible for a return:</p>
            <ul>
                <li>Items must be unused and in original condition</li>
                <li>All original packaging, tags, and accessories must be included</li>
                <li>Items must be returned within 7 days of delivery</li>
                <li>Proof of purchase (order number) is required</li>
            </ul>
            <p>Refunds are processed within 5-7 business days after inspection. Shipping fees are non-refundable unless the item is defective.</p>
            
            <h2><i class="fas fa-medal"></i> 8. Warranty</h2>
            <p>Product warranties are provided by the manufacturer. Relyve acts as a facilitator and is not responsible for warranty claims beyond facilitating the process between you and the manufacturer. Warranty terms vary by product and manufacturer.</p>
            
            <h2><i class="fas fa-balance-scale"></i> 9. Limitation of Liability</h2>
            <p>To the maximum extent permitted by law, Relyve shall not be liable for any indirect, incidental, special, consequential, or punitive damages, or any loss of profits or revenues, whether incurred directly or indirectly, or any loss of data, use, goodwill, or other intangible losses, resulting from the use of our products or services.</p>
            
            <h2><i class="fas fa-edit"></i> 10. Changes to Terms</h2>
            <p>We may update these Terms & Conditions at any time without prior notice. Continued use of our website after any changes constitutes acceptance of the updated terms. We encourage you to review this page periodically for any changes.</p>
            
            <!-- Contact Section -->
            <div class="contact-section">
                <p><i class="fas fa-envelope"></i> Have questions about our Terms & Conditions?</p>
                <p>Contact us at <a href="mailto:legal@relyve.com">legal@relyve.com</a> for any legal inquiries.</p>
            </div>
            
            <div class="last-updated">
                <i class="far fa-calendar-alt"></i>
                Last Updated: January 1, 2024
            </div>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>