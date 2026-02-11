<?php
/**
 * PayHere Payment Notification Handler with Exception Handling
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Payhere\Payhere;
use Payhere\Config;
use Payhere\NotificationHandler;
use Payhere\Exceptions\NotificationVerificationException;
use Payhere\Exceptions\MissingRequiredFieldException;
use Payhere\Exceptions\PayhereException;

// Set response header
header('Content-Type: text/plain');

try {
    // Initialize configuration
    $config = new Config(
        'YOUR_MERCHANT_ID',
        'YOUR_MERCHANT_SECRET',
        true // Sandbox mode
    );
    
    // Create notification handler
    $notification = new NotificationHandler($config);
    
    // Log incoming notification
    $notification->log(__DIR__ . '/notifications.log');
    
    // Verify the notification
    if ($notification->verify()) {
        
        // Get payment details
        $orderId = $notification->getOrderId();
        $paymentId = $notification->getPaymentId();
        $amount = $notification->getAmount();
        $currency = $notification->getCurrency();
        
        // Handle different payment statuses
        if ($notification->isSuccess()) {
            // Payment successful
            // TODO: Update database
            // TODO: Mark order as paid
            // TODO: Send confirmation email
            // TODO: Activate subscription
            
            error_log("SUCCESS: Order {$orderId}, Payment {$paymentId}, Amount {$amount} {$currency}");
            
            echo "SUCCESS";
            http_response_code(200);
            
        } elseif ($notification->isPending()) {
            // Payment pending
            error_log("PENDING: Order {$orderId}");
            
            echo "PENDING";
            http_response_code(200);
            
        } elseif ($notification->isCanceled()) {
            // Payment canceled
            error_log("CANCELED: Order {$orderId}");
            
            echo "CANCELED";
            http_response_code(200);
            
        } elseif ($notification->isFailed()) {
            // Payment failed
            error_log("FAILED: Order {$orderId}");
            
            echo "FAILED";
            http_response_code(200);
            
        } elseif ($notification->isChargedBack()) {
            // Payment charged back
            error_log("CHARGEDBACK: Order {$orderId}, Payment {$paymentId}");
            
            echo "CHARGEDBACK";
            http_response_code(200);
            
        } else {
            // Unknown status
            error_log("UNKNOWN STATUS: Order {$orderId}, Status: {$notification->getStatusCode()}");
            
            echo "UNKNOWN";
            http_response_code(200);
        }
    }
    
} catch (NotificationVerificationException $e) {
    // Hash verification failed - possible tampering
    http_response_code(403);
    echo "VERIFICATION_FAILED";
    
    error_log("VERIFICATION FAILED: " . $e->getMessage());
    error_log("Context: " . json_encode($e->getContext()));
    
} catch (MissingRequiredFieldException $e) {
    // Missing required fields in notification
    http_response_code(400);
    echo "MISSING_FIELDS";
    
    error_log("MISSING FIELD: " . $e->getMessage());
    error_log("Field: " . json_encode($e->getContext()));
    
} catch (PayhereException $e) {
    // Other PayHere exception
    http_response_code(500);
    echo "ERROR";
    
    error_log("PayHere Exception: " . $e->getMessage());
    if ($e->getContext()) {
        error_log("Context: " . json_encode($e->getContext()));
    }
    
} catch (Exception $e) {
    // Unexpected error
    http_response_code(500);
    echo "INTERNAL_ERROR";
    
    error_log("Unexpected error: " . $e->getMessage());
    error_log("Trace: " . $e->getTraceAsString());
}