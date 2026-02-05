#!/bin/bash
# PayHere PHP SDK - Universal Setup Script
# This script detects the OS and runs the appropriate setup script

echo "========================================"
echo "PayHere PHP SDK - Universal Setup"
echo "========================================"
echo ""

# Detect OS and run appropriate setup script
if [[ "$OSTYPE" == "msys" ]] || [[ "$OSTYPE" == "win32" ]] || [[ "$OS" == "Windows_NT" ]]; then
    echo "Detected Windows OS"
    ./setup/setup-windows.bat
else
    echo "Detected Unix-like OS (Linux/Mac)"
    ./setup/setup-unix.sh
fi
