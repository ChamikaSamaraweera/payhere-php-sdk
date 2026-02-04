# PayHere PHP SDK - Quick Start Guide

## Installation

1. **Install via Composer:**
   ```bash
   composer require ChamikaSamaraweera/payhere-php-sdk
   ```

2. **Or clone this repository:**
   ```bash
   git clone https://github.com/ChamikaSamaraweera/payhere-php-sdk.git
   cd payhere-php-sdk
   composer install
   ```

## Setup

1. **Get your credentials from PayHere:**
   - Login to your PayHere account
   - Go to `Side Menu > Integrations`
   - Copy your **Merchant ID**
   - Click "Add Domain/App" and add your domain
   - Wait for approval (up to 24 hours)
   - Copy your **Merchant Secret**

2. **Configure your application:**
   ```php
   use Payhere\Payhere;
   
   $payhere = new Payhere(
       'YOUR_MERCHANT_ID',
       'YOUR_MERCHANT_SECRET',
       true  // true for sandbox, false for live
   );
   ```

## Basic Usage

### 1. Create a Payment

```php
$payment = $payhere->createPaymentRequest()
    ->setOrderId('ORDER_123')
    ->setAmount(1000.00)
    ->setCurrency('LKR')
    ->setItems('Product Name')
    ->setCustomer(
        'John', 'Doe', 
        'john@example.com', 
        '0771234567',
        '123 Street', 'Colombo', 'Sri Lanka'
    )
    ->setReturnUrl('https://yoursite.com/return')
    ->setCancelUrl('https://yoursite.com/cancel')
    ->setNotifyUrl('https://yoursite.com/notify');

// Redirect to PayHere
$payment->redirect();
```

### 2. Handle Payment Notification

```php
$notification = $payhere->handleNotification();

if ($notification->verify() && $notification->isSuccess()) {
    $orderId = $notification->getOrderId();
    $amount = $notification->getAmount();
    
    // Update your database
    // Send confirmation email
}
```

## Testing

Use sandbox mode for testing:
```php
$payhere = new Payhere('MERCHANT_ID', 'MERCHANT_SECRET', true);
```

Test with PayHere's test cards (check their documentation).

## Examples

Check the `examples/` folder for complete working examples:
- `checkout.php` - Payment form
- `notify.php` - Notification handler
- `return.php` - Success page
- `cancel.php` - Cancel page

## Support

- [Full Documentation](../README.md)
- [PayHere API Docs](https://support.payhere.lk/)
- [GitHub Issues](https://github.com/ChamikaSamaraweera/payhere-php-sdk/issues)
