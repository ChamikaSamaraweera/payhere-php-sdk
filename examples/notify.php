<?php
/**
 * PayHere Payment Notification Handler
 * 
 * This endpoint receives server-to-server notifications from PayHere
 * about payment status updates
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Payhere\Payhere;

// Initialize PayHere SDK
$payhere = new Payhere(
    'YOUR_MERCHANT_ID',        // Replace with your Merchant ID
    'YOUR_MERCHANT_SECRET',    // Replace with your Merchant Secret
    true                       // true = sandbox, false = live
);

// Handle the notification
$notification = $payhere->handleNotification();

// Log the notification (for debugging)
file_put_contents(
    __DIR__ . '/notifications.log',
    date('Y-m-d H:i:s') . " - " . json_encode($_POST) . "\n",
    FILE_APPEND
);

// Verify the notification hash
if (!$notification->verify()) {
    http_response_code(400);
    die('Invalid notification signature');
}

// Get payment details
$orderId = $notification->getOrderId();
$paymentId = $notification->getPaymentId();
$amount = $notification->getAmount();
$currency = $notification->getCurrency();
$statusCode = $notification->getStatusCode();
$statusText = $notification->getStatusText();
$method = $notification->getMethod();
$cardHolderName = $notification->getCardHolderName();
$custom1 = $notification->getCustom1();
$custom2 = $notification->getCustom2();

// Handle different payment statuses
switch ($statusCode) {
    case $notification::STATUS_SUCCESS:
        // Payment successful
        // TODO: Update your database
        // TODO: Mark order as paid
        // TODO: Send confirmation email
        // TODO: Activate subscription, etc.
        
        // Example database update (pseudo-code)
        // $db->query("UPDATE orders SET status = 'paid', payment_id = ? WHERE order_id = ?", 
        //            [$paymentId, $orderId]);
        
        // Log success
        file_put_contents(
            __DIR__ . '/payments.log',
            date('Y-m-d H:i:s') . " - SUCCESS - Order: $orderId, Payment: $paymentId, Amount: $amount $currency\n",
            FILE_APPEND
        );
        
        echo "Payment processed successfully";
        break;
        
    case $notification::STATUS_PENDING:
        // Payment pending
        // TODO: Mark order as pending
        
        file_put_contents(
            __DIR__ . '/payments.log',
            date('Y-m-d H:i:s') . " - PENDING - Order: $orderId\n",
            FILE_APPEND
        );
        
        echo "Payment pending";
        break;
        
    case $notification::STATUS_CANCELED:
        // Payment canceled
        // TODO: Mark order as canceled
        
        file_put_contents(
            __DIR__ . '/payments.log',
            date('Y-m-d H:i:s') . " - CANCELED - Order: $orderId\n",
            FILE_APPEND
        );
        
        echo "Payment canceled";
        break;
        
    case $notification::STATUS_FAILED:
        // Payment failed
        // TODO: Mark order as failed
        
        file_put_contents(
            __DIR__ . '/payments.log',
            date('Y-m-d H:i:s') . " - FAILED - Order: $orderId\n",
            FILE_APPEND
        );
        
        echo "Payment failed";
        break;
        
    case $notification::STATUS_CHARGEDBACK:
        // Payment chargedback
        // TODO: Handle chargeback
        
        file_put_contents(
            __DIR__ . '/payments.log',
            date('Y-m-d H:i:s') . " - CHARGEDBACK - Order: $orderId, Payment: $paymentId\n",
            FILE_APPEND
        );
        
        echo "Payment chargedback";
        break;
        
    default:
        echo "Unknown payment status";
}

// Always return 200 OK to PayHere
http_response_code(200);
