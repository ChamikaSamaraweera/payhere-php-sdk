<?php
/**
 * PayHere Payment Checkout Example
 * 
 * This example shows how to create a payment request and redirect to PayHere
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Payhere\Payhere;

// Initialize PayHere SDK
$payhere = new Payhere(
    'YOUR_MERCHANT_ID',        // Replace with your Merchant ID
    'YOUR_MERCHANT_SECRET',    // Replace with your Merchant Secret
    true                       // true = sandbox, false = live
);

// Create a payment request
$payment = $payhere->createPaymentRequest()
    ->setOrderId('ORDER_' . time())
    ->setAmount(1000.00)
    ->setCurrency('LKR')
    ->setItems('Premium Subscription', 1)
    ->setCustomer(
        'John',
        'Doe',
        'john.doe@example.com',
        '0771234567',
        '123 Main Street',
        'Colombo',
        'Sri Lanka'
    )
    ->setReturnUrl('http://localhost/payhere-sdk/examples/return.php')
    ->setCancelUrl('http://localhost/payhere-sdk/examples/cancel.php')
    ->setNotifyUrl('http://localhost/payhere-sdk/examples/notify.php')
    ->setCustomFields('user_id_123', 'subscription_plan_premium');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayHere Payment Checkout</title>
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
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .order-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
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
        
        .amount {
            font-size: 24px;
            color: #667eea;
        }
        
        form button {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        form button:active {
            transform: translateY(0);
        }
        
        .secure-badge {
            text-align: center;
            margin-top: 20px;
            color: #999;
            font-size: 12px;
        }
        
        .secure-badge::before {
            content: "🔒 ";
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Complete Your Payment</h1>
        <p class="subtitle">You will be redirected to PayHere secure payment gateway</p>
        
        <div class="order-details">
            <div class="detail-row">
                <span class="detail-label">Item</span>
                <span class="detail-value">Premium Subscription</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Order ID</span>
                <span class="detail-value"><?php echo htmlspecialchars($payment->getData()['order_id']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Amount</span>
                <span class="detail-value amount">LKR 1,000.00</span>
            </div>
        </div>
        
        <?php echo $payment->generateForm('Proceed to Payment', ['id' => 'payment-form']); ?>
        
        <div class="secure-badge">
            Secured by PayHere Payment Gateway
        </div>
    </div>
</body>
</html>
