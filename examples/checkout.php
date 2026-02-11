<?php
/**
 * PayHere Payment Checkout with Exception Handling
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Payhere\Payhere;
use Payhere\Config;
use Payhere\Exceptions\InvalidAmountException;
use Payhere\Exceptions\InvalidCurrencyException;
use Payhere\Exceptions\InvalidPaymentDataException;
use Payhere\Exceptions\MissingRequiredFieldException;
use Payhere\Exceptions\PayhereException;

try {
    // Initialize configuration
    $config = new Config(
        'YOUR_MERCHANT_ID',
        'YOUR_MERCHANT_SECRET',
        true // Sandbox mode
    );
    
    // Initialize PayHere
    $payhere = new Payhere(
        $config->getMerchantId(),
        $config->getMerchantSecret(),
        $config->isSandbox()
    );
    
    // Create payment request
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
        ->setReturnUrl('http://localhost/payhere-sdk/return.php')
        ->setCancelUrl('http://localhost/payhere-sdk/cancel.php')
        ->setNotifyUrl('http://localhost/payhere-sdk/notify.php')
        ->setCustomFields('user_id_123', 'subscription_plan_premium');
    
    // Get payment summary
    $summary = $payment->getSummary();
    
} catch (InvalidAmountException $e) {
    die("Amount Error: " . $e->getMessage());
    
} catch (InvalidCurrencyException $e) {
    die("Currency Error: " . $e->getMessage());
    
} catch (InvalidPaymentDataException $e) {
    die("Payment Data Error: " . $e->getMessage());
    
} catch (MissingRequiredFieldException $e) {
    die("Missing Field: " . $e->getMessage());
    
} catch (PayhereException $e) {
    die("PayHere Error: " . $e->getMessage());
    
} catch (Exception $e) {
    die("Unexpected Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayHere Checkout</title>
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
                <span class="detail-value"><?php echo htmlspecialchars($summary['items']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Order ID</span>
                <span class="detail-value"><?php echo htmlspecialchars($summary['order_id']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Amount</span>
                <span class="detail-value amount"><?php echo htmlspecialchars($summary['currency'] . ' ' . number_format($summary['amount'], 2)); ?></span>
            </div>
        </div>
        
        <?php echo $payment->generateForm('Proceed to Payment', ['id' => 'payment-form']); ?>
        
        <div class="secure-badge">
            Secured by PayHere Payment Gateway
        </div>
    </div>
</body>
</html>