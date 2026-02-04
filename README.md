# PayHere PHP SDK

[![Latest Version](https://img.shields.io/github/v/release/ChamikaSamaraweera/payhere-php-sdk)](https://github.com/ChamikaSamaraweera/payhere-php-sdk/releases)
[![License](https://img.shields.io/github/license/ChamikaSamaraweera/payhere-php-sdk)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.4-blue)](https://www.php.net/)

A comprehensive PHP SDK for integrating PayHere Payment Gateway into your PHP applications.

## Features

- ✅ Simple and intuitive API
- ✅ Secure hash generation and verification
- ✅ Support for both Sandbox and Live environments
- ✅ Payment notification handling
- ✅ PSR-4 autoloading
- ✅ Comprehensive error handling
- ✅ Well-documented code

## Requirements

- PHP 7.4 or higher
- Composer

## Installation

Install via Composer:

```bash
composer require ChamikaSamaraweera/payhere-php-sdk
```

Or manually add to your `composer.json`:

```json
{
    "require": {
        "ChamikaSamaraweera/payhere-php-sdk": "^1.0"
    }
}
```

## Quick Start

### 1. Initialize the SDK

```php
<?php
require_once 'vendor/autoload.php';

use Payhere\Payhere;

// Initialize with your credentials
$payhere = new Payhere(
    'YOUR_MERCHANT_ID',
    'YOUR_MERCHANT_SECRET',
    true // true for sandbox, false for live
);
```

### 2. Create a Payment Request

```php
// Create a payment request
$payment = $payhere->createPaymentRequest()
    ->setOrderId('ORDER_' . time())
    ->setAmount(1000.00)
    ->setCurrency('LKR')
    ->setItems('Product Name', 1)
    ->setCustomer(
        'John',
        'Doe',
        'john@example.com',
        '0771234567',
        '123 Main Street',
        'Colombo',
        'Sri Lanka'
    )
    ->setReturnUrl('https://yoursite.com/payment/return')
    ->setCancelUrl('https://yoursite.com/payment/cancel')
    ->setNotifyUrl('https://yoursite.com/payment/notify');

// Option 1: Generate HTML form
echo $payment->generateForm('Pay Now');

// Option 2: Auto-redirect to PayHere
$payment->redirect();

// Option 3: Get data array for custom implementation
$paymentData = $payment->getData();
```

### 3. Handle Payment Notifications

Create a notification handler endpoint (e.g., `notify.php`):

```php
<?php
require_once 'vendor/autoload.php';

use Payhere\Payhere;

$payhere = new Payhere(
    'YOUR_MERCHANT_ID',
    'YOUR_MERCHANT_SECRET',
    true
);

// Handle the notification
$notification = $payhere->handleNotification();

// Verify the notification
if ($notification->verify()) {
    
    // Check if payment was successful
    if ($notification->isSuccess()) {
        $orderId = $notification->getOrderId();
        $paymentId = $notification->getPaymentId();
        $amount = $notification->getAmount();
        $currency = $notification->getCurrency();
        
        // Update your database
        // Mark order as paid
        // Send confirmation email, etc.
        
        echo "Payment successful!";
    } else {
        $status = $notification->getStatusText();
        echo "Payment status: " . $status;
    }
    
} else {
    // Invalid notification
    http_response_code(400);
    echo "Invalid notification";
}
```

## Configuration

### Merchant Credentials

You need two credentials from your PayHere account:

1. **Merchant ID**: Found in `Side Menu > Integrations`
2. **Merchant Secret**: Generate by adding your domain/app in `Side Menu > Integrations`

### Sandbox vs Live

```php
// Sandbox (for testing)
$payhere = new Payhere('MERCHANT_ID', 'MERCHANT_SECRET', true);

// Live (for production)
$payhere = new Payhere('MERCHANT_ID', 'MERCHANT_SECRET', false);
```

## API Reference

### PaymentRequest Methods

| Method | Description |
|--------|-------------|
| `setOrderId(string $orderId)` | Set unique order ID |
| `setAmount(float $amount)` | Set payment amount |
| `setCurrency(string $currency)` | Set currency (default: LKR) |
| `setItems(string $name, int $number)` | Set item details |
| `setCustomer(...)` | Set customer information |
| `setReturnUrl(string $url)` | Set return URL after payment |
| `setCancelUrl(string $url)` | Set cancel URL |
| `setNotifyUrl(string $url)` | Set notification callback URL |
| `setCustomFields(string $custom1, ?string $custom2)` | Set custom fields |
| `getData()` | Get payment data array with hash |
| `generateForm(string $buttonText, array $attrs)` | Generate HTML form |
| `redirect()` | Auto-redirect to PayHere |

### NotificationHandler Methods

| Method | Description |
|--------|-------------|
| `verify()` | Verify notification hash |
| `isSuccess()` | Check if payment was successful |
| `getStatusCode()` | Get status code (2=success, 0=pending, -1=canceled, -2=failed) |
| `getStatusText()` | Get status as text |
| `getOrderId()` | Get order ID |
| `getPaymentId()` | Get PayHere payment ID |
| `getAmount()` | Get payment amount |
| `getCurrency()` | Get currency |
| `getCustom1()` | Get custom field 1 |
| `getCustom2()` | Get custom field 2 |
| `getCardHolderName()` | Get card holder name |
| `getCardNo()` | Get masked card number |
| `getMethod()` | Get payment method |
| `getData()` | Get all notification data |
| `get(string $key, $default)` | Get specific field |

### Payment Status Codes

| Code | Constant | Description |
|------|----------|-------------|
| 2 | `STATUS_SUCCESS` | Payment successful |
| 0 | `STATUS_PENDING` | Payment pending |
| -1 | `STATUS_CANCELED` | Payment canceled |
| -2 | `STATUS_FAILED` | Payment failed |
| -3 | `STATUS_CHARGEDBACK` | Payment chargedback |

## Complete Example

See the `examples/` directory for complete working examples:

- `examples/checkout.php` - Payment checkout page
- `examples/notify.php` - Payment notification handler
- `examples/return.php` - Return page handler

## Security Best Practices

1. **Never expose your Merchant Secret** in client-side code
2. **Always verify notifications** using the `verify()` method
3. **Use HTTPS** for all callback URLs
4. **Validate amounts** in your notification handler
5. **Store payment records** before redirecting to PayHere
6. **Use unique order IDs** for each transaction

## Testing

Use PayHere's sandbox environment for testing:

```php
$payhere = new Payhere('MERCHANT_ID', 'MERCHANT_SECRET', true);
```

Test card details are available in PayHere's documentation.

## Documentation

- 📖 [Quick Start Guide](docs/QUICKSTART.md)
- 📘 [Detailed Usage Guide](docs/HOW_TO_USE.md)
- 🔐 [Security Best Practices](docs/SECURITY.md)
- 📋 [Project Structure](docs/STRUCTURE.md)
- 📝 [Changelog](docs/CHANGELOG.md)

## Support

- [PayHere Documentation](https://support.payhere.lk/)
- [API Documentation](https://support.payhere.lk/api-&-mobile-sdk/checkout-api)
- [GitHub Issues](https://github.com/ChamikaSamaraweera/payhere-php-sdk/issues)

## Author

**Chamika Samaraweera**
- Email: chamika@teaminfinity.lk
- GitHub: [@ChamikaSamaraweera](https://github.com/ChamikaSamaraweera)

## License

MIT License - see the [LICENSE](LICENSE) file for details

## Contributing

Contributions are welcome! Please read our [Contributing Guide](CONTRIBUTING.md) for details on how to submit pull requests.

## Acknowledgments

- PayHere for providing the payment gateway service
- All contributors who help improve this SDK

