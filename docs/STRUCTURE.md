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
