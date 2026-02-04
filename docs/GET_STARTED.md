# 🚀 PayHere PHP SDK - Getting Started

## What is This?

This is a **complete PHP SDK for PayHere Payment Gateway** - the first unofficial but comprehensive implementation since PayHere doesn't provide an official PHP SDK.

## ✨ Features

- ✅ **Simple API** - Easy to use, fluent interface
- ✅ **Secure** - Hash-based verification and validation
- ✅ **Complete** - Payment requests, notifications, and callbacks
- ✅ **Well-Documented** - Extensive documentation and examples
- ✅ **Production-Ready** - Follows best practices
- ✅ **PSR-4 Compliant** - Modern PHP standards

## 📦 What's Included

### Core SDK (`src/`)
- `Payhere.php` - Main SDK class
- `Config.php` - Configuration management
- `PaymentRequest.php` - Payment builder
- `NotificationHandler.php` - Callback handler

### Examples (`examples/`)
- `checkout.php` - Payment checkout page
- `notify.php` - Server notification handler
- `return.php` - Customer return page
- `cancel.php` - Payment cancel page

### Documentation
- `README.md` - Complete API reference
- `QUICKSTART.md` - 5-minute setup
- `IMPLEMENTATION_GUIDE.md` - Master guide
- `HOW_TO_USE.md` - Detailed usage
- `SECURITY.md` - Security practices
- `STRUCTURE.md` - Project architecture

## 🎯 Quick Start

### 1. Clone this repository
```bash
git clone https://github.com/ChamikaSamaraweera/payhere-php-sdk.git
cd payhere-php-sdk
```

### 2. Install via Composer:
   ```bash
   composer require ChamikaSamaraweera/payhere-php-sdk
   ```

### 3. Get PayHere Credentials
- Login to https://www.payhere.lk/
- Go to Side Menu > Integrations
- Copy Merchant ID and Secret

### 4. Update Examples
Edit `examples/checkout.php`:
```php
$payhere = new Payhere(
    'YOUR_MERCHANT_ID',
    'YOUR_MERCHANT_SECRET',
    true  // sandbox mode
);
```

### 5. Run Examples
```bash
php -S localhost:8000 -t examples
```

Visit: http://localhost:8000/checkout.php

## 💻 Basic Usage

```php
use Payhere\Payhere;

// Initialize
$payhere = new Payhere('MERCHANT_ID', 'MERCHANT_SECRET', true);

// Create payment
$payment = $payhere->createPaymentRequest()
    ->setOrderId('ORDER_123')
    ->setAmount(1000.00)
    ->setCurrency('LKR')
    ->setItems('Product Name')
    ->setCustomer('John', 'Doe', 'john@example.com', '0771234567',
                  '123 Street', 'Colombo', 'Sri Lanka')
    ->setReturnUrl('https://yoursite.com/return')
    ->setNotifyUrl('https://yoursite.com/notify');

// Redirect to PayHere
$payment->redirect();
```

## 🔐 Handle Notifications

```php
$notification = $payhere->handleNotification();

if ($notification->verify() && $notification->isSuccess()) {
    $orderId = $notification->getOrderId();
    $amount = $notification->getAmount();
    
    // Update database, send emails, etc.
}
```

## 📚 Documentation

| Document | Purpose |
|----------|---------|
| **QUICKSTART.md** | Get started in 5 minutes |
| **IMPLEMENTATION_GUIDE.md** | Complete implementation guide |
| **HOW_TO_USE.md** | Detailed usage instructions |
| **README.md** | Full API reference |
| **SECURITY.md** | Security best practices |
| **STRUCTURE.md** | Project architecture |

## 🧪 Testing

```bash
# Run automated tests
php test.php

# Start dev server
php -S localhost:8000 -t examples

# Test in browser
http://localhost:8000/checkout.php
```

## 🌐 Production Deployment

1. Change sandbox to `false`
2. Use production credentials
3. Ensure HTTPS on all URLs
4. Test with small amounts
5. Monitor notifications
6. Set up error logging

## 🎨 Payment Flow

```
Customer → Checkout → PayHere Gateway → Payment Processing
                                              ↓
Customer ← Return URL ← PayHere ← Payment Complete
                                              ↓
Your Server ← Notify URL ← PayHere (Server Callback)
```

## 🛠️ Requirements

- PHP 7.4 or higher
- Composer
- PayHere merchant account

## 📖 API Reference

### PaymentRequest Methods
- `setOrderId(string)` - Set order ID
- `setAmount(float)` - Set amount
- `setCurrency(string)` - Set currency
- `setItems(string, int)` - Set item details
- `setCustomer(...)` - Set customer info
- `setReturnUrl(string)` - Set return URL
- `setNotifyUrl(string)` - Set notify URL
- `redirect()` - Redirect to PayHere
- `generateForm(string)` - Generate HTML form

### NotificationHandler Methods
- `verify()` - Verify notification
- `isSuccess()` - Check if successful
- `getOrderId()` - Get order ID
- `getAmount()` - Get amount
- `getPaymentId()` - Get payment ID
- `getStatusCode()` - Get status code
- `getStatusText()` - Get status text

## 🔗 Resources

- **PayHere**: https://www.payhere.lk/
- **API Docs**: https://support.payhere.lk/api-&-mobile-sdk/checkout-api
- **Sandbox**: https://sandbox.payhere.lk/

## 📄 License

MIT License - Free for commercial and personal use

## 🤝 Contributing

Contributions welcome! Feel free to submit issues and pull requests.

---

**Ready to accept payments? Start with QUICKSTART.md! 🚀**
