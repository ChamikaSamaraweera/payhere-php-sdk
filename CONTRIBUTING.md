# Contributing to PayHere PHP SDK

Thank you for considering contributing to the PayHere PHP SDK! This document provides guidelines for contributing to the project.

## How to Contribute

### Reporting Bugs

If you find a bug, please create an issue on GitHub with:
- A clear, descriptive title
- Steps to reproduce the issue
- Expected behavior
- Actual behavior
- PHP version and environment details

### Suggesting Enhancements

Enhancement suggestions are welcome! Please create an issue with:
- A clear description of the enhancement
- Use cases and benefits
- Any implementation ideas you have

### Pull Requests

1. Fork the repository
2. Clone your fork (`git clone https://github.com/your-username/payhere-php-sdk.git`)
3. Run the setup script to install git hooks (`./setup-hooks.sh`)
4. Create a new branch (`git checkout -b feature/your-feature-name`)
5. Make your changes
6. Write or update tests if applicable
7. Update documentation as needed
8. Commit your changes using conventional commit format (`git commit -m 'feat: add new feature'`)
9. Push to the branch (`git push origin feature/your-feature-name`)
10. Create a Pull Request

### Commit Message Guidelines

This project uses [Conventional Commits](https://conventionalcommits.org/) for commit messages. This helps maintain a clear and structured git history.

**Format:** `type(scope): description`

**Valid types:**
- `feat`: A new feature
- `fix`: A bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, etc.)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

**Examples:**
- `feat: add support for recurring payments`
- `fix(auth): resolve hash verification issue`
- `docs: update installation instructions`

The commit hooks will validate your commit messages automatically.

### Testing

- Test your changes thoroughly
- Include both sandbox and live environment considerations
- Verify hash generation and verification work correctly

## Code of Conduct

- Be respectful and inclusive
- Welcome newcomers
- Focus on constructive feedback
- Help maintain a positive community

## Questions?

Feel free to open an issue for any questions about contributing!

## License

By contributing, you agree that your contributions will be licensed under the MIT License.
