# PayHere PHP SDK - Project Structure

```
PayherePHPSDK/
│
├── src/                          # Core SDK source files
│   ├── Payhere.php              # Main SDK facade class
│   ├── Config.php               # Configuration management
│   ├── PaymentRequest.php       # Payment request builder
│   └── NotificationHandler.php  # Payment notification handler
│
├── examples/                     # Working examples
│   ├── checkout.php             # Payment checkout page
│   ├── notify.php               # Server notification handler
│   ├── return.php               # Customer return page
│   └── cancel.php               # Payment cancel page
│
├── vendor/                       # Composer dependencies (auto-generated)
│
├── composer.json                 # Composer configuration
├── composer.lock                 # Locked dependencies (auto-generated)
│
├── test.php                      # SDK test script
│
├── README.md                     # Main documentation
├── QUICKSTART.md                # Quick start guide
├── HOW_TO_USE.md                # Detailed usage guide
├── SECURITY.md                  # Security best practices
├── CHANGELOG.md                 # Version history
├── LICENSE                      # MIT License
│
└── .gitignore                   # Git ignore rules
```

## File Descriptions

### Core SDK (`src/`)

**Payhere.php**
- Main entry point for the SDK
- Factory for creating payment requests and notification handlers
- Simple facade pattern for easy usage

**Config.php**
- Stores merchant credentials
- Manages sandbox/live environment
- Provides checkout URLs

**PaymentRequest.php**
- Fluent interface for building payment requests
- Automatic hash generation
- Form generation and auto-redirect functionality
- Validates required fields

**NotificationHandler.php**
- Processes payment notifications from PayHere
- Verifies notification signatures
- Extracts payment details
- Provides status checking methods

### Examples (`examples/`)

**checkout.php**
- Complete payment checkout page
- Shows how to create payment requests
- Modern UI with payment details
- Form submission to PayHere

**notify.php**
- Server-side notification endpoint
- Demonstrates hash verification
- Shows how to handle different payment statuses
- Includes logging for debugging

**return.php**
- Customer return page after payment
- Displays payment status
- Modern UI with order details

**cancel.php**
- Shown when customer cancels payment
- Provides options to retry or go home

### Documentation

**README.md**
- Complete SDK documentation
- Installation instructions
- API reference
- Usage examples

**QUICKSTART.md**
- Get started in 5 minutes
- Basic setup and usage
- Minimal example code

**HOW_TO_USE.md**
- Detailed implementation guide
- Step-by-step setup
- Troubleshooting
- Production deployment checklist

**SECURITY.md**
- Security best practices
- Vulnerability reporting
- Known security considerations

**CHANGELOG.md**
- Version history
- Feature additions
- Bug fixes

### Configuration Files

**composer.json**
- Package metadata
- Dependencies
- PSR-4 autoloading configuration

**.gitignore**
- Excludes vendor/ directory
- Ignores log files
- Excludes IDE files

**LICENSE**
- MIT License text
- Usage permissions

### Test Files

**test.php**
- Automated tests for SDK functionality
- Verifies all components work
- Demonstrates basic usage

## Class Hierarchy

```
Payhere (Main SDK)
├── Config (Configuration)
├── PaymentRequest (Payment Builder)
│   └── Uses Config for credentials
└── NotificationHandler (Callback Handler)
    └── Uses Config for verification
```

## Data Flow

### Payment Creation Flow
```
User Code
  → Payhere::createPaymentRequest()
  → PaymentRequest::setOrderId(), setAmount(), etc.
  → PaymentRequest::generateHash() (internal)
  → PaymentRequest::redirect() or generateForm()
  → Customer redirected to PayHere
```

### Notification Flow
```
PayHere Server
  → POST to notify_url
  → NotificationHandler::__construct($_POST)
  → NotificationHandler::verify()
  → NotificationHandler::isSuccess()
  → User Code processes payment
```

## Key Features by File

| File | Key Features |
|------|--------------|
| Payhere.php | Simple API, Factory pattern |
| Config.php | Environment management, URL handling |
| PaymentRequest.php | Fluent interface, Hash generation, Form builder |
| NotificationHandler.php | Hash verification, Status checking, Data extraction |

## Dependencies

- **PHP**: >= 7.4
- **Composer**: For autoloading
- **No external libraries**: Pure PHP implementation

## Extending the SDK

To add new features:

1. **New payment methods**: Extend `PaymentRequest.php`
2. **Additional verification**: Extend `NotificationHandler.php`
3. **New configurations**: Extend `Config.php`
4. **Keep backward compatibility**: Don't break existing API

## Testing Strategy

1. **Unit Tests**: Test individual methods
2. **Integration Tests**: Test payment flow
3. **Manual Testing**: Use sandbox environment
4. **Production Testing**: Small real transactions

## Maintenance

- Keep dependencies updated
- Monitor PayHere API changes
- Update documentation
- Address security issues promptly
- Maintain backward compatibility
