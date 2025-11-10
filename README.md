# Akira QR Code Generator for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/akira/laravel-qrcode.svg?style=flat-square)](https://packagist.org/packages/akira/laravel-qrcode)
[![Tests](https://img.shields.io/github/actions/workflow/status/akira/laravel-qrcode/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/akira/laravel-qrcode/actions/workflows/tests.yml)
[![PHPStan](https://img.shields.io/github/actions/workflow/status/akira/laravel-qrcode/phpstan.yml?branch=main&label=phpstan&style=flat-square)](https://github.com/akira/laravel-qrcode/actions/workflows/phpstan.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/akira/laravel-qrcode.svg?style=flat-square)](https://packagist.org/packages/akira/laravel-qrcode)

A modern, type-safe QR Code generator for Laravel 12+ and PHP 8.4+. Built with **Action Pattern**, **Value Objects**, and **Dependency Injection** following the Akira architectural standards.

## Key Features

- **Action Pattern** - Business logic isolated in single-responsibility actions with `handle()` method
- **Value Objects** - Immutable, validated data structures
- **Type Safe** - Full PHP 8.4+ type safety with readonly classes
- **Laravel IoC** - Automatic dependency injection, no manual instantiation
- **PHPStan Level 9** - Maximum static analysis coverage
- **Multiple Formats** - PNG, SVG, EPS
- **Highly Customizable** - Colors, gradients, sizes, margins, error correction, eye styles
- **Rich Data Types** - WiFi, Email, Phone, SMS, Geo, Bitcoin
- **Thoroughly Tested** - Comprehensive test coverage with Pest

## Requirements

- **PHP 8.4 or higher**
- **Laravel 12.0 or higher**

## Installation

```bash
composer require akira/laravel-qrcode
```

Optional: Publish the configuration file:

```bash
php artisan vendor:publish --tag="qrcode-config"
```

## Quick Start

### Basic Usage

```php
use Akira\QrCode\Facades\QrCode;

// Simple text QR code
$qrCode = QrCode::generate('Hello, World!');

// With customization
$qrCode = QrCode::size(300)
    ->color(255, 0, 0)
    ->generate('https://example.com');

// Save to file
QrCode::size(400)
    ->format('png')
    ->generate('Visit my website!', 'qrcode.png');
```

### Using Value Objects and DataTypes (Recommended Pattern)

 Using **Value Objects** for data encapsulation and **Actions** for business logic:

```php
use Akira\QrCode\ValueObjects\WiFiData;
use Akira\QrCode\DataTypes\WiFiDataType;
use Akira\QrCode\Facades\QrCode;

// 1. Create a Value Object (validated, immutable data)
$wifiData = WiFiData::create(
    ssid: 'MyNetwork',
    password: 'secret123',
    encryption: 'WPA',
    hidden: false
);

// 2. Create DataType (Laravel IoC automatically injects the action)
$dataType = WiFiDataType::fromValueObject($wifiData);

// 3. Generate QR code
$qrCode = QrCode::size(400)->generate((string) $dataType);
```

## Architecture

This package follows the Action pattern with strict separation of concerns:

### 1. Value Objects

Immutable, validated data containers with no business logic:

```php
final readonly class WiFiData
{
    public function __construct(
        public string $ssid,
        public string $password,
        public string $encryption,
        public bool $hidden
    ) {}
    
    public static function create(
        string $ssid,
        string $password,
        string $encryption = 'WPA',
        bool $hidden = false
    ): self {
        // Validation happens here
        return new self($ssid, $password, $encryption, $hidden);
    }
}
```

### 2. Actions

Single-responsibility classes with a `handle()` method containing business logic:

```php
final class BuildWiFiStringAction
{
    public function handle(WiFiData $data): string
    {
        return sprintf(
            'WIFI:T:%s;S:%s;P:%s;H:%s;;',
            $data->encryption,
            $this->escapeValue($data->ssid),
            $this->escapeValue($data->password),
            $data->hidden ? 'true' : 'false'
        );
    }
    
    private function escapeValue(string $value): string
    {
        return str_replace(['\\', ';', ',', ':', '"'], 
            ['\\\\', '\\;', '\\,', '\\:', '\\"'], $value);
    }
}
```

### 3. DataTypes

Orchestration layer leveraging Laravel's IoC container for automatic dependency injection:

```php
final readonly class WiFiDataType implements QrCodeDataTypeContract
{
    // Actions injected via IoC - no manual instantiation!
    public function __construct(
        private WiFiData $data,
        private BuildWiFiStringAction $action
    ) {}
    
    public static function fromValueObject(WiFiData $data): self
    {
        // Laravel IoC resolves dependencies automatically
        return app(self::class, ['data' => $data]);
    }
    
    public function __toString(): string
    {
        return $this->action->handle($this->data);
    }
}
```

### Data Flow Diagram

```
User Input
    ↓
Value Object (validated, immutable)
    ↓
DataType (orchestration via Laravel IoC)
    ↓
Action (business logic via handle() method)
    ↓
String Output
    ↓
QrCode Generator (with injected actions)
    ↓
PNG/SVG/EPS
```

## Supported Data Types

### WiFi Networks

```php
use Akira\QrCode\ValueObjects\WiFiData;
use Akira\QrCode\DataTypes\WiFiDataType;

$wifiData = WiFiData::create(
    ssid: 'HomeNetwork',
    password: 'MyPassword123',
    encryption: 'WPA',  // WPA, WEP, or nopass
    hidden: false
);

$dataType = WiFiDataType::fromValueObject($wifiData);
QrCode::generate((string) $dataType);
```

### Email Addresses

```php
use Akira\QrCode\ValueObjects\EmailData;
use Akira\QrCode\DataTypes\EmailDataType;

$emailData = EmailData::create(
    email: 'contact@example.com',
    subject: 'Hello',
    body: 'Message content'
);

$dataType = EmailDataType::fromValueObject($emailData);
QrCode::generate((string) $dataType);
```

### Phone Numbers

```php
use Akira\QrCode\ValueObjects\PhoneNumber;
use Akira\QrCode\DataTypes\PhoneNumberDataType;

$phoneNumber = PhoneNumber::create('+1234567890');
$dataType = PhoneNumberDataType::fromValueObject($phoneNumber);
QrCode::generate((string) $dataType);
```

### SMS Messages

```php
use Akira\QrCode\ValueObjects\SMSData;
use Akira\QrCode\DataTypes\SMSDataType;

$smsData = SMSData::create(
    phoneNumber: '+1234567890',
    message: 'Hello from QR Code!'
);

$dataType = SMSDataType::fromValueObject($smsData);
QrCode::generate((string) $dataType);
```

### Geo Location

```php
use Akira\QrCode\ValueObjects\GeoLocation;
use Akira\QrCode\DataTypes\GeoDataType;

$location = GeoLocation::create(
    latitude: 37.7749,
    longitude: -122.4194
);

$dataType = GeoDataType::fromValueObject($location);
QrCode::generate((string) $dataType);
```

### Bitcoin Addresses

```php
use Akira\QrCode\ValueObjects\BitcoinData;
use Akira\QrCode\DataTypes\BitcoinDataType;

$bitcoinData = BitcoinData::create(
    address: '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa',
    amount: 0.001,
    label: 'Donation',
    message: 'Thank you!'
);

$dataType = BitcoinDataType::fromValueObject($bitcoinData);
QrCode::generate((string) $dataType);
```

## Customization Options

### Size

```php
QrCode::size(300)->generate('Hello');  // Default: 100
```

### Colors

```php
QrCode::color(255, 0, 0)              // Red foreground
    ->backgroundColor(0, 0, 0)         // Black background
    ->generate('Hello');

// With alpha transparency
QrCode::color(255, 0, 0, 50)
    ->generate('Hello');
```

### Individual Eye Colors

```php
QrCode::eyeColor(0, 255, 0, 0)        // Eye 0: red inner
    ->eyeColor(1, 0, 255, 0)          // Eye 1: green inner
    ->eyeColor(2, 0, 0, 255)          // Eye 2: blue inner
    ->generate('Hello');

// With outer color
QrCode::eyeColor(0, 255, 0, 0, 0, 0, 255)  // Red inner, blue outer
    ->generate('Hello');
```

### Gradients

```php
QrCode::gradient(
    startRed: 255, startGreen: 0, startBlue: 0,
    endRed: 0, endGreen: 0, endBlue: 255,
    type: 'VERTICAL'  // VERTICAL, HORIZONTAL, DIAGONAL, RADIAL
)->generate('Hello');
```

### Margin

```php
QrCode::margin(20)->generate('Hello');  // Default: 0
```

### Error Correction

```php
QrCode::errorCorrection('H')->generate('Hello');

// L = Low (7% correction)
// M = Medium (15% correction)  
// Q = Quartile (25% correction)
// H = High (30% correction) - Best for QR codes with logos
```

### Module Styles

```php
// Square (default)
QrCode::style('square')->generate('Hello');

// Dots
QrCode::style('dot', 0.5)->generate('Hello');  // Size: 0-1

// Rounded
QrCode::style('round', 0.7)->generate('Hello');
```

### Eye Styles

```php
// Square (default)
QrCode::eye('square')->generate('Hello');

// Circle
QrCode::eye('circle')->generate('Hello');
```

### Output Formats

```php
// SVG (default)
$svg = QrCode::format('svg')->generate('Hello');

// PNG
$png = QrCode::format('png')->generate('Hello');

// EPS
$eps = QrCode::format('eps')->generate('Hello');
```

### Merge with Logo/Image

```php
// Only works with PNG format
QrCode::format('png')
    ->merge('/path/to/logo.png', 0.2)  // 20% of QR code size
    ->generate('Hello');

// Or merge with image string
QrCode::format('png')
    ->mergeString($imageContent, 0.3)
    ->generate('Hello');
```

## Dependency Injection in Action

One of the core principles of the Akira pattern is leveraging Laravel's IoC container:

### QrCode Class

Actions are injected via constructor, not manually instantiated:

```php
class QrCode
{
    public function __construct(
        protected GenerateQrCodeAction $generateAction,
        protected CreateColorAction $colorAction,
        protected MergeImageAction $mergeImageAction
    ) {}
    
    public function generate(string $text, ?string $filename = null): HtmlString|string
    {
        // Uses injected action - no "new" keyword!
        return $this->generateAction->handle(
            $text,
            $this->getWriter($this->getRenderer()),
            $this->encoding,
            $this->errorCorrection,
            // ... other params
        );
    }
}
```

### Using in Controllers

```php
use Akira\QrCode\QrCode;

class InvoiceController extends Controller
{
    // QrCode injected automatically by Laravel
    public function __construct(
        private QrCode $qrCode
    ) {}
    
    public function show(Invoice $invoice)
    {
        $qrCodeImage = $this->qrCode
            ->size(300)
            ->errorCorrection('H')
            ->generate($invoice->paymentUrl());
            
        return view('invoice.show', [
            'invoice' => $invoice,
            'qrCodeImage' => $qrCodeImage
        ]);
    }
}
```

## Testing

Run tests:

```bash
composer test
```

Run tests with coverage:

```bash
composer test-coverage
```

Run PHPStan analysis:

```bash
composer analyse
```

Run Laravel Pint code style fixer:

```bash
composer lint
```

### Testing with Mocks

The Akira pattern makes testing easy with dependency injection:

```php
use Akira\QrCode\Actions\BuildWiFiStringAction;
use Akira\QrCode\ValueObjects\WiFiData;
use Akira\QrCode\DataTypes\WiFiDataType;

test('builds wifi qrcode string correctly', function () {
    // Mock the action
    $mockAction = Mockery::mock(BuildWiFiStringAction::class);
    $mockAction->shouldReceive('handle')
        ->once()
        ->andReturn('WIFI:T:WPA;S:TestNetwork;P:pass123;;');
    
    // Inject mock into container
    $this->app->instance(BuildWiFiStringAction::class, $mockAction);
    
    $wifiData = WiFiData::create('TestNetwork', 'pass123');
    $dataType = WiFiDataType::fromValueObject($wifiData);
    
    expect((string) $dataType)->toBe('WIFI:T:WPA;S:TestNetwork;P:pass123;;');
});
```

## Configuration

Configuration file at `config/qrcode.php`:

```php
return [
    'default_size' => 100,
    'default_margin' => 0,
    'default_format' => 'svg',
    'default_error_correction' => 'M',
    
    'color' => [
        'foreground' => [0, 0, 0],      // Black
        'background' => [255, 255, 255], // White
    ],
];
```

## Real-World Examples

### Blade Template with Base64

```blade
<div class="qrcode-container">
    <img src="data:image/png;base64,{{ base64_encode($qrCode) }}" 
         alt="QR Code"
         class="w-64 h-64">
</div>
```

### API Response

```php
use Illuminate\Http\JsonResponse;
use Akira\QrCode\Facades\QrCode;

public function generateQrCode(Request $request): JsonResponse
{
    $qrCode = QrCode::format('png')
        ->size(300)
        ->generate($request->input('text'));
    
    return response()->json([
        'qrcode' => base64_encode($qrCode),
        'format' => 'png',
        'size' => 300
    ]);
}
```

### Download Response

```php
use Illuminate\Http\Response;
use Akira\QrCode\Facades\QrCode;

public function downloadQrCode(Request $request): Response
{
    $png = QrCode::format('png')
        ->size(500)
        ->errorCorrection('H')
        ->generate($request->input('text'));
    
    return response($png)
        ->header('Content-Type', 'image/png')
        ->header('Content-Disposition', 'attachment; filename="qrcode.png"');
}
```

### Event Ticket with Logo

```php
use Akira\QrCode\Facades\QrCode;

public function generateTicket(Ticket $ticket)
{
    $qrCode = QrCode::format('png')
        ->size(400)
        ->errorCorrection('H')  // High correction needed for logo
        ->merge(public_path('images/logo.png'), 0.25)
        ->generate($ticket->verification_code);
    
    // Store or return the QR code
    Storage::disk('public')->put(
        "tickets/{$ticket->id}.png",
        $qrCode
    );
}
```

### Caching QR Codes

```php
use Illuminate\Support\Facades\Cache;
use Akira\QrCode\ValueObjects\WiFiData;
use Akira\QrCode\DataTypes\WiFiDataType;
use Akira\QrCode\Facades\QrCode;

$wifiQrCode = Cache::remember('qrcode:wifi:guest', 3600, function () {
    $wifiData = WiFiData::create('GuestNetwork', 'guest2024');
    $dataType = WiFiDataType::fromValueObject($wifiData);
    
    return QrCode::format('png')
        ->size(300)
        ->generate((string) $dataType);
});
```

## SOLID Principles

This package strictly follows SOLID principles:

- **Single Responsibility** - Each action has one purpose, each class one reason to change
- **Open/Closed** - Open for extension via new DataTypes/Actions, closed for modification
- **Liskov Substitution** - All DataTypes implement QrCodeDataTypeContract
- **Interface Segregation** - Minimal, focused interfaces
- **Dependency Inversion** - Depends on abstractions (interfaces), IoC handles concrete implementations

## Performance Tips

- **Readonly classes** - Zero runtime overhead, no defensive copying
- **Type safety** - No runtime type checking needed
- **Laravel IoC** - Efficient singleton/scoped resolution
- **Cacheable** - Results are immutable and cache-friendly

```php
// Cache expensive operations
$qrCode = Cache::remember("qr:{$id}", 3600, fn() => 
    QrCode::size(400)
        ->format('png')
        ->errorCorrection('H')
        ->merge($logoPath, 0.2)
        ->generate($data)
);
```

## Creating Custom Data Types

Extend the package with your own data types following the Akira pattern:

### 1. Create a Value Object

```php
namespace App\ValueObjects;

final readonly class VCardData
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $phone
    ) {}
    
    public static function create(
        string $firstName,
        string $lastName,
        string $email,
        string $phone
    ): self {
        // Add validation here
        return new self($firstName, $lastName, $email, $phone);
    }
}
```

### 2. Create an Action

```php
namespace App\Actions;

use App\ValueObjects\VCardData;

final class BuildVCardStringAction
{
    public function handle(VCardData $data): string
    {
        return sprintf(
            "BEGIN:VCARD\nVERSION:3.0\nFN:%s %s\nEMAIL:%s\nTEL:%s\nEND:VCARD",
            $data->firstName,
            $data->lastName,
            $data->email,
            $data->phone
        );
    }
}
```

### 3. Create a DataType

```php
namespace App\DataTypes;

use Akira\QrCode\Contracts\QrCodeDataTypeContract;
use App\ValueObjects\VCardData;
use App\Actions\BuildVCardStringAction;

final readonly class VCardDataType implements QrCodeDataTypeContract
{
    public function __construct(
        private VCardData $data,
        private BuildVCardStringAction $action
    ) {}
    
    public static function fromValueObject(VCardData $data): self
    {
        return app(self::class, ['data' => $data]);
    }
    
    public function __toString(): string
    {
        return $this->action->handle($this->data);
    }
}
```

### 4. Use It

```php
$vcard = VCardData::create('John', 'Doe', 'john@example.com', '+1234567890');
$dataType = VCardDataType::fromValueObject($vcard);
$qrCode = QrCode::generate((string) $dataType);
```

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

When contributing, please follow the Akira pattern:
- Use Value Objects for data
- Use Actions with `handle()` method for logic
- Use DataTypes for orchestration via IoC
- Inject dependencies, never instantiate manually
- Maintain PHPStan Level 9 compliance


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Credits

- **Akira Team**
- Built with [BaconQrCode](https://github.com/Bacon/BaconQrCode)
- Inspired by clean architecture and SOLID principles

## Links

- [Documentation](https://github.com/akira-io/laravel-qrcode)
- [Issue Tracker](https://github.com/akira-io/laravel-qrcode/issues)
- [Changelog](CHANGELOG.md)
- [Contributing Guide](CONTRIBUTING.md)

---

Made with ❤️ by [Akira](https://akira-io.com)     
