# Contributing

Thank you for considering contributing to the Akira QR Code package. This document outlines the contribution process and guidelines.

## Code of Conduct

This project adheres to a code of conduct. By participating, you are expected to uphold this code.

## How to Contribute

### Reporting Bugs

Before creating bug reports, please check the existing issues to avoid duplicates. When creating a bug report, include:

- Clear and descriptive title
- Exact steps to reproduce the problem
- Expected behavior
- Actual behavior
- PHP version, Laravel version, and package version
- Code samples or test cases

**Example:**

```markdown
## Bug: QR Code with gradient not scanning

### Environment
- PHP: 8.4.0
- Laravel: 12.0
- Package: 1.0.0

### Steps to Reproduce
1. Generate QR code with gradient
2. Scan with iPhone camera
3. QR code not recognized

### Code
```php
QrCode::gradient(255, 0, 0, 0, 0, 255, 'RADIAL')->generate('test');
```

### Expected
QR code should scan successfully

### Actual
QR code not recognized
```

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, include:

- Clear and descriptive title
- Detailed description of the proposed feature
- Use cases and examples
- Why this enhancement would be useful

### Pull Requests

1. **Fork the repository**
2. **Create a feature branch** from `main`
3. **Make your changes** following the coding standards
4. **Add tests** for new functionality
5. **Update documentation** if needed
6. **Run tests and code analysis**
7. **Submit a pull request**

## Development Setup

### Clone Repository

```bash
git clone https://github.com/akira-io/laravel-qrcode.git
cd laravel-qrcode
```

### Install Dependencies

```bash
composer install
```

### Run Tests

```bash
# Run all tests
composer test

# Run with coverage
composer test-coverage

# Run specific test
vendor/bin/pest tests/Feature/QrCodeTest.php
```

### Run Code Analysis

```bash
# PHPStan static analysis
composer analyse

# Laravel Pint code style
composer lint
```

## Coding Standards

### Action Pattern

Follow the Akira Action Pattern for business logic:

**Correct:**

```php
final class BuildCustomStringAction
{
    public function handle(CustomData $data): string
    {
        // Business logic here
        return $result;
    }
}
```

**Incorrect:**

```php
class CustomHelper
{
    public static function build($data)
    {
        // Static methods discouraged
    }
}
```

### Value Objects

Use immutable readonly classes for data:

**Correct:**

```php
final readonly class CustomData
{
    public function __construct(
        public string $field1,
        public string $field2
    ) {}
    
    public static function create(
        string $field1,
        string $field2
    ): self {
        // Validation here
        return new self($field1, $field2);
    }
}
```

**Incorrect:**

```php
class CustomData
{
    public $field1; // Mutable properties
    public $field2;
}
```

### Dependency Injection

Use constructor injection, not manual instantiation:

**Correct:**

```php
class MyClass
{
    public function __construct(
        private BuildStringAction $action
    ) {}
    
    public function process(): string
    {
        return $this->action->handle($data);
    }
}
```

**Incorrect:**

```php
class MyClass
{
    public function process(): string
    {
        $action = new BuildStringAction(); // Manual instantiation
        return $action->handle($data);
    }
}
```

### Type Safety

Use strict types and full type declarations:

**Correct:**

```php
declare(strict_types=1);

public function handle(string $input): string
{
    return strtoupper($input);
}
```

**Incorrect:**

```php
public function handle($input)
{
    return strtoupper($input);
}
```

## Code Style

The project uses Laravel Pint for code style enforcement:

```bash
# Check code style
composer lint

# Fix code style
vendor/bin/pint
```

### Key Style Rules

- Use 4 spaces for indentation
- Use single quotes for strings (unless interpolation needed)
- Declare strict types at the top of files
- Use trailing commas in multiline arrays
- Maximum line length: 120 characters
- Use readonly properties when possible
- Use final classes when not designed for inheritance

## PHPStan Level 9

All code must pass PHPStan Level 9:

```bash
composer analyse
```

### Common PHPStan Issues

**Issue: Mixed type**
```php
// Bad
public function process($data)
{
    return $data;
}

// Good
public function process(string $data): string
{
    return $data;
}
```

