#!/bin/bash

# Setup git hooks for conventional commits

echo "Setting up git hooks..."

# Copy hooks to .git/hooks
cp githooks/* .git/hooks/

# Make them executable
chmod +x .git/hooks/*

echo "Git hooks installed successfully!"
echo "Commit messages will now be validated for conventional commit format."