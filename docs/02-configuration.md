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

## Cache Configuration

For production environments, consider caching generated QR codes:

```php
use Illuminate\Support\Facades\Cache;

$qrCode = Cache::remember('qrcode:' . md5($data), 3600, function () use ($data) {
    return QrCode::format('png')
        ->size(300)
        ->generate($data);
});
```

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
