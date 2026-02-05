@echo off
REM Setup git hooks for conventional commits

echo Setting up git hooks...

REM Create hooks directory if it doesn't exist
if not exist .git\hooks mkdir .git\hooks

REM Copy hooks to .git/hooks
copy githooks\* .git\hooks\

echo Git hooks installed successfully!
echo Commit messages will now be validated for conventional commit format.