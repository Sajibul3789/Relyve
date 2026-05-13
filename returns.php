<?php
session_start();
include_once 'includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Policy - Relyve</title>
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
        .return-steps {
            background: #fef3c7;
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
        }
        .return-steps h3 {
            color: #d97706;
            margin-bottom: 10px;
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
            <h1>Return & Refund Policy</h1>
            <p>Our commitment to your satisfaction</p>
        </div>
        
        <div class="policy-content">
            <h2>7-Day Return Policy</h2>
            <p>We offer a 7-day return policy on most products. Returns are accepted within 7 days of delivery for eligible items.</p>
            
            <h2>Eligibility Criteria</h2>
            <p>To be eligible for a return:</p>
            <ul>
                <li>Item must be unused and in original condition</li>
                <li>All original packaging, tags, and accessories must be included</li>
                <li>Product must not be damaged by improper use</li>
                <li>Items must be returned within 7 days of delivery</li>
            </ul>
            
            <h2>Non-Returnable Items</h2>
            <p>The following items cannot be returned:</p>
            <ul>
                <li>Personal care products (earphones, headphones, etc.)</li>
                <li>Perishable goods</li>
                <li>Customized or personalized items</li>
                <li>Software, games, and digital products</li>
                <li>Items marked as "Final Sale"</li>
            </ul>
            
            <div class="return-steps">
                <h3>📦 How to Return an Item</h3>
                <ol style="margin-left: 20px; color: #666;">
                    <li>Contact our customer support within 7 days of delivery</li>
                    <li>Provide your order number and reason for return</li>
                    <li>Our team will guide you through the return process</li>
                    <li>Pack the item securely in original packaging</li>
                    <li>Wait for pickup or drop off at designated location</li>
                </ol>
            </div>
            
            <h2>Refund Process</h2>
            <p>Once we receive and inspect your return:</p>
            <ul>
                <li>Refunds are processed within 5-7 business days</li>
                <li>Refunds are issued to the original payment method</li>
                <li>Cash on Delivery orders are refunded via bank transfer or bKash</li>
                <li>Shipping fees are non-refundable</li>
            </ul>
            
            <h2>Damaged or Defective Items</h2>
            <p>If you receive a damaged or defective item, please contact us within 24 hours of delivery with photos of the damage. We will arrange a replacement or full refund at no extra cost.</p>
            
            <h2>Exchange Policy</h2>
            <p>We offer exchanges for size or color variants subject to availability. Contact our support team to initiate an exchange.</p>
            
            <h2>Cancellation Policy</h2>
            <p>Orders can be cancelled within 2 hours of placement without any charges. Once shipped, cancellations are subject to return policy.</p>
            
            <div class="last-updated">
                Last Updated: January 1, 2024
            </div>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>