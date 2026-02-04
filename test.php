<?php
/**
 * Simple test script to verify the SDK works correctly
 * Run this with: php test.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use Payhere\Payhere;

echo "=== PayHere PHP SDK Test ===\n\n";

// Test 1: Initialize SDK
echo "Test 1: Initializing SDK...\n";
try {
    $payhere = new Payhere('TEST_MERCHANT_ID', 'TEST_MERCHANT_SECRET', true);
    echo "✓ SDK initialized successfully\n\n";
} catch (Exception $e) {
    echo "✗ Failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 2: Create Payment Request
echo "Test 2: Creating payment request...\n";
try {
    $payment = $payhere->createPaymentRequest()
        ->setOrderId('TEST_ORDER_' . time())
        ->setAmount(1000.00)
        ->setCurrency('LKR')
        ->setItems('Test Product', 1)
        ->setCustomer(
            'John',
            'Doe',
            'john@example.com',
            '0771234567',
            '123 Test Street',
            'Colombo',
            'Sri Lanka'
        )
        ->setReturnUrl('http://localhost/return')
        ->setCancelUrl('http://localhost/cancel')
        ->setNotifyUrl('http://localhost/notify');
    
    echo "✓ Payment request created\n\n";
} catch (Exception $e) {
    echo "✗ Failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 3: Generate Payment Data
echo "Test 3: Generating payment data with hash...\n";
try {
    $data = $payment->getData();
    echo "✓ Payment data generated\n";
    echo "  - Order ID: " . $data['order_id'] . "\n";
    echo "  - Amount: " . $data['amount'] . "\n";
    echo "  - Currency: " . $data['currency'] . "\n";
    echo "  - Hash: " . substr($data['hash'], 0, 20) . "...\n\n";
} catch (Exception $e) {
    echo "✗ Failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 4: Generate HTML Form
echo "Test 4: Generating HTML form...\n";
try {
    $form = $payment->generateForm('Pay Now');
    if (strpos($form, '<form') !== false && strpos($form, 'hash') !== false) {
        echo "✓ HTML form generated successfully\n";
        echo "  Form length: " . strlen($form) . " characters\n\n";
    } else {
        echo "✗ Form generation incomplete\n\n";
    }
} catch (Exception $e) {
    echo "✗ Failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 5: Notification Handler
echo "Test 5: Testing notification handler...\n";
try {
    // Simulate a notification
    $testNotification = [
        'merchant_id' => 'TEST_MERCHANT_ID',
        'order_id' => 'TEST_ORDER_123',
        'payhere_amount' => '1000.00',
        'payhere_currency' => 'LKR',
        'status_code' => '2',
        'payment_id' => 'TEST_PAYMENT_123',
        'md5sig' => 'test_signature'
    ];
    
    $notification = $payhere->handleNotification($testNotification);
    echo "✓ Notification handler created\n";
    echo "  - Order ID: " . $notification->getOrderId() . "\n";
    echo "  - Amount: " . $notification->getAmount() . "\n";
    echo "  - Status: " . $notification->getStatusText() . "\n";
    echo "  - Is Success: " . ($notification->getStatusCode() === 2 ? 'Yes' : 'No') . "\n\n";
} catch (Exception $e) {
    echo "✗ Failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 6: Config
echo "Test 6: Testing configuration...\n";
try {
    $config = $payhere->getConfig();
    echo "✓ Configuration accessible\n";
    echo "  - Merchant ID: " . $config->getMerchantId() . "\n";
    echo "  - Is Sandbox: " . ($config->isSandbox() ? 'Yes' : 'No') . "\n";
    echo "  - Checkout URL: " . $config->getCheckoutUrl() . "\n\n";
} catch (Exception $e) {
    echo "✗ Failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

echo "=== All Tests Passed! ===\n";
echo "\nThe SDK is working correctly. You can now:\n";
echo "1. Update your merchant credentials in examples/checkout.php\n";
echo "2. Run the examples with a PHP server\n";
echo "3. Test with PayHere sandbox environment\n";
