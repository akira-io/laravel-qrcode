# Installation

## System Requirements

- PHP 8.4 or higher
- Laravel 12.0 or higher
- ext-gd extension for image merging
- ext-imagick extension for PNG output

## Dependencies

The package requires the following Composer packages:

- `bacon/bacon-qr-code` ^3.0 - Core QR code generation library
- `illuminate/contracts` ^12.0 - Laravel contracts
- `illuminate/support` ^12.0 - Laravel support utilities

## Installation via Composer

Install the package using Composer:

```bash
composer require akira/laravel-qrcode
```

The package will automatically install all required dependencies.

## Service Provider Registration

The package uses Laravel's package auto-discovery feature. The service provider and facade are automatically registered.

**Automatic Registration** (Default)
- Service Provider: `Akira\QrCode\QrCodeServiceProvider`
- Facade: `Akira\QrCode\Facades\QrCode`

**Manual Registration** (if needed)

If auto-discovery is disabled, add to `config/app.php`:

```php
'providers' => [
    // ...
    Akira\QrCode\QrCodeServiceProvider::class,
],

'aliases' => [
    // ...
    'QrCode' => Akira\QrCode\Facades\QrCode::class,
],
```

## Publishing Configuration

Publish the configuration file (optional):

```bash
php artisan vendor:publish --tag="qrcode-config"
```

This creates `config/qrcode.php` where you can customize default values:

```php
return [
    'format' => 'png',              // Default format: png, svg, eps
    'size' => 200,                  // Default size in pixels
    'margin' => 4,                  // Default margin
    'color' => [0, 0, 0, 0],       // Default foreground color (RGBA)
    'background_color' => [255, 255, 255, 0], // Default background
    'error_correction' => 'H',      // Error correction level: L, M, Q, H
    'encoding' => 'UTF-8',          // Character encoding
    'merge' => [
        'percentage' => 0.2,        // Logo size as percentage
        'absolute' => false,        // Use absolute size
    ],
];
```

## Verify Installation

Test the installation by creating a test route:

```php
use Akira\QrCode\Facades\QrCode;

Route::get('/test-qrcode', function () {
    return QrCode::generate('Installation successful!');
});
```

Visit `/test-qrcode` in your browser. You should see a QR code.

## Helper Function

The package provides a global helper function:

```php
// Get QrCode instance
$qrcode = qrcode();

// Generate QR code directly
$qrcode = qrcode('Hello, World!');
```

## Environment Variables

You can configure defaults via environment variables in `.env`:

```env
QR_CODE_FORMAT=png
QR_CODE_SIZE=200
QR_CODE_MARGIN=4
QR_CODE_ERROR_CORRECTION=H
QR_CODE_ENCODING=UTF-8

# Colors (RGBA values 0-255)
QR_CODE_COLOR_R=0
QR_CODE_COLOR_G=0
QR_CODE_COLOR_B=0
QR_CODE_COLOR_A=0

QR_CODE_BACKGROUND_COLOR_R=255
QR_CODE_BACKGROUND_COLOR_G=255
QR_CODE_BACKGROUND_COLOR_B=255
QR_CODE_BACKGROUND_COLOR_A=0

# Merge options
QR_CODE_MERGE_PERCENTAGE=0.2
QR_CODE_MERGE_ABSOLUTE=false
```

## Troubleshooting

**GD Extension Missing**

If you encounter errors about missing GD extension when using PNG format:

```bash
# Ubuntu/Debian
sudo apt-get install php8.4-gd

# macOS with Homebrew
brew install php@8.4
brew link php@8.4

# Verify installation
php -m | grep gd
```

**Composer Version Conflicts**

If you encounter version conflicts:

```bash
# Update Composer
composer self-update

# Clear cache
composer clear-cache

# Install with specific Laravel version
composer require akira/laravel-qrcode --with-all-dependencies
```

## Next Steps

- [Configuration](02-configuration.md) - Detailed configuration options
- [Quick Start](03-quick-start.md) - Get started quickly
- [Basic Usage](04-basic-usage.md) - Learn the fundamentals

**Previous:** [Roadmap](00-roadmap.md) | **Next:** [Configuration](02-configuration.md)
