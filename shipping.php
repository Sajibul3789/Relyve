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
        .shipping-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .shipping-table th, .shipping-table td {
            padding: 12px;
            border: 1px solid #eee;
            text-align: left;
        }
        .shipping-table th {
            background: #f8f9fa;
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
            .shipping-table {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="policy-container">
        <div class="policy-header">
            <h1>Shipping Information</h1>
            <p>Fast and reliable delivery across Bangladesh</p>
        </div>
        
        <div class="policy-content">
            <h2>Delivery Areas</h2>
            <p>We deliver to all 64 districts of Bangladesh through our trusted courier partners including Sundarban Courier, SA Paribahan, and RedX.</p>
            
            <h2>Shipping Rates</h2>
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
                        <td><strong>FREE Shipping</strong></td>
                    </tr>
                </tbody>
            </table>
            
            <h2>Delivery Time</h2>
            <table class="shipping-table">
                <thead>
                    <tr>
                        <th>Location</th>
                        <th>Estimated Delivery</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dhaka City (Inside City Corporation)</td>
                        <td>1-2 business days</td>
                    </tr>
                    <tr>
                        <td>Dhaka (Outside City Corporation)</td>
                        <td>2-3 business days</td>
                    </tr>
                    <tr>
                        <td>Other Major Cities (Chittagong, Sylhet, Khulna, Rajshahi, Barisal)</td>
                        <td>2-4 business days</td>
                    </tr>
                    <tr>
                        <td>Other Districts</td>
                        <td>3-5 business days</td>
                    </tr>
                </tbody>
            </table>
            
            <h2>Order Processing</h2>
            <p>Orders are processed within 24 hours of placement. You will receive a confirmation email with tracking information once your order is shipped.</p>
            
            <h2>Tracking Your Order</h2>
            <p>You can track your order by:</p>
            <ul>
                <li>Visiting our <a href="track-order.php" style="color:#f97316;">Track Order page</a></li>
                <li>Using the tracking link sent to your email</li>
                <li>Contacting our customer support</li>
            </ul>
            
            <h2>What to Do If Your Order Is Delayed</h2>
            <p>If your order hasn't arrived within the estimated timeframe:</p>
            <ul>
                <li>Check the tracking information first</li>
                <li>Contact our support team with your order number</li>
                <li>We will investigate and update you within 24 hours</li>
            </ul>
            
            <h2>International Shipping</h2>
            <p>Currently, we only ship within Bangladesh. International shipping will be available soon.</p>
            
            <div class="last-updated">
                Last Updated: January 1, 2024
            </div>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>