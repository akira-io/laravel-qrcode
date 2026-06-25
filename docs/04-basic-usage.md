# Basic Usage

This guide covers the fundamental features of the Akira QR Code package.

## Simple Text QR Code

The most basic usage is generating a QR code from plain text:

```php
use Akira\QrCode\Facades\QrCode;

// Generate QR code
$qrCode = QrCode::generate('Hello World');

// In a Blade view
{!! QrCode::generate('Hello World') !!}

// In a controller
public function show()
{
    return QrCode::generate('Hello World');
}
```

## Setting Size

Control the size of your QR code (in pixels):

```php
// 300x300 pixels
$qrCode = QrCode::size(300)->generate('Large QR Code');

// 100x100 pixels
$qrCode = QrCode::size(100)->generate('Small QR Code');

// Default size is 200x200
$qrCode = QrCode::generate('Default size');
```

**Valid range:** 10 - 2000 pixels

## Setting Margin

Add white space (quiet zone) around your QR code:

```php
// 10 pixel margin
$qrCode = QrCode::margin(10)->generate('QR with margin');

// No margin (not recommended)
$qrCode = QrCode::margin(0)->generate('No margin');

// Default margin is 4
$qrCode = QrCode::generate('Default margin');
```

**Valid range:** 0 - 50 pixels

## Setting Colors

### Foreground Color

Change the color of the QR code modules:

```php
// Red QR code (RGB)
$qrCode = QrCode::color(255, 0, 0)->generate('Red QR Code');

// Green QR code
$qrCode = QrCode::color(0, 255, 0)->generate('Green QR Code');

// Blue QR code
$qrCode = QrCode::color(0, 0, 255)->generate('Blue QR Code');

// With alpha transparency (0-127, 0 = opaque)
$qrCode = QrCode::color(255, 0, 0, 50)->generate('Semi-transparent Red');
```

### Background Color

Change the background color:

```php
// Yellow background
$qrCode = QrCode::backgroundColor(255, 255, 0)->generate('Yellow Background');

// Light gray background
$qrCode = QrCode::backgroundColor(240, 240, 240)->generate('Gray Background');

// Combined with foreground color
$qrCode = QrCode::color(255, 0, 0)
    ->backgroundColor(255, 255, 255)
    ->generate('Red on Yellow');
```

**Format:** RGB or RGBA
- R, G, B: 0-255
- A: 0-127 (optional, 0 = opaque, 127 = transparent)

## Error Correction

Set the error correction level:

```php
// Low (7% correction)
$qrCode = QrCode::errorCorrection('L')->generate('Low correction');

// Medium (15% correction) - Default
$qrCode = QrCode::errorCorrection('M')->generate('Medium correction');

// Quartile (25% correction)
$qrCode = QrCode::errorCorrection('Q')->generate('Quartile correction');

// High (30% correction) - Best for logos
$qrCode = QrCode::errorCorrection('H')->generate('High correction');
```

## Output Formats

### SVG (Default)

Vector format, scalable without quality loss:

```php
$qrCode = QrCode::format('svg')->generate('SVG QR Code');
```

### PNG

Raster format, requires ext-imagick:

```php
$qrCode = QrCode::format('png')->generateRaw('PNG QR Code');
```

### EPS

Encapsulated PostScript for professional printing:

```php
$qrCode = QrCode::format('eps')->generate('EPS QR Code');
```

### WebP

Modern raster format, requires Imagick with WebP support:

```php
$qrCode = QrCode::format('webp')->generateRaw('WebP QR Code');
```

### PDF

Standalone PDF document, requires Imagick with PDF support:

```php
$qrCode = QrCode::format('pdf')->generateRaw('PDF QR Code');
```

## Combining Settings

Chain multiple methods together:

```php
$qrCode = QrCode::size(350)
    ->margin(10)
    ->color(100, 100, 100)
    ->backgroundColor(255, 255, 255)
    ->errorCorrection('H')
    ->format('png')
    ->generate('Customized QR Code');
```

## Saving to File

Save the generated QR code to a file:

```php
// Save as PNG
QrCode::format('png')
    ->size(400)
    ->generate('Hello', storage_path('qrcodes/hello.png'));

// Save as SVG
QrCode::format('svg')
    ->size(400)
    ->generate('Hello', storage_path('qrcodes/hello.svg'));

// With full path
$path = public_path('qrcodes/example.png');
QrCode::format('png')->generate('Example', $path);
```

## Usage in Blade Templates

### Direct Output

```blade
<div class="qr-container">
    {!! QrCode::generate('Hello World') !!}
</div>
```

### With Styling

```blade
<div style="text-align: center; padding: 20px; background: #f5f5f5;">
    <h2>Scan this QR Code</h2>
    {!! QrCode::size(300)
        ->margin(4)
        ->color(0, 0, 0)
        ->backgroundColor(255, 255, 255)
        ->generate('https://example.com') !!}
    <p>Visit our website</p>
</div>
```

