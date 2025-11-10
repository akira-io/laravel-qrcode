# Akira QR Code Generator - Documentation

A modern, type-safe QR Code generator for Laravel built with the Action Pattern, Value Objects, and Dependency Injection following the Akira architectural standards.

## Table of Contents

### Getting Started
1. [Installation](01-installation.md)
2. [Configuration](02-configuration.md)
3. [Quick Start](03-quick-start.md)

### Usage
4. [Basic Usage](04-basic-usage.md)
5. [Data Types](05-data-types.md)
6. [Customization](06-customization.md)
7. [Advanced Features](07-advanced-features.md)

### Examples & Reference
8. [Examples](08-examples.md)
9. [Architecture](09-architecture.md)
10. [API Reference](10-api-reference.md)
11. [Testing](11-testing.md)

### Contributing
12. [Contributing](12-contributing.md)

## Overview

This package provides a comprehensive QR code generation solution for Laravel applications, built on top of the BaconQrCode library with a clean, modern architecture.

## Key Features

**Architecture**
- Action Pattern - Business logic isolated in single-responsibility actions
- Value Objects - Immutable, validated data structures
- Type Safe - Full PHP 8.4+ type safety with readonly classes
- Laravel IoC - Automatic dependency injection
- PHPStan Level 9 - Maximum static analysis coverage

**Functionality**
- Multiple Formats - PNG, SVG, EPS
- Highly Customizable - Colors, gradients, sizes, margins, error correction
- Rich Data Types - WiFi, Email, Phone, SMS, Geo, Bitcoin
- Image Merging - Add logos to QR codes
- Thoroughly Tested - Comprehensive test coverage with Pest

## Requirements

- PHP 8.4 or higher
- Laravel 12.0 or higher
- ext-gd extension (for PNG format and image merging)
- bacon/bacon-qr-code ^3.0

## Quick Example

```php
use Akira\QrCode\Facades\QrCode;

// Simple text QR code
$qrCode = QrCode::text('Hello, World!');

// With customization
$qrCode = QrCode::size(300)
    ->color(255, 0, 0)
    ->text('https://example.com');

// WiFi QR Code
$qrCode = QrCode::wifi([
    'ssid' => 'MyNetwork',
    'password' => 'secret123'
]);

// Email QR Code
$qrCode = QrCode::email('contact@example.com', 'Subject', 'Body');

// Phone QR Code
$qrCode = QrCode::phone('+1234567890');
```

## Support

- Repository: https://github.com/akira-io/laravel-qrcode
- Issues: https://github.com/akira-io/laravel-qrcode/issues

## License

The MIT License (MIT). Please see [License File](../LICENSE) for more information.
