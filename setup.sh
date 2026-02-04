#!/bin/bash
# PayHere PHP SDK - Quick Setup Script for Linux/Mac
# This script helps you set up and test the SDK

echo "========================================"
echo "PayHere PHP SDK - Setup Script"
echo "========================================"
echo ""

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "[ERROR] Composer is not installed!"
    echo "Please install Composer from https://getcomposer.org/"
    exit 1
fi

echo "[1/4] Checking PHP installation..."
php --version
if [ $? -ne 0 ]; then
    echo "[ERROR] PHP is not installed or not in PATH!"
    exit 1
fi
echo ""

echo "[2/4] Installing dependencies..."
composer install
if [ $? -ne 0 ]; then
    echo "[ERROR] Failed to install dependencies!"
    exit 1
fi
echo ""

echo "[3/4] Running SDK tests..."
php test.php
if [ $? -ne 0 ]; then
    echo "[ERROR] Tests failed!"
    exit 1
fi
echo ""

echo "[4/4] Setup complete!"
echo ""
echo "========================================"
echo "Next Steps:"
echo "========================================"
echo ""
echo "1. Get your PayHere credentials:"
echo "   - Login to https://www.payhere.lk/"
echo "   - Go to Side Menu > Integrations"
echo "   - Copy your Merchant ID and Secret"
echo ""
echo "2. Update examples/checkout.php with your credentials"
echo ""
echo "3. Start the development server:"
echo "   php -S localhost:8000 -t examples"
echo ""
echo "4. Open http://localhost:8000/checkout.php"
echo ""
echo "5. Read HOW_TO_USE.md for detailed instructions"
echo ""
echo "========================================"
echo ""
