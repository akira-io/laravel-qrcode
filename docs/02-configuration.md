# Configuration

## Configuration File

The configuration file is located at `config/qrcode.php`. Publish it using:

```bash
php artisan vendor:publish --tag="qrcode-config"
```

## Configuration Options

### Default Data

The default data used when generating a QR code without explicitly providing data:

```php
'default_data' => env('QR_CODE_DEFAULT_DATA', ''),
```

### Format

Controls the default output format for QR codes.

```php
'format' => env('QR_CODE_FORMAT', 'png'),
```

**Supported formats:**
- `png` - PNG image format (requires ext-imagick)
- `svg` - SVG vector format
- `eps` - Encapsulated PostScript format
- `webp` - WebP image format (requires Imagick WebP support)
- `pdf` - PDF document format (requires Imagick PDF support)

### Size

Default size of the QR code in pixels:

```php
'size' => (int) (env('QR_CODE_SIZE') ?? 200),
```

**Valid range:** 10 - 2000 pixels

### Margin

Default margin around the QR code:

```php
'margin' => (int) (env('QR_CODE_MARGIN') ?? 4),
```

**Valid range:** 0 - 50 pixels

### Color

Default foreground color of the QR code modules in RGBA format:

```php
'color' => [
    (int) (env('QR_CODE_COLOR_R') ?? 0),
    (int) (env('QR_CODE_COLOR_G') ?? 0),
    (int) (env('QR_CODE_COLOR_B') ?? 0),
    (int) (env('QR_CODE_COLOR_A') ?? 0),
],
```

**Format:** `[R, G, B, A]`
- R, G, B: 0-255 (Red, Green, Blue)
- A: 0-127 (Alpha transparency, 0 = opaque, 127 = transparent)

### Background Color

Default background color in RGBA format:

```php
'background_color' => [
    (int) (env('QR_CODE_BACKGROUND_COLOR_R') ?? 255),
    (int) (env('QR_CODE_BACKGROUND_COLOR_G') ?? 255),
    (int) (env('QR_CODE_BACKGROUND_COLOR_B') ?? 255),
    (int) (env('QR_CODE_BACKGROUND_COLOR_A') ?? 0),
],
```

### Error Correction Level

Controls the error correction capability of the QR code:

```php
'error_correction' => env('QR_CODE_ERROR_CORRECTION', 'H'),
```

**Available levels:**
- `L` - Low (7% correction capability)
- `M` - Medium (15% correction capability)
- `Q` - Quartile (25% correction capability)
- `H` - High (30% correction capability) - Recommended for QR codes with logos

### Encoding

Character encoding for the QR code data:

```php
'encoding' => env('QR_CODE_ENCODING', 'UTF-8'),
```

**Common encodings:**
- `UTF-8` (recommended)
- `ISO-8859-1`
- `Shift_JIS`

### Merge Options

Options for merging images (logos) with QR codes:

```php
'merge' => [
    'percentage' => env('QR_CODE_MERGE_PERCENTAGE', 0.2),
    'absolute' => env('QR_CODE_MERGE_ABSOLUTE', false),
],
```

- `percentage` - Logo size as percentage of QR code (0.0 - 1.0)
- `absolute` - Treat the image path as an absolute path

### Cache Options

Controls cache-backed QR code generation. When enabled, `generate()` and `generateRaw()` store their output in Laravel's cache keyed by the full builder configuration, so identical requests are served without re-rendering.

```php
'cache' => [
    'enabled' => filter_var(env('QR_CODE_CACHE_ENABLED', false), FILTER_VALIDATE_BOOL),
    'ttl' => (int) env('QR_CODE_CACHE_TTL', 3600),
    'prefix' => env('QR_CODE_CACHE_PREFIX', 'qrcode'),
],
```

- `enabled` - When `true`, every generation is cached by default (boolean, default `false`)
- `ttl` - Cache lifetime in seconds (default `3600`)
- `prefix` - Prefix for the generated cache keys (default `qrcode`)

