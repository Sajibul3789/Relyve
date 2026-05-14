<?php
session_start();
include_once 'includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Relyve</title>
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
            font-size: 2.2rem;
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
            font-size: 1.3rem;
            margin: var(--spacing-xl) 0 var(--spacing-md);
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding-bottom: var(--spacing-xs);
            border-bottom: 2px solid var(--primary-light);
            display: inline-flex;
        }

        .policy-content h2:first-child {
            margin-top: 0;
        }

        .policy-content h2 i {
            color: var(--primary);
            font-size: 1.2rem;
        }

        /* Paragraphs */
        .policy-content p {
            color: var(--gray-600);
            line-height: 1.7;
            margin-bottom: var(--spacing-md);
            font-size: 0.95rem;
        }

        /* Lists */
        .policy-content ul {
            margin: var(--spacing-md) 0 var(--spacing-lg) var(--spacing-xl);
            padding-left: 0;
            list-style: none;
        }

        .policy-content li {
            color: var(--gray-600);
            margin-bottom: var(--spacing-sm);
            position: relative;
            padding-left: var(--spacing-xl);
            line-height: 1.6;
        }

        .policy-content li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--primary);
            font-weight: bold;
        }

        /* Grid for Sections */
        .policy-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--spacing-xl);
            margin: var(--spacing-xl) 0;
        }

        .policy-card {
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            padding: var(--spacing-lg);
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .policy-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-light);
        }

        .policy-card h3 {
            font-size: 1rem;
            margin-bottom: var(--spacing-md);
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .policy-card h3 i {
            color: var(--primary);
            font-size: 1.1rem;
        }

        .policy-card p {
            font-size: 0.85rem;
            margin-bottom: 0;
            color: var(--gray-600);
            line-height: 1.6;
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
            padding: var(--spacing-xl);
            background: linear-gradient(135deg, var(--primary-light), var(--white));
            border-radius: var(--radius-lg);
            text-align: center;
            border: 1px solid var(--gray-200);
        }

        .contact-section p {
            margin-bottom: var(--spacing-sm);
            color: var(--gray-700);
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
            .policy-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
            }
            .policy-content h2 {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            .policy-content {
                padding: var(--spacing-md);
            }
            .policy-content ul {
                margin-left: var(--spacing-md);
            }
            .policy-content li {
                padding-left: var(--spacing-lg);
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
            <span class="current">Privacy Policy</span>
        </div>

        <!-- Policy Header -->
        <div class="policy-header">
            <h1>
                <i class="fas fa-shield-alt"></i>
                Privacy Policy
            </h1>
            <p>How we collect, use, and protect your information</p>
        </div>
        
        <!-- Policy Content -->
        <div class="policy-content">
            <h2><i class="fas fa-database"></i> Information We Collect</h2>
            <p>We collect information you provide directly to us, including:</p>
            <ul>
                <li>Name, email address, phone number, and shipping address</li>
                <li>Payment information (processed securely through third-party providers)</li>
                <li>Order history and preferences</li>
                <li>Communications with our customer support team</li>
            </ul>
            
            <h2><i class="fas fa-chart-line"></i> How We Use Your Information</h2>
            <p>We use your information to:</p>
            <ul>
                <li>Process and fulfill your orders</li>
                <li>Communicate about your orders and account</li>
                <li>Improve our products and services</li>
                <li>Send promotional offers (you can opt out anytime)</li>
                <li>Prevent fraud and ensure security</li>
            </ul>
            
            <!-- Policy Grid -->
            <div class="policy-grid">
                <div class="policy-card">
                    <h3><i class="fas fa-share-alt"></i> Information Sharing</h3>
                    <p>We do not sell your personal information. We may share your information with shipping partners, payment processors, or legal authorities when required by law.</p>
                </div>
                <div class="policy-card">
                    <h3><i class="fas fa-lock"></i> Data Security</h3>
                    <p>We implement industry-standard security measures including SSL encryption for all transactions to protect your information.</p>
                </div>
                <div class="policy-card">
                    <h3><i class="fas fa-cookie-bite"></i> Cookies</h3>
                    <p>We use cookies to enhance your browsing experience, remember your preferences, and analyze site traffic. You can disable cookies in your browser settings.</p>
                </div>
                <div class="policy-card">
                    <h3><i class="fas fa-user-check"></i> Your Rights</h3>
                    <p>You have the right to access, correct, or delete your personal information. Contact us for assistance.</p>
                </div>
            </div>
            
            <h2><i class="fas fa-child"></i> Children's Privacy</h2>
            <p>Our services are not intended for children under 13. We do not knowingly collect information from children. If you believe we have collected information from a child, please contact us immediately.</p>
            
            <h2><i class="fas fa-edit"></i> Changes to This Policy</h2>
            <p>We may update this privacy policy periodically. We will notify you of any significant changes via email or website notice. Please review this policy regularly to stay informed about how we protect your information.</p>
            
            <!-- Contact Section -->
            <div class="contact-section">
                <p><i class="fas fa-envelope"></i> Have questions about our privacy policy?</p>
                <p>Contact us at <a href="mailto:privacy@relyve.com">privacy@relyve.com</a> for any privacy-related concerns.</p>
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