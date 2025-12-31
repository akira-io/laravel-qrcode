# Contributing to Akira Laravel QR Code

Thank you for your interest in contributing! This package welcomes contributions from the community.

## How to Contribute

### Reporting Issues

- Check existing issues before creating a new one
- Provide clear reproduction steps
- Include environment details (PHP, Laravel, package versions)
- Add code examples when applicable

### Pull Requests

1. Fork the repository
2. Create a feature branch from `main`
3. Make your changes following our coding standards
4. Add tests for new functionality
5. Ensure all tests pass
6. Update documentation as needed
7. Submit a pull request

## Development Setup

```bash
# Clone your fork
git clone https://github.com/YOUR_USERNAME/laravel-qrcode.git
cd laravel-qrcode

# Install dependencies
composer install

# Run tests
composer test
```

## Coding Standards

This package follows:

- **PSR-12** coding style
- **PHPStan Level 9** static analysis
- **Action Pattern** for business logic
- **Value Objects** for data structures
- **Type safety** with PHP 8.4+

### Run Quality Checks

```bash
# Code style
composer lint

# Static analysis
composer analyse

# Tests
composer test
```

## Testing

All contributions must include tests:

- Unit tests for actions and value objects
- Integration tests for complete workflows
- Minimum 80% code coverage

```bash
# Run tests with coverage
composer test:coverage
```

## Documentation

Update documentation for any changes:

- Code examples in docblocks
- User documentation in `/docs`
- README for major features
- CHANGELOG for all changes

## Commit Messages

Use clear, descriptive commit messages:

```
feat(wifi): add WPA3 encryption support
fix(merge): correct alpha channel handling
docs(readme): update installation steps
```

## Architecture Guidelines

### Adding New Data Types

1. Create a Value Object in `src/ValueObjects/`
2. Create a Build Action in `src/Actions/`
3. Create a DataType in `src/DataTypes/`
4. Add method to `DataTypeMapper`
5. Add PHPDoc to `QrCode` class
6. Write comprehensive tests
7. Update documentation

### Example Structure

```php
// ValueObject
final readonly class MyData
{
    public static function create(string $value): self
    {
        // Validation
        return new self($value);
    }
}

// Action
final class BuildMyStringAction
{
    public function handle(MyData $data): string
    {
        // Business logic
        return $result;
    }
}

// DataType
final readonly class MyDataType implements QrCodeDataTypeContract
{
    public function __construct(
        private MyData $data,
        private BuildMyStringAction $action
    ) {}
    
    public function __toString(): string
    {
        return $this->action->handle($this->data);
    }
}
```

## Code Review Process

1. Automated checks must pass (tests, PHPStan, Pint)
2. Code review by maintainers
3. Address feedback
4. Final approval and merge

## Questions?

- Open a GitHub Discussion
- Check `/docs` directory
- Review existing issues and PRs

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

---

For detailed guidelines, see [docs/12-contributing.md](docs/12-contributing.md)