### Base64 Embedded Image

```blade
@php
    $qrCode = QrCode::format('png')->size(300)->generateRaw('https://example.com');
    $base64 = base64_encode($qrCode);
@endphp

<img src="data:image/png;base64,{{ $base64 }}" alt="QR Code" class="qr-image">
```

### In Loops

```blade
@foreach($products as $product)
    <div class="product-card">
        <h3>{{ $product->name }}</h3>
        <div class="qr-code">
            {!! QrCode::size(150)->generate($product->url) !!}
        </div>
    </div>
@endforeach
```

## Usage in Controllers

### Return as Response

```php
use Akira\QrCode\Facades\QrCode;

public function show()
{
    return QrCode::size(300)->generate('Controller Response');
}
```

### Return as Image

```php
use Illuminate\Http\Response;

public function image(): Response
{
    $qrCode = QrCode::format('png')
        ->size(300)
        ->generate('https://example.com');
    
    return response($qrCode)
        ->header('Content-Type', 'image/png');
}
```

### Pass to View

```php
public function show(Product $product)
{
    $qrCode = QrCode::size(200)->generate($product->url);
    
    return view('products.show', [
        'product' => $product,
        'qrCode' => $qrCode
    ]);
}
```

### Download Response

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

### JSON API Response

```php
use Illuminate\Http\JsonResponse;

public function api(Request $request): JsonResponse
{
    $qrCode = QrCode::format('png')
        ->size(300)
        ->generateRaw($request->input('text'));
    
    return response()->json([
        'success' => true,
        'qrcode' => base64_encode($qrCode),
        'format' => 'png',
        'size' => 300,
    ]);
}
```

## Using Helper Function

The package provides a global helper:

```php
// Get QrCode instance
$qrcode = qrcode();

// Generate directly
$qrcode = qrcode('Hello, World!');

// With chaining
$qrcode = qrcode()->size(300)->color(255, 0, 0)->generate('Hello');
```

## Using Dependency Injection

Inject QrCode class into your controllers:

```php
use Akira\QrCode\QrCode;

class QrCodeController extends Controller
{
    public function __construct(
        private QrCode $qrCode
    ) {}
    
    public function generate(Request $request)
    {
        return $this->qrCode
            ->size(300)
            ->generate($request->input('text'));
    }
}
```

## Encoding

Set character encoding:

```php
// UTF-8 (default)
$qrCode = QrCode::encoding('UTF-8')->generate('Text with UTF-8');

// ISO-8859-1
$qrCode = QrCode::encoding('ISO-8859-1')->generate('Latin text');

// Shift_JIS (for Japanese)
$qrCode = QrCode::encoding('Shift_JIS')->generate('Japanese text');
```

## Best Practices

### Size Recommendations

- **Minimum:** 100px for basic scanning
- **Standard:** 200-300px for general use
- **Print:** 400-600px for high quality
- **Display:** 800-1000px for large screens

### Margin Guidelines

- **Minimum:** 0 (may affect scanning)
- **Recommended:** 4 (standard quiet zone)
- **Maximum:** 10 (extra spacing)

### Color Contrast

- Ensure high contrast between foreground and background
- Dark foreground on light background works best
- Avoid low-contrast combinations
- Test readability with multiple scanners

### Error Correction Selection

- **L (7%):** Simple text, no damage expected
- **M (15%):** Standard use cases (default)
- **Q (25%):** Potentially damaged codes
- **H (30%):** QR codes with logos or high damage risk

### Performance Tips

1. Use SVG format for better performance
2. Cache frequently generated QR codes
3. Use appropriate error correction level
4. Limit size to actual display requirements

## Common Patterns

### Caching QR Codes

The package caches generation output natively. Opt in per call with `cache()`, or enable it globally via `config('qrcode.cache')`:

```php
$qrCode = QrCode::format('png')
    ->size(300)
    ->cache(ttl: 3600)
    ->generate($text);
```

