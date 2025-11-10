# Laravel QR Code Generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/akira/laravel-qrcode.svg?style=flat-square)](https://packagist.org/packages/akira/laravel-qrcode)
[![Tests](https://img.shields.io/github/actions/workflow/status/akira/laravel-qrcode/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/akira/laravel-qrcode/actions/workflows/tests.yml)
[![PHPStan](https://img.shields.io/github/actions/workflow/status/akira/laravel-qrcode/phpstan.yml?branch=main&label=phpstan&style=flat-square)](https://github.com/akira/laravel-qrcode/actions/workflows/phpstan.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/akira/laravel-qrcode.svg?style=flat-square)](https://packagist.org/packages/akira/laravel-qrcode)

A clean, modern, and easy-to-use QR code generator for Laravel applications. Built with the Action Pattern, Value Objects, and full type safety.

## Features

- Multiple output formats (PNG, SVG, EPS)
- Highly customizable (colors, gradients, styles, sizes)
- Specialized data types (WiFi, Email, Phone, SMS, Geo, Bitcoin)
- Logo/image merging support
- Type-safe with PHP 8.4+
- PHPStan Level 9 compliant
- Comprehensive test coverage

## Requirements

- PHP 8.4+
- Laravel 12.0+

## Installation

Install via Composer:

```bash
composer require akira/laravel-qrcode
```

Optionally, publish the configuration:

```bash
php artisan vendor:publish --tag="qrcode-config"
```

## Quick Start

```php
use Akira\QrCode\Facades\QrCode;

// Simple text
$qrCode = QrCode::text('Hello World');

// With customization
$qrCode = QrCode::size(300)
    ->color(255, 0, 0)
    ->text('https://example.com');

// WiFi network
$qrCode = QrCode::wifi([
    'ssid' => 'MyNetwork',
    'password' => 'secret123'
]);

// Email
$qrCode = QrCode::email('contact@example.com', 'Subject', 'Body');

// Phone
$qrCode = QrCode::phone('+1234567890');

// With styling
$qrCode = QrCode::size(400)
    ->gradient(255, 0, 0, 0, 0, 255, 'diagonal')
    ->style('round', 0.7)
    ->eye('circle')
    ->text('Styled QR Code');
```

## Documentation

Complete documentation is available in the [docs](docs/) folder:

### Getting Started
1. [Installation](docs/01-installation.md) - Detailed installation guide
2. [Configuration](docs/02-configuration.md) - Configuration options
3. [Quick Start](docs/03-quick-start.md) - Get started in 5 minutes

### Usage Guide
4. [Basic Usage](docs/04-basic-usage.md) - Fundamentals and examples
5. [Data Types](docs/05-data-types.md) - WiFi, Email, Phone, SMS, Geo, Bitcoin
6. [Customization](docs/06-customization.md) - Colors, gradients, styles
7. [Advanced Features](docs/07-advanced-features.md) - Logo merging, custom types

### Examples & Reference
8. [Examples](docs/08-examples.md) - Real-world use cases
9. [Architecture](docs/09-architecture.md) - Package design patterns
10. [API Reference](docs/10-api-reference.md) - Complete method reference
11. [Testing](docs/11-testing.md) - Testing guide
12. [Contributing](docs/12-contributing.md) - How to contribute

## Available Data Types

| Type | Description | Example |
|------|-------------|---------|
| WiFi | Network credentials | `QrCode::wifi(['ssid' => 'Network', 'password' => 'pass'])` |
| Email | mailto links | `QrCode::email('email@example.com', 'Subject', 'Body')` |
| Phone | Direct dial | `QrCode::phone('+1234567890')` |
| SMS | Pre-filled message | `QrCode::sms('+1234567890', 'Hello')` |
| Geo | GPS coordinates | `QrCode::geo(37.7749, -122.4194, 'San Francisco')` |
| Bitcoin | Payment address | `QrCode::bitcoin('address', 0.001, ['label' => 'Donation'])` |

## Customization Options

| Option | Description | Values |
|--------|-------------|--------|
| Size | Dimensions in pixels | `size(300)` |
| Colors | Foreground/background | `color(r, g, b)`, `backgroundColor(r, g, b)` |
| Gradients | Color transitions | `gradient(r1, g1, b1, r2, g2, b2, 'type')` |
| Styles | Module shapes | `style('square\|dot\|round', 0-1)` |
| Eyes | Pattern styles | `eye('square\|circle')`, `eyeColor(...)` |
| Error Correction | Data recovery | `errorCorrection('L\|M\|Q\|H')` |
| Formats | Output type | `format('png\|svg\|eps')` |
| Logo | Image merging | `merge('/path/to/logo.png', 0.2)` |

## Usage Examples

### In Blade Templates

```blade
<img src="data:image/png;base64,{{ base64_encode($qrCode) }}" alt="QR Code">
```

### API Response

```php
return response()->json([
    'qrcode' => base64_encode(QrCode::format('png')->text($data))
]);
```

### Download Response

```php
$png = QrCode::format('png')->size(500)->text($data);

return response($png)
    ->header('Content-Type', 'image/png')
    ->header('Content-Disposition', 'attachment; filename="qrcode.png"');
```

### With Logo

```php
$qrCode = QrCode::format('png')
    ->size(400)
    ->errorCorrection('H')
    ->merge(public_path('logo.png'), 0.2)
    ->text('https://example.com');
```

## Testing

```bash
# Run tests
composer test

# Run tests with coverage
composer test-coverage

# Run static analysis
composer analyse

# Run code style fixer
composer lint
```

## Architecture

This package is built with:

- **Action Pattern** - Single-responsibility business logic
- **Value Objects** - Immutable, validated data structures
- **Dependency Injection** - Laravel IoC container
- **Type Safety** - PHP 8.4+ with readonly classes
- **SOLID Principles** - Clean, maintainable code

See [Architecture Documentation](docs/09-architecture.md) for details.

## Contributing

Contributions are welcome! Please see [Contributing Guide](docs/12-contributing.md) for details.

## Security

If you discover any security issues, please email kidiatoliny@gmail.com instead of using the issue tracker.

## Credits

- [Kidiatoliny](https://github.com/kidiatoliny)
- Built with [BaconQrCode](https://github.com/Bacon/BaconQrCode)
- All Contributors

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Links

- [Documentation](docs/)
- [Changelog](CHANGELOG.md)
- [Issue Tracker](https://github.com/akira-io/laravel-qrcode/issues)
- [Packagist](https://packagist.org/packages/akira/laravel-qrcode)
