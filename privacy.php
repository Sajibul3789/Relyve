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
        .policy-container {
            max-width: 1000px;
            margin: 60px auto;
            padding: 0 20px;
        }
        .policy-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .policy-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .policy-content {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .policy-content h2 {
            font-size: 1.3rem;
            margin: 25px 0 15px;
            color: #333;
        }
        .policy-content h2:first-child {
            margin-top: 0;
        }
        .policy-content p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .policy-content ul {
            margin: 15px 0;
            padding-left: 20px;
        }
        .policy-content li {
            color: #666;
            margin-bottom: 8px;
        }
        .last-updated {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 0.85rem;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>

<main>
    <div class="policy-container">
        <div class="policy-header">
            <h1>Privacy Policy</h1>
            <p>How we collect, use, and protect your information</p>
        </div>
        
        <div class="policy-content">
            <h2>Information We Collect</h2>
            <p>We collect information you provide directly to us, including:</p>
            <ul>
                <li>Name, email address, phone number, and shipping address</li>
                <li>Payment information (processed securely through third-party providers)</li>
                <li>Order history and preferences</li>
                <li>Communications with our customer support team</li>
            </ul>
            
            <h2>How We Use Your Information</h2>
            <p>We use your information to:</p>
            <ul>
                <li>Process and fulfill your orders</li>
                <li>Communicate about your orders and account</li>
                <li>Improve our products and services</li>
                <li>Send promotional offers (you can opt out anytime)</li>
                <li>Prevent fraud and ensure security</li>
            </ul>
            
            <h2>Information Sharing</h2>
            <p>We do not sell your personal information. We may share your information with:</p>
            <ul>
                <li>Shipping partners to deliver your orders</li>
                <li>Payment processors to handle transactions</li>
                <li>Legal authorities when required by law</li>
            </ul>
            
            <h2>Data Security</h2>
            <p>We implement industry-standard security measures to protect your information, including SSL encryption for all transactions.</p>
            
            <h2>Cookies</h2>
            <p>We use cookies to enhance your browsing experience, remember your preferences, and analyze site traffic. You can disable cookies in your browser settings.</p>
            
            <h2>Your Rights</h2>
            <p>You have the right to access, correct, or delete your personal information. Contact us at privacy@relyve.com for assistance.</p>
            
            <h2>Children's Privacy</h2>
            <p>Our services are not intended for children under 13. We do not knowingly collect information from children.</p>
            
            <h2>Changes to This Policy</h2>
            <p>We may update this privacy policy periodically. We will notify you of any significant changes via email or website notice.</p>
            
            <div class="last-updated">
                Last Updated: January 1, 2024
            </div>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>