The cache key is derived from the text plus every styling option, so any change produces a distinct entry. Use `withoutCache()` to bypass the cache for a single call when caching is enabled globally. See [Advanced Features](07-advanced-features.md#caching-strategies) for details.

### Batch Generation

Generate many QR codes from one configured builder. Keys are preserved:

```php
$codes = QrCode::format('svg')
    ->size(200)
    ->batch([
        'home' => 'https://example.com',
        'docs' => 'https://example.com/docs',
    ]);

// Raw string output instead of HtmlString
$pngs = QrCode::format('png')->batchRaw(['a', 'b', 'c']);
```

### Storing QR Codes

```php
use Illuminate\Support\Facades\Storage;

$qrCode = QrCode::format('png')
    ->size(400)
    ->generateRaw($text);

Storage::disk('public')->put('qrcodes/example.png', $qrCode);
```

### Merging a Logo from String Content

When the logo is not on disk (for example fetched remotely or stored in the database), use `mergeString()`:

```php
$logo = Storage::disk('public')->get('logo.png');

$qrCode = QrCode::format('png')
    ->mergeString($logo, 0.25)
    ->generate($url);
```

### Responsive QR Codes

```blade
<div class="qr-container" style="max-width: 300px; width: 100%;">
    {!! QrCode::format('svg')->size(300)->generate($url) !!}
</div>

<style>
.qr-container svg {
    width: 100%;
    height: auto;
}
</style>
```

## Troubleshooting

**QR Code Not Scanning**
- Increase error correction level
- Ensure sufficient contrast
- Add margin around QR code
- Increase size

**GD Extension Error**
- Install php-gd extension
- Use SVG format instead of PNG
- Check PHP configuration

**Memory Issues**
- Reduce QR code size
- Use streaming for large files
- Increase PHP memory limit

## Complete Playground Examples

All examples from the package test routes:

### Basic Text QR Codes

```php
// Simple
QrCode::text('Hello World');

// With size
QrCode::size(300)->text('Large QR Code');

// With margin
QrCode::margin(5)->text('QR with Margin');

// Colored
QrCode::color(255, 0, 0)->text('Red QR Code');

// With background
QrCode::backgroundColor(255, 255, 0)->text('Yellow Background');
```

### Gradient Styles

```php
// Vertical
QrCode::gradient(255, 0, 0, 0, 0, 255, 'vertical')->text('Vertical');

// Horizontal
QrCode::gradient(255, 0, 0, 0, 255, 0, 'horizontal')->text('Horizontal');

// Diagonal
QrCode::gradient(255, 0, 255, 255, 255, 0, 'diagonal')->text('Diagonal');

// Radial
QrCode::gradient(0, 0, 255, 255, 255, 255, 'radial')->text('Radial');
```

### Module Styles

```php
// Square (default)
QrCode::style('square')->text('Square');

// Dots
QrCode::style('dot', 0.5)->text('Small Dots');
QrCode::style('dot', 0.7)->text('Medium Dots');

// Rounded
QrCode::style('round', 0.5)->text('Slightly Rounded');
QrCode::style('round', 0.8)->text('Very Rounded');
```

### Eye Customization

```php
// Square eyes
QrCode::eye('square')->text('Square Eyes');

// Circle eyes
QrCode::eye('circle')->text('Circle Eyes');

// Individual colored eyes
QrCode::eyeColor(0, 255, 0, 0, 0, 0, 0)->text('Red Eye 0');
QrCode::eyeColor(1, 0, 255, 0, 0, 0, 0)->text('Green Eye 1');
QrCode::eyeColor(2, 0, 0, 255, 0, 0, 0)->text('Blue Eye 2');

// All eyes different colors
QrCode::eyeColor(0, 255, 0, 0, 0, 0, 0)
    ->eyeColor(1, 0, 255, 0, 0, 0, 0)
    ->eyeColor(2, 0, 0, 255, 0, 0, 0)
    ->text('Rainbow Eyes');
```

### Error Correction Levels

```php
QrCode::errorCorrection('L')->text('Low (7%)');
QrCode::errorCorrection('M')->text('Medium (15%)');
QrCode::errorCorrection('Q')->text('Quartile (25%)');
QrCode::errorCorrection('H')->text('High (30%)');
```

### Combined Customization

```php
// Style combination
QrCode::gradient(255, 0, 0, 255, 255, 0, 'diagonal')
    ->backgroundColor(0, 0, 0)
    ->style('round', 0.7)
    ->text('Combined Styles');

// Complex design
QrCode::size(300)
    ->eye('circle')
    ->eyeColor(0, 255, 0, 0, 0, 0, 0)
    ->eyeColor(1, 0, 255, 0, 0, 0, 0)
    ->eyeColor(2, 0, 0, 255, 0, 0, 0)
    ->color(100, 100, 100)
    ->text('Complex Design');

// Full customization
QrCode::size(350)
    ->margin(2)
    ->gradient(138, 43, 226, 75, 0, 130, 'radial')
    ->backgroundColor(255, 255, 255)
    ->eye('circle')
    ->style('dot', 0.6)
    ->errorCorrection('H')
    ->text('Full Custom');
```

## Next Steps

- [Data Types](05-data-types.md) - Learn about specialized QR code types
- [Customization](06-customization.md) - Advanced styling options
- [Examples](08-examples.md) - Real-world usage examples
- [API Reference](10-api-reference.md) - Complete method reference

**Previous:** [Quick Start](03-quick-start.md) | **Next:** [Data Types](05-data-types.md)
