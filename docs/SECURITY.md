# Security Policy

## Reporting a Vulnerability

If you discover a security vulnerability within this SDK, please send an email to chamika@teaminfinity.lk. All security vulnerabilities will be promptly addressed.

Please do not publicly disclose the issue until it has been addressed by the maintainers.

## Security Best Practices

When using this SDK, please follow these security best practices:

### 1. Protect Your Merchant Secret
- **Never** expose your Merchant Secret in client-side code
- Store it securely in environment variables or configuration files
- Do not commit it to version control

### 2. Verify All Notifications
- Always use the `verify()` method to validate payment notifications
- Never trust payment data without verification
- Check the hash signature before processing payments

### 3. Use HTTPS
- All callback URLs (notify_url, return_url, cancel_url) must use HTTPS
- Ensure your server has a valid SSL certificate

### 4. Validate Amounts
- Always verify the payment amount matches your expected amount
- Check the currency is correct
- Validate the order ID exists in your system

### 5. Idempotency
- Use unique order IDs for each transaction
- Handle duplicate notifications gracefully
- Store payment records before redirecting to PayHere

### 6. Environment Separation
- Use sandbox for development and testing
- Never use production credentials in development
- Test thoroughly before going live

### 7. Error Handling
- Log all payment notifications for audit purposes
- Implement proper error handling
- Monitor for suspicious activity

### 8. Server Security
- Keep PHP and dependencies up to date
- Use the latest stable version of this SDK
- Implement rate limiting on notification endpoints
- Restrict access to sensitive endpoints

## Supported Versions

| Version | Supported          |
| ------- | ------------------ |
| 1.0.x   | :white_check_mark: |

## Known Security Considerations

- This SDK relies on MD5 hashing as specified by PayHere's API
- Always validate data on the server side
- Implement additional fraud detection as needed for your use case
