# Akira QR Code Generator - Documentation

A modern, type-safe QR Code generator for Laravel built with the Action Pattern, Value Objects, and Dependency Injection following the Akira architectural standards.

## Table of Contents

- [Installation](installation.md)
- [Configuration](configuration.md)
- [Quick Start](quick-start.md)
- [Architecture](architecture.md)
- [Basic Usage](basic-usage.md)
- [Data Types](data-types.md)
- [Customization](customization.md)
- [Advanced Features](advanced-features.md)
- [API Reference](api-reference.md)
- [Examples](examples.md)
- [Testing](testing.md)
- [Contributing](contributing.md)

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
$qrCode = QrCode::generate('Hello, World!');

// With customization
$qrCode = QrCode::size(300)
    ->color(255, 0, 0)
    ->generate('https://example.com');

// Using Value Objects
use Akira\QrCode\ValueObjects\WiFiData;
use Akira\QrCode\DataTypes\WiFiDataType;

$wifiData = WiFiData::create(
    ssid: 'MyNetwork',
    password: 'secret123',
    encryption: 'WPA'
);

$dataType = WiFiDataType::fromValueObject($wifiData);
$qrCode = QrCode::generate((string) $dataType);
```

## Support

- Repository: https://github.com/akira-io/laravel-qrcode
- Issues: https://github.com/akira-io/laravel-qrcode/issues

## License

The MIT License (MIT). Please see [License File](../LICENSE) for more information.
