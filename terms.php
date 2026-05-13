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
        .policy-header p {
            color: #666;
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
            line-height: 1.5;
        }
        .last-updated {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 0.85rem;
            color: #999;
            text-align: center;
        }
        @media (max-width: 768px) {
            .policy-content {
                padding: 25px;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="policy-container">
        <div class="policy-header">
            <h1>Terms & Conditions</h1>
            <p>Please read these terms carefully before using our services</p>
        </div>
        
        <div class="policy-content">
            <h2>1. Acceptance of Terms</h2>
            <p>By accessing and using Relyve.com, you agree to be bound by these Terms & Conditions. If you do not agree, please do not use our website.</p>
            
            <h2>2. Account Registration</h2>
            <p>To place orders on Relyve, you must create an account. You are responsible for maintaining the confidentiality of your account information and for all activities that occur under your account.</p>
            
            <h2>3. Product Information</h2>
            <p>We strive to display accurate product information, including prices, descriptions, and images. However, we do not guarantee that all information is error-free. Colors may vary depending on your screen settings.</p>
            
            <h2>4. Pricing and Payment</h2>
            <p>All prices are listed in Bangladeshi Taka (৳). We reserve the right to change prices at any time. Payment can be made via Cash on Delivery, Credit/Debit Cards, or bKash.</p>
            
            <h2>5. Order Acceptance</h2>
            <p>We reserve the right to refuse or cancel any order for any reason, including product availability, pricing errors, or suspected fraud.</p>
            
            <h2>6. Shipping and Delivery</h2>
            <p>Delivery times are estimates and not guaranteed. We are not responsible for delays caused by courier services or customs. Shipping fees are non-refundable.</p>
            
            <h2>7. Returns and Refunds</h2>
            <p>We offer a 7-day return policy on eligible products. Items must be unused, in original packaging, with all tags attached. Refunds are processed within 5-7 business days.</p>
            
            <h2>8. Warranty</h2>
            <p>Product warranties are provided by the manufacturer. Relyve acts as a facilitator and is not responsible for warranty claims beyond facilitating the process.</p>
            
            <h2>9. Limitation of Liability</h2>
            <p>Relyve shall not be liable for any indirect, incidental, or consequential damages arising from the use of our products or services.</p>
            
            <h2>10. Changes to Terms</h2>
            <p>We may update these Terms & Conditions at any time. Continued use of our website constitutes acceptance of the updated terms.</p>
            
            <div class="last-updated">
                Last Updated: January 1, 2024
            </div>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>