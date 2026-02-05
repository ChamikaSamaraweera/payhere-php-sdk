@echo off
REM PayHere PHP SDK - Quick Setup Script for Windows
REM This script helps you set up and test the SDK

echo ========================================
echo PayHere PHP SDK - Setup Script
echo ========================================
echo.

REM Check if composer is installed
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Composer is not installed!
    echo Please install Composer from https://getcomposer.org/
    pause
    exit /b 1
)

echo [1/4] Checking PHP installation...
php --version
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] PHP is not installed or not in PATH!
    pause
    exit /b 1
)
echo.

echo [2/4] Installing dependencies...
composer install
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Failed to install dependencies!
    pause
    exit /b 1
)
echo.

echo [3/5] Running SDK tests...
php test.php
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Tests failed!
    pause
    exit /b 1
)
echo.

echo [4/5] Setting up git hooks...
setup-hooks.bat
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Failed to setup git hooks!
    pause
    exit /b 1
)
echo.

echo [5/5] Setup complete!
echo.
echo ========================================
echo Next Steps:
echo ========================================
echo.
echo 1. Get your PayHere credentials:
echo    - Login to https://www.payhere.lk/
echo    - Go to Side Menu ^> Integrations
echo    - Copy your Merchant ID and Secret
echo.
echo 2. Update examples/checkout.php with your credentials
echo.
echo 3. Start the development server:
echo    php -S localhost:8000 -t examples
echo.
echo 4. Open http://localhost:8000/checkout.php
echo.
echo 5. Read HOW_TO_USE.md for detailed instructions
echo.
echo ========================================
echo.

pause
