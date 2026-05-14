<?php
session_start();
include_once 'includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Information - Relyve</title>
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
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--primary);
            font-weight: bold;
        }

        /* Tables */
        .shipping-table {
            width: 100%;
            border-collapse: collapse;
            margin: var(--spacing-lg) 0;
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .shipping-table th, 
        .shipping-table td {
            padding: 12px 16px;
            border: 1px solid var(--gray-200);
            text-align: left;
        }

        .shipping-table th {
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            font-weight: 600;
            color: var(--gray-800);
        }

        .shipping-table td {
            color: var(--gray-600);
        }

        .shipping-table tr:hover td {
            background: var(--gray-50);
        }

        /* Info Cards Grid */
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
            font-size: 1rem;
            margin-bottom: var(--spacing-xs);
            color: var(--gray-800);
        }

        .info-card p {
            font-size: 0.8rem;
            margin-bottom: 0;
            color: var(--gray-500);
        }

        /* Highlight Box */
        .highlight-box {
            background: linear-gradient(135deg, #fef3c7, #ffedd5);
            padding: var(--spacing-md) var(--spacing-lg);
            border-radius: var(--radius-lg);
            margin: var(--spacing-lg) 0;
            border-left: 4px solid #f59e0b;
        }

        .highlight-box p {
            margin-bottom: 0;
            color: #92400e;
        }

        .highlight-box i {
            margin-right: var(--spacing-sm);
            color: #f59e0b;
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

        /* Track Button */
        .track-btn {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: 10px 24px;
            border-radius: var(--radius-lg);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            margin-top: var(--spacing-md);
        }

        .track-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
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
            .info-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
            }
            .shipping-table {
                font-size: 0.8rem;
            }
            .shipping-table th, 
            .shipping-table td {
                padding: 8px 12px;
            }
        }

        @media (max-width: 480px) {
            .policy-content {
                padding: var(--spacing-md);
            }
            .shipping-table {
                font-size: 0.7rem;
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
            <span class="current">Shipping Information</span>
        </div>

        <!-- Policy Header -->
        <div class="policy-header">
            <h1>
                <i class="fas fa-truck"></i>
                Shipping Information
            </h1>
            <p>Fast and reliable delivery across Bangladesh</p>
        </div>
        
        <!-- Policy Content -->
        <div class="policy-content">
            <!-- Info Cards Grid -->
            <div class="info-grid">
                <div class="info-card">
                    <i class="fas fa-map-marker-alt"></i>
                    <h4>64 Districts</h4>
                    <p>Delivery across all districts</p>
                </div>
                <div class="info-card">
                    <i class="fas fa-clock"></i>
                    <h4>1-5 Days</h4>
                    <p>Fast delivery time</p>
                </div>
                <div class="info-card">
                    <i class="fas fa-gift"></i>
                    <h4>Free Shipping</h4>
                    <p>On orders over ৳5,000</p>
                </div>
            </div>

            <h2><i class="fas fa-map-marked-alt"></i> Delivery Areas</h2>
            <p>We deliver to all 64 districts of Bangladesh through our trusted courier partners including Sundarban Courier, SA Paribahan, RedX, and others. Our extensive network ensures that your orders reach you no matter where you are in the country.</p>
            
            <h2><i class="fas fa-money-bill-wave"></i> Shipping Rates</h2>
            <table class="shipping-table">
                <thead>
                    <tr>
                        <th>Order Value</th>
                        <th>Shipping Fee</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Below ৳5,000</td>
                        <td>৳100 (Flat Rate)</td>
                    </tr>
                    <tr>
                        <td>৳5,000 and above</td>
                        <td><strong class="text-success">FREE Shipping</strong> <i class="fas fa-gift"></i></td>
                    </tr>
                </tbody>
            </table>
            
            <h2><i class="fas fa-hourglass-half"></i> Delivery Time</h2>
            <table class="shipping-table">
                <thead>
                    <tr>
                        <th>Location</th>
                        <th>Estimated Delivery</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><i class="fas fa-city"></i> Dhaka City (Inside City Corporation)</td>
                        <td>1-2 business days</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-map-pin"></i> Dhaka (Outside City Corporation)</td>
                        <td>2-3 business days</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-building"></i> Other Major Cities</td>
                        <td>2-4 business days</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-tree"></i> Other Districts</td>
                        <td>3-5 business days</td>
                    </tr>
                </tbody>
            </table>
            
            <h2><i class="fas fa-box"></i> Order Processing</h2>
            <p>Orders are processed within 24 hours of placement (excluding Fridays and public holidays). You will receive a confirmation email with tracking information once your order is shipped. Orders placed before 12 PM are processed the same day.</p>
            
            <div class="highlight-box">
                <p><i class="fas fa-info-circle"></i> <strong>Note:</strong> Delivery times may vary during peak seasons (Eid, New Year, etc.) and adverse weather conditions. We'll keep you updated on your order status via SMS and email.</p>
            </div>
            
            <h2><i class="fas fa-search"></i> Tracking Your Order</h2>
            <p>You can track your order by using any of these methods:</p>
            <ul>
                <li>Visiting our <a href="track_order.php" style="color: var(--primary);">Track Order page</a> and entering your order number</li>
                <li>Using the tracking link sent to your email after shipment</li>
                <li>Contacting our customer support with your order number</li>
                <li>Checking the status in your account dashboard under "My Orders"</li>
            </ul>
            
            <div style="text-align: center;">
                <a href="track_order.php" class="track-btn">
                    <i class="fas fa-search"></i> Track Your Order
                </a>
            </div>
            
            <h2><i class="fas fa-clock"></i> What to Do If Your Order Is Delayed</h2>
            <p>If your order hasn't arrived within the estimated timeframe:</p>
            <ul>
                <li>Check the tracking information first for any updates</li>
                <li>Contact our support team with your order number</li>
                <li>Our team will investigate and update you within 24 hours</li>
                <li>If lost, we will initiate a replacement or refund</li>
            </ul>
            
            <h2><i class="fas fa-globe"></i> International Shipping</h2>
            <p>Currently, we only ship within Bangladesh. International shipping is coming soon! We're working on expanding our services to serve customers worldwide. Stay tuned for updates.</p>
            
            <!-- Contact Section -->
            <div class="contact-section">
                <p><i class="fas fa-headset"></i> Have questions about shipping?</p>
                <p>Contact our support team at <a href="mailto:shipping@relyve.com">shipping@relyve.com</a> or call us at <a href="tel:+8801234567890">+880 1234-567890</a></p>
                <p><small>Available Sunday-Thursday, 9AM - 8PM</small></p>
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