**Issue: Undefined property**
```php
// Bad
$this->undefinedProperty = 'value';

// Good
public function __construct(
    private string $definedProperty
) {}
```

## Testing Requirements

All new features and bug fixes must include tests.

### Unit Tests

Test individual actions and value objects:

```php
test('action handles data correctly', function () {
    $action = new CustomAction();
    $result = $action->handle($data);
    
    expect($result)->toBe('expected');
});
```

### Integration Tests

Test complete workflows:

```php
test('generates custom qr code', function () {
    $data = CustomData::create('value1', 'value2');
    $dataType = CustomDataType::fromValueObject($data);
    
    $qrCode = QrCode::generate((string) $dataType);
    
    expect($qrCode)->toBeInstanceOf(HtmlString::class);
});
```

### Test Coverage

Aim for high test coverage:

```bash
composer test-coverage
```

Minimum coverage targets:
- Overall: 80%
- New features: 90%
- Critical paths: 100%

## Documentation

Update documentation for any changes:

### Code Documentation

Use PHPDoc for all public methods:

```php
/**
 * Generate a QR code from the given text.
 *
 * @param string $text The text to encode
 * @param string|null $filename Optional file path to save
 * @return HtmlString|string Generated QR code
 */
public function generate(string $text, ?string $filename = null): HtmlString|string
{
    // Implementation
}
```

### User Documentation

Update relevant documentation files in `docs/`:

- `README.md` - If adding major features
- `basic-usage.md` - For basic feature additions
- `advanced-features.md` - For complex features
- `api-reference.md` - For new public methods
- `examples.md` - For new use cases

## Pull Request Process

### Before Submitting

1. **Rebase on latest main**
   ```bash
   git fetch origin
   git rebase origin/main
   ```

2. **Run all tests**
   ```bash
   composer test
   ```

3. **Run static analysis**
   ```bash
   composer analyse
   ```

4. **Fix code style**
   ```bash
   composer lint
   ```

5. **Update documentation**
   - Add/update relevant docs
   - Update CHANGELOG.md

### Pull Request Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Changes Made
- Detailed list of changes
- Why these changes were needed

## Testing
- [ ] Added unit tests
- [ ] Added integration tests
- [ ] All tests passing
- [ ] PHPStan Level 9 passing
- [ ] Code style compliant

## Documentation
- [ ] Updated user documentation
- [ ] Updated API reference
- [ ] Added code examples
- [ ] Updated CHANGELOG.md

## Related Issues
Fixes #123
```

### Review Process

1. Automated checks must pass
2. Code review by maintainers
3. Address review feedback
4. Final approval and merge

## Versioning

The project follows Semantic Versioning (SemVer):

- **MAJOR** version for incompatible API changes
- **MINOR** version for new functionality (backwards compatible)
- **PATCH** version for bug fixes (backwards compatible)

## Commit Messages

Use clear, descriptive commit messages:

### Format

```
type(scope): subject

body

footer
```

### Types

- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation only
- `style`: Code style changes
- `refactor`: Code refactoring
- `test`: Adding tests
- `chore`: Maintenance tasks

### Examples

**Good:**
```
feat(wifi): add support for WPA3 encryption

Add WPA3 encryption type to WiFiData value object
and update BuildWiFiStringAction to handle WPA3.

Closes #123
```

**Bad:**
```
updated wifi stuff
```

## Adding New Data Types

When contributing a new data type, include:

1. **Value Object** - Validated, immutable data
2. **Action** - Business logic to build string
3. **DataType** - Orchestration class
4. **Tests** - Comprehensive test coverage
5. **Documentation** - Usage examples

Example structure:

```
src/
  ValueObjects/
    NewTypeData.php
  Actions/
    BuildNewTypeStringAction.php
  DataTypes/
    NewTypeDataType.php

tests/
  Feature/
    NewTypeDataTypeTest.php
  Unit/
    BuildNewTypeStringActionTest.php

docs/
  data-types.md (update)
  examples.md (update)
```

## Questions?

- Open a GitHub Discussion
- Check existing issues and documentation
- Contact maintainers via email

## Recognition

Contributors will be:
- Listed in CHANGELOG.md
- Credited in release notes
- Added to contributors list

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

## Thank You

Your contributions make this package better for everyone. Thank you for taking the time to contribute!
