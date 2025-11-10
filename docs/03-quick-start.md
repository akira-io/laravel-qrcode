# Quick Start

## Installation

```bash
composer require akira/laravel-qrcode
```

## Basic Example

```php
use Akira\QrCode\Facades\QrCode;

// Generate a simple QR code
$qrCode = QrCode::generate('Hello, World!');

// Display in Blade template
{!! $qrCode !!}
```

## Common Use Cases

### 1. Website URL

```php
$qrCode = QrCode::size(300)
    ->errorCorrection('M')
    ->generate('https://example.com');
```

### 2. WiFi Network

```php
$qrCode = QrCode::size(300)->wifi([
    'ssid' => 'MyNetwork',
    'password' => 'SecurePassword123'
]);
```

### 3. Email Address

```php
$qrCode = QrCode::email(
    'contact@example.com',
    'Hello',
    'Message content'
);
```

### 4. Phone Number

```php
$qrCode = QrCode::phone('+1234567890');
```

### 5. Custom Styled QR Code

```php
$qrCode = QrCode::size(400)
    ->color(255, 0, 0)                    // Red foreground
    ->backgroundColor(255, 255, 255)       // White background
    ->margin(10)                           // 10px margin
    ->errorCorrection('H')                 // High error correction
    ->format('png')                        // PNG format
    ->generate('Styled QR Code');
```

### 6. QR Code with Logo

```php
$qrCode = QrCode::format('png')
    ->size(500)
    ->errorCorrection('H')                 // High correction needed
    ->merge(public_path('images/logo.png'), 0.2)  // 20% logo size
    ->generate('https://example.com');
```

## Using in Routes

```php
use Akira\QrCode\Facades\QrCode;

Route::get('/qrcode', function () {
    return QrCode::generate('Hello from Laravel!');
});
```

## Using in Controllers

```php
use Akira\QrCode\Facades\QrCode;
use Illuminate\Http\Response;

class QrCodeController extends Controller
{
    public function generate(Request $request): Response
    {
        $qrCode = QrCode::format('png')
            ->size(300)
            ->generate($request->input('text'));
        
        return response($qrCode)
            ->header('Content-Type', 'image/png');
    }
}
```

## Using in Blade Templates

### Direct Output

```blade
<div class="qr-container">
    {!! QrCode::size(300)->generate('https://example.com') !!}
</div>
```

### Base64 Embedded Image

```blade
@php
    $qrCode = QrCode::format('png')->size(300)->generate('https://example.com');
    $base64 = base64_encode($qrCode);
@endphp

<img src="data:image/png;base64,{{ $base64 }}" alt="QR Code">
```

### Pass from Controller

Controller:
```php
public function show()
{
    $qrCode = QrCode::size(300)->generate('https://example.com');
    return view('qrcode', compact('qrCode'));
}
```

Blade:
```blade
<div class="qr-container">
    {!! $qrCode !!}
</div>
```

## Using Helper Function

```php
// Get QrCode instance
$qrcode = qrcode();

// Generate directly
$qrcode = qrcode('Hello, World!');

// With chaining
$qrcode = qrcode()->size(300)->generate('Hello');
```

## Saving to File

```php
// Save as PNG
QrCode::format('png')
    ->size(400)
    ->generate('Save to file', storage_path('qrcodes/example.png'));

// Save as SVG
QrCode::format('svg')
    ->size(400)
    ->generate('Save to file', storage_path('qrcodes/example.svg'));
```

## Download Response

```php
use Illuminate\Http\Response;

public function download(Request $request): Response
{
    $qrCode = QrCode::format('png')
        ->size(500)
        ->generate($request->input('text'));
    
    return response($qrCode)
        ->header('Content-Type', 'image/png')
        ->header('Content-Disposition', 'attachment; filename="qrcode.png"');
}
```

## JSON API Response

```php
use Illuminate\Http\JsonResponse;

public function api(Request $request): JsonResponse
{
    $qrCode = QrCode::format('png')
        ->size(300)
        ->generate($request->input('text'));
    
    return response()->json([
        'success' => true,
        'qrcode' => base64_encode($qrCode),
        'format' => 'png',
        'size' => 300,
    ]);
}
```

## Caching QR Codes

```php
use Illuminate\Support\Facades\Cache;

$qrCode = Cache::remember('qr:' . md5($text), 3600, function () use ($text) {
    return QrCode::format('png')
        ->size(300)
        ->generate($text);
});
```

## Next Steps

- [Architecture](architecture.md) - Understand the package structure
- [Basic Usage](basic-usage.md) - Detailed usage guide
- [Data Types](data-types.md) - All supported data types
- [Customization](customization.md) - Styling and customization options
- [Examples](examples.md) - More real-world examples