Caching only applies when generating in-memory output. Writes to a file path (`generate($text, $filename)`) always bypass the cache. The cache can also be toggled per call with the `cache()` and `withoutCache()` builder methods - see [Advanced Features](07-advanced-features.md#caching-strategies).

## Environment Variables

Configure defaults in your `.env` file:

```env
# Format and basic settings
QR_CODE_FORMAT=png
QR_CODE_SIZE=200
QR_CODE_MARGIN=4

# Foreground color (Black)
QR_CODE_COLOR_R=0
QR_CODE_COLOR_G=0
QR_CODE_COLOR_B=0
QR_CODE_COLOR_A=0

# Background color (White)
QR_CODE_BACKGROUND_COLOR_R=255
QR_CODE_BACKGROUND_COLOR_G=255
QR_CODE_BACKGROUND_COLOR_B=255
QR_CODE_BACKGROUND_COLOR_A=0

# Error correction and encoding
QR_CODE_ERROR_CORRECTION=H
QR_CODE_ENCODING=UTF-8

# Image merging
QR_CODE_MERGE_PERCENTAGE=0.2
QR_CODE_MERGE_ABSOLUTE=false

# Caching
QR_CODE_CACHE_ENABLED=false
QR_CODE_CACHE_TTL=3600
QR_CODE_CACHE_PREFIX=qrcode
```

## Runtime Configuration

You can override configuration at runtime using method chaining:

```php
use Akira\QrCode\Facades\QrCode;

$qrCode = QrCode::size(300)
    ->margin(10)
    ->color(255, 0, 0)
    ->backgroundColor(255, 255, 255)
    ->errorCorrection('H')
    ->format('png')
    ->generate('Custom QR Code');
```

## Configuration Best Practices

**Size Recommendations**
- Minimum: 100px for basic scanning
- Standard: 200-300px for general use
- High-quality: 400-600px for print
- Maximum: 1000px+ for large displays

**Margin Guidelines**
- Minimum: 0 (no quiet zone, may affect scanning)
- Recommended: 4 (standard quiet zone)
- Maximum: 10 (extra spacing)

**Color Contrast**
- Ensure high contrast between foreground and background
- Dark foreground on light background works best
- Avoid low-contrast combinations (e.g., yellow on white)

**Error Correction Selection**
- L (7%): Simple text, no damage expected
- M (15%): Standard use cases
- Q (25%): When QR codes might be partially obscured
- H (30%): For QR codes with logos or high damage risk

**Format Selection**
- PNG: For web display, email, and raster graphics
- SVG: For scaling, print, and vector graphics
- EPS: For professional printing and design software
- WebP: For modern web delivery when browser support is acceptable
- PDF: For document workflows that need a standalone QR code page

## Cache Configuration

The package ships with built-in caching. Enable it globally via the `cache` config block (or the `QR_CODE_CACHE_*` env vars above), or opt in per call:

```php
use Akira\QrCode\Facades\QrCode;

// Per-call: cache this result for one hour under the "reports" prefix
$svg = QrCode::format('svg')
    ->size(300)
    ->cache(ttl: 3600, prefix: 'reports')
    ->generate($data);

// Force a fresh render even when caching is enabled globally
$fresh = QrCode::cache()->withoutCache()->generate($data);
```

The cache key is derived from the text plus every styling option (size, margin, colors, gradient, eye styling, merged logo, ...), so changing any option produces a distinct cached entry. Inspect the computed key with `QrCode::cacheKeyFor($text)`. See [Advanced Features](07-advanced-features.md#caching-strategies) for batch caching and key details.

## Performance Tips

1. Use SVG format for better performance (no GD processing)
2. Cache frequently generated QR codes
3. Use appropriate error correction level (don't always use H)
4. Limit size to actual display requirements
5. Store generated QR codes in storage for reuse

## Next Steps

- [Quick Start](03-quick-start.md) - Get started quickly
- [Basic Usage](04-basic-usage.md) - Learn the fundamentals
- [Customization](06-customization.md) - Advanced customization options

**Previous:** [Installation](01-installation.md) | **Next:** [Quick Start](03-quick-start.md)
