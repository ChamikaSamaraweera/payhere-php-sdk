# How to Build and Use PayHere PHP SDK

## Overview

This is a complete PHP SDK for the PayHere payment gateway. Since PayHere doesn't have an official PHP SDK, this implementation provides a clean, secure, and easy-to-use interface for integrating PayHere payments into your PHP applications.

## What's Included

### Core SDK Files (`src/`)
- **Payhere.php** - Main SDK facade
- **Config.php** - Configuration management
- **PaymentRequest.php** - Payment request builder
- **NotificationHandler.php** - Payment notification handler

### Examples (`examples/`)
- **checkout.php** - Payment checkout page
- **notify.php** - Server-side notification handler
- **return.php** - Customer return page
- **cancel.php** - Payment cancellation page

### Documentation
- **README.md** - Complete documentation
- **QUICKSTART.md** - Quick start guide
- **SECURITY.md** - Security best practices
- **CHANGELOG.md** - Version history

## How It Works

### 1. Payment Flow

```
Customer → Your Checkout Page → PayHere Gateway → Payment Processing
                                                          ↓
Customer ← Return Page ← PayHere ← Payment Complete
                                                          ↓
Your Server ← Notification Callback ← PayHere (Server-to-Server)
```

### 2. Hash Generation (Security)

The SDK automatically generates secure hashes for:
- **Payment Request**: Prevents tampering with payment amounts
- **Notification Verification**: Ensures callbacks are from PayHere

Hash Formula:
```
MD5(merchant_id + order_id + amount + currency + MD5(merchant_secret))
```

### 3. Notification Verification

When PayHere sends a payment notification, the SDK:
1. Receives the POST data
2. Generates a local hash using your merchant secret
3. Compares it with PayHere's signature
4. Only processes if they match

## Setup Instructions

### Step 1: Install Dependencies

```bash
cd path/to/yourProject
composer install
```

### Step 2: Get PayHere Credentials

1. Create a PayHere account at https://www.payhere.lk/
2. Login to your dashboard
3. Go to **Side Menu > Integrations**
4. Copy your **Merchant ID**
5. Click **"Add Domain/App"**
6. Enter your domain (e.g., `localhost` for testing)
7. Wait for approval (up to 24 hours)
8. Copy your **Merchant Secret**

### Step 3: Configure Examples

Edit `examples/checkout.php` and update:
```php
$payhere = new Payhere(
    'YOUR_MERCHANT_ID',      // Replace with your actual Merchant ID
    'YOUR_MERCHANT_SECRET',  // Replace with your actual Merchant Secret
    true                     // true = sandbox, false = live
);
```

Also update `examples/notify.php` with the same credentials.

### Step 4: Run Local Server

```bash
# Using PHP built-in server
php -S localhost:8000 -t examples/

# Or use XAMPP/WAMP and place files in htdocs
```

### Step 5: Test the Integration

1. Open http://localhost:8000/checkout.php
2. Click "Proceed to Payment"
3. Use PayHere test cards (sandbox mode)
4. Complete the payment
5. Check the notification logs

## Integration into Your Project

### Method 1: Using Composer (Recommended)

1. Install via composer:
   ```bash
   composer require ChamikaSamaraweera/payhere-php-sdk
   ```

### Method 2: Manual Installation

1. Copy the `src/` folder to your project
2. Include the autoloader:
   ```php
   require_once 'path/to/src/Payhere.php';
   require_once 'path/to/src/Config.php';
   require_once 'path/to/src/PaymentRequest.php';
   require_once 'path/to/src/NotificationHandler.php';
   ```

## Usage Examples

### Basic Payment

```php
use Payhere\Payhere;

$payhere = new Payhere('MERCHANT_ID', 'MERCHANT_SECRET', true);

$payment = $payhere->createPaymentRequest()
    ->setOrderId('ORDER_123')
    ->setAmount(1000.00)
    ->setCurrency('LKR')
    ->setItems('Product Name')
    ->setCustomer('John', 'Doe', 'john@example.com', '0771234567',
                  '123 Street', 'Colombo', 'Sri Lanka')
    ->setReturnUrl('https://yoursite.com/return')
    ->setNotifyUrl('https://yoursite.com/notify');

$payment->redirect();
```

### Handle Notification

```php
$notification = $payhere->handleNotification();

if ($notification->verify() && $notification->isSuccess()) {
    $orderId = $notification->getOrderId();
    $amount = $notification->getAmount();
    
    // Update database
    updateOrder($orderId, 'paid', $amount);
    
    // Send email
    sendConfirmationEmail($orderId);
}
```

## Testing

### Run the Test Script

```bash
php test.php
```

This will verify:
- SDK initialization
- Payment request creation
- Hash generation
- Form generation
- Notification handling
- Configuration access

### Test Cards (Sandbox)

Use PayHere's test cards in sandbox mode:
- Check PayHere documentation for current test card numbers
- Use any future expiry date
- Use any CVV

## Production Deployment

### Checklist

- [ ] Change sandbox mode to `false`
- [ ] Use production Merchant ID and Secret
- [ ] Ensure all URLs use HTTPS
- [ ] Test with real cards (small amounts)
- [ ] Set up proper error logging
- [ ] Implement database transactions
- [ ] Add email notifications
- [ ] Set up monitoring for failed payments
- [ ] Review security best practices

### Environment Variables

Store credentials securely:

```php
$payhere = new Payhere(
    getenv('PAYHERE_MERCHANT_ID'),
    getenv('PAYHERE_MERCHANT_SECRET'),
    getenv('PAYHERE_SANDBOX') === 'true'
);
```

## Troubleshooting

### Hash Mismatch Error

- Verify Merchant Secret is correct
- Check amount formatting (2 decimal places)
- Ensure no extra spaces in credentials

### Notification Not Received

- Check notify_url is publicly accessible
- Verify HTTPS is used
- Check server logs for incoming requests
- Ensure no firewall blocking PayHere IPs

### Payment Fails

- Verify sandbox mode matches your credentials
- Check test card details
- Review PayHere dashboard for errors
- Check amount is greater than minimum

## Advanced Features

### Custom Fields

```php
$payment->setCustomFields('user_id_123', 'plan_premium');

// In notification handler
$userId = $notification->getCustom1();
$plan = $notification->getCustom2();
```

### Multiple Currencies

```php
$payment->setCurrency('USD');  // or 'LKR', 'GBP', 'EUR', etc.
```

### Recurring Payments

For subscription-based payments, use PayHere's recurring payment API (separate implementation needed).

## API Reference

See **README.md** for complete API documentation.

## Support & Resources

- **PayHere Documentation**: https://support.payhere.lk/
- **API Reference**: https://support.payhere.lk/api-&-mobile-sdk/checkout-api
- **Test Environment**: https://sandbox.payhere.lk/
- **Support**: support@payhere.lk

## Contributing

To contribute to this SDK:
1. Fork the repository at https://github.com/ChamikaSamaraweera/payhere-php-sdk
2. Create a feature branch
3. Make your changes
4. Add tests
5. Submit a pull request

## License

MIT License - See LICENSE file for details
