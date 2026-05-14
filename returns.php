<?php
session_start();
include_once 'includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return & Refund Policy - Relyve</title>
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
            font-size: 1.1rem;
        }

        /* Paragraphs */
        .policy-content p {
            color: var(--gray-600);
            line-height: 1.7;
            margin-bottom: var(--spacing-md);
            font-size: 0.95rem;
        }

        /* Lists */
        .policy-content ul, .policy-content ol {
            margin: var(--spacing-md) 0 var(--spacing-lg) var(--spacing-xl);
            padding-left: 0;
        }

        .policy-content li {
            color: var(--gray-600);
            margin-bottom: var(--spacing-sm);
            line-height: 1.6;
            position: relative;
            padding-left: var(--spacing-md);
        }

        .policy-content ul li {
            list-style: none;
        }

        .policy-content ul li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--primary);
            font-weight: bold;
        }

        .policy-content ol li {
            margin-left: var(--spacing-md);
        }

        /* Return Steps Box */
        .return-steps {
            background: linear-gradient(135deg, #fef3c7, #ffedd5);
            padding: var(--spacing-xl);
            border-radius: var(--radius-xl);
            margin: var(--spacing-xl) 0;
            border: 1px solid #fde68a;
            transition: var(--transition);
        }

        .return-steps:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .return-steps h3 {
            color: #d97706;
            margin-bottom: var(--spacing-md);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            font-size: 1.1rem;
        }

        .return-steps h3 i {
            font-size: 1.3rem;
        }

        .return-steps ol {
            margin: 0;
            padding-left: var(--spacing-lg);
        }

        .return-steps li {
            color: var(--gray-700);
            margin-bottom: var(--spacing-sm);
            padding-left: 0;
        }

        /* Grid for Policy Highlights */
        .policy-highlights {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--spacing-lg);
            margin: var(--spacing-xl) 0;
        }

        .highlight-card {
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            padding: var(--spacing-lg);
            border-radius: var(--radius-lg);
            text-align: center;
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .highlight-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-light);
        }

        .highlight-card i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: var(--spacing-sm);
        }

        .highlight-card h4 {
            font-size: 1rem;
            margin-bottom: var(--spacing-xs);
            color: var(--gray-800);
        }

        .highlight-card p {
            font-size: 0.8rem;
            margin-bottom: 0;
            color: var(--gray-500);
        }

        /* Info Boxes */
        .info-box {
            background: linear-gradient(135deg, #dbeafe, #eff6ff);
            padding: var(--spacing-md) var(--spacing-lg);
            border-radius: var(--radius-lg);
            margin: var(--spacing-lg) 0;
            border-left: 4px solid #3b82f6;
        }

        .info-box p {
            margin-bottom: 0;
            color: #1e40af;
        }

        .info-box i {
            margin-right: var(--spacing-sm);
            color: #3b82f6;
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
            .policy-highlights {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
            }
            .policy-content h2 {
                font-size: 1.1rem;
            }
            .return-steps {
                padding: var(--spacing-lg);
            }
        }

        @media (max-width: 480px) {
            .policy-content {
                padding: var(--spacing-md);
            }
            .policy-content ul, .policy-content ol {
                margin-left: var(--spacing-md);
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
            <span class="current">Return & Refund Policy</span>
        </div>

        <!-- Policy Header -->
        <div class="policy-header">
            <h1>
                <i class="fas fa-undo-alt"></i>
                Return & Refund Policy
            </h1>
            <p>Our commitment to your satisfaction</p>
        </div>
        
        <!-- Policy Content -->
        <div class="policy-content">
            <h2><i class="fas fa-calendar-week"></i> 7-Day Return Policy</h2>
            <p>We offer a 7-day return policy on most products. Returns are accepted within 7 days of delivery for eligible items. Your satisfaction is our top priority, and we're here to make the return process as smooth as possible.</p>
            
            <!-- Policy Highlights Grid -->
            <div class="policy-highlights">
                <div class="highlight-card">
                    <i class="fas fa-clock"></i>
                    <h4>7 Days Return</h4>
                    <p>Return within 7 days of delivery</p>
                </div>
                <div class="highlight-card">
                    <i class="fas fa-truck"></i>
                    <h4>Free Pickup</h4>
                    <p>Free return pickup for defective items</p>
                </div>
                <div class="highlight-card">
                    <i class="fas fa-money-bill-wave"></i>
                    <h4>Full Refund</h4>
                    <p>Full refund on eligible returns</p>
                </div>
            </div>
            
            <h2><i class="fas fa-check-circle"></i> Eligibility Criteria</h2>
            <p>To be eligible for a return, the following conditions must be met:</p>
            <ul>
                <li>Item must be unused and in original condition</li>
                <li>All original packaging, tags, and accessories must be included</li>
                <li>Product must not be damaged by improper use</li>
                <li>Items must be returned within 7 days of delivery</li>
                <li>Proof of purchase (order number) is required</li>
            </ul>
            
            <h2><i class="fas fa-ban"></i> Non-Returnable Items</h2>
            <p>The following items cannot be returned due to hygiene and safety reasons:</p>
            <ul>
                <li>Personal care products (earphones, headphones, etc.)</li>
                <li>Perishable goods and food items</li>
                <li>Customized or personalized items</li>
                <li>Software, games, and digital products</li>
                <li>Items marked as "Final Sale" or "Non-Returnable"</li>
                <li>Intimate apparel and swimwear</li>
            </ul>
            
            <!-- Return Steps Box -->
            <div class="return-steps">
                <h3><i class="fas fa-box-open"></i> How to Return an Item</h3>
                <ol>
                    <li>Contact our customer support within 7 days of delivery</li>
                    <li>Provide your order number and reason for return</li>
                    <li>Our team will guide you through the return process</li>
                    <li>Pack the item securely in original packaging</li>
                    <li>Wait for pickup or drop off at designated location</li>
                    <li>Receive your refund after quality inspection</li>
                </ol>
            </div>
            
            <h2><i class="fas fa-credit-card"></i> Refund Process</h2>
            <p>Once we receive and inspect your returned item, we will process your refund:</p>
            <ul>
                <li>Refunds are processed within 5-7 business days after inspection</li>
                <li>Refunds are issued to the original payment method</li>
                <li>Cash on Delivery orders are refunded via bank transfer or bKash</li>
                <li>Shipping fees are non-refundable unless the item is defective</li>
                <li>You will receive an email confirmation once your refund is processed</li>
            </ul>
            
            <div class="info-box">
                <p><i class="fas fa-info-circle"></i> Note: Refunds may take 3-5 business days to appear in your account depending on your bank or payment provider.</p>
            </div>
            
            <h2><i class="fas fa-box-tissue"></i> Damaged or Defective Items</h2>
            <p>If you receive a damaged or defective item, please contact us within 24 hours of delivery with clear photos of the damage. We will arrange a replacement or full refund at no extra cost, including return shipping fees.</p>
            
            <h2><i class="fas fa-exchange-alt"></i> Exchange Policy</h2>
            <p>We offer exchanges for size or color variants subject to availability. Contact our support team to initiate an exchange. Exchange items must be unused and in original condition. Shipping fees for exchanges are covered by the customer unless the item is defective.</p>
            
            <h2><i class="fas fa-times-circle"></i> Cancellation Policy</h2>
            <p>Orders can be cancelled within 2 hours of placement without any charges. Once an order is processed or shipped, cancellations are subject to our return policy. To cancel an order, please contact our customer support team immediately.</p>
            
            <!-- Contact Section -->
            <div class="contact-section">
                <p><i class="fas fa-envelope"></i> Have questions about returns or refunds?</p>
                <p>Contact our support team at <a href="mailto:returns@relyve.com">returns@relyve.com</a> or call us at <a href="tel:+8801234567890">+880 1234-567890</a></p>
                <p><small>Our support team is available 24/7 to assist you</small></p>
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