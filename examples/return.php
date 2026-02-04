<?php
/**
 * PayHere Payment Return Page
 * 
 * This page is shown to the customer after they complete the payment
 * on PayHere's gateway (successful or not)
 */

// Get return parameters
$orderId = $_GET['order_id'] ?? 'Unknown';
$paymentId = $_GET['payment_id'] ?? null;
$statusMessage = $_GET['status_message'] ?? 'Processing';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        
        .icon {
            font-size: 64px;
            margin-bottom: 20px;
            animation: scaleIn 0.5s ease-out;
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        
        .message {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: left;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .detail-label {
            color: #666;
            font-weight: 500;
        }
        
        .detail-value {
            color: #333;
            font-weight: 600;
        }
        
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 10px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        .note {
            margin-top: 20px;
            color: #999;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">⏳</div>
        <h1>Payment Processing</h1>
        <p class="message">
            Your payment is being processed. We will send you a confirmation email shortly.
        </p>
        
        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Order ID</span>
                <span class="detail-value"><?php echo htmlspecialchars($orderId); ?></span>
            </div>
            <?php if ($paymentId): ?>
            <div class="detail-row">
                <span class="detail-label">Payment ID</span>
                <span class="detail-value"><?php echo htmlspecialchars($paymentId); ?></span>
            </div>
            <?php endif; ?>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value"><?php echo htmlspecialchars($statusMessage); ?></span>
            </div>
        </div>
        
        <a href="/" class="button">Return to Home</a>
        
        <p class="note">
            Please check your email for payment confirmation and receipt.
        </p>
    </div>
</body>
</html>
