# Customization

This guide covers all customization options available for styling QR codes.

## Size and Dimensions

### Setting Size

```php
use Akira\QrCode\Facades\QrCode;

// Small QR code
$qrCode = QrCode::size(100)->generate('Small');

// Medium QR code
$qrCode = QrCode::size(300)->generate('Medium');

// Large QR code
$qrCode = QrCode::size(600)->generate('Large');
```

**Valid range:** 10 - 2000 pixels

### Setting Margin

```php
// No margin
$qrCode = QrCode::margin(0)->generate('No margin');

// Small margin
$qrCode = QrCode::margin(4)->generate('Small margin');

// Large margin
$qrCode = QrCode::margin(20)->generate('Large margin');
```

**Valid range:** 0 - 50 pixels

## Colors

### Foreground Color

Set the color of QR code modules:

```php
// RGB format
$qrCode = QrCode::color(255, 0, 0)->generate('Red');

// RGBA format (with transparency)
$qrCode = QrCode::color(255, 0, 0, 50)->generate('Semi-transparent Red');
```

**Parameters:**
- R (0-255): Red component
- G (0-255): Green component  
- B (0-255): Blue component
- A (0-127, optional): Alpha transparency (0 = opaque)

**Examples:**

```php
// Black (default)
$qrCode = QrCode::color(0, 0, 0)->generate('Black');

// White
$qrCode = QrCode::color(255, 255, 255)->generate('White');

// Blue
$qrCode = QrCode::color(0, 102, 204)->generate('Blue');

// Green
$qrCode = QrCode::color(46, 204, 113)->generate('Green');

// Orange
$qrCode = QrCode::color(230, 126, 34)->generate('Orange');

// Purple
$qrCode = QrCode::color(155, 89, 182)->generate('Purple');
```

### Background Color

Set the background color:

```php
// White background (default)
$qrCode = QrCode::backgroundColor(255, 255, 255)->generate('White BG');

// Light gray background
$qrCode = QrCode::backgroundColor(240, 240, 240)->generate('Gray BG');

// Colored background
$qrCode = QrCode::backgroundColor(255, 235, 59)->generate('Yellow BG');

// Transparent background
$qrCode = QrCode::backgroundColor(255, 255, 255, 127)->generate('Transparent BG');
```

### Combined Colors

```php
$qrCode = QrCode::color(231, 76, 60)              // Red foreground
    ->backgroundColor(236, 240, 241)               // Light gray background
    ->generate('Styled QR');
```

## Gradients

Create gradient effects for QR codes:

```php
$qrCode = QrCode::gradient(
    startRed: 255,
    startGreen: 0,
    startBlue: 0,
    endRed: 0,
    endGreen: 0,
    endBlue: 255,
    type: 'VERTICAL'
)->generate('Gradient QR');
```

### Gradient Types

**Vertical Gradient:**
```php
$qrCode = QrCode::gradient(255, 0, 0, 0, 0, 255, 'VERTICAL')
    ->generate('Vertical');
```

**Horizontal Gradient:**
```php
$qrCode = QrCode::gradient(255, 0, 0, 0, 0, 255, 'HORIZONTAL')
    ->generate('Horizontal');
```

**Diagonal Gradient:**
```php
$qrCode = QrCode::gradient(255, 0, 0, 0, 0, 255, 'DIAGONAL')
    ->generate('Diagonal');
```

**Radial Gradient:**
```php
$qrCode = QrCode->gradient(255, 0, 0, 0, 0, 255, 'RADIAL')
    ->generate('Radial');
```

### Gradient Examples

**Blue to Purple:**
```php
$qrCode = QrCode::gradient(52, 152, 219, 155, 89, 182, 'VERTICAL')
    ->generate('Blue to Purple');
```

**Orange to Pink:**
```php
$qrCode = QrCode::gradient(230, 126, 34, 231, 76, 60, 'DIAGONAL')
    ->generate('Orange to Pink');
```

**Green to Blue:**
```php
$qrCode = QrCode::gradient(46, 204, 113, 52, 152, 219, 'HORIZONTAL')
    ->generate('Green to Blue');
```

## Module Styles

Control the appearance of individual QR code modules:

### Square Style (Default)

```php
$qrCode = QrCode::style('square')->generate('Square');
```

### Dot Style

```php
// Small dots
$qrCode = QrCode::style('dot', 0.5)->generate('Small Dots');

// Medium dots
$qrCode = QrCode::style('dot', 0.7)->generate('Medium Dots');

// Large dots (almost square)
$qrCode = QrCode::style('dot', 0.9)->generate('Large Dots');
```

**Size parameter:** 0.0 - 1.0 (percentage of module size)

### Rounded Style

```php
// Slightly rounded
$qrCode = QrCode::style('round', 0.3)->generate('Slightly Rounded');

// Very rounded
$qrCode = QrCode::style('round', 0.7)->generate('Very Rounded');

// Maximum rounded
$qrCode = QrCode::style('round', 1.0)->generate('Maximum Rounded');
```

**Size parameter:** 0.0 - 1.0 (radius factor)

## Eye Styles

Customize the position detection patterns (eyes):

### Square Eyes (Default)

```php
$qrCode = QrCode::eye('square')->generate('Square Eyes');
```

### Circle Eyes

```php
$qrCode = QrCode::eye('circle')->generate('Circle Eyes');
```

## Individual Eye Colors

Color each eye separately:

```php
// Color all three eyes differently
$qrCode = QrCode::eyeColor(0, 255, 0, 0)        // Eye 0: Red
    ->eyeColor(1, 0, 255, 0)                     // Eye 1: Green
    ->eyeColor(2, 0, 0, 255)                     // Eye 2: Blue
    ->generate('Colored Eyes');
```

### With Outer Eye Color

```php
// Eye with inner and outer colors
$qrCode = QrCode::eyeColor(
    eye: 0,
    innerRed: 255,
    innerGreen: 0,
    innerBlue: 0,
    outerRed: 0,
    outerGreen: 0,
    outerBlue: 255
)->generate('Dual Color Eye');
```

### All Three Eyes Custom

```php
$qrCode = QrCode::eyeColor(0, 231, 76, 60, 192, 57, 43)    // Red eye
    ->eyeColor(1, 46, 204, 113, 39, 174, 96)               // Green eye
    ->eyeColor(2, 52, 152, 219, 41, 128, 185)              // Blue eye
    ->generate('Three Color Eyes');
```

## Error Correction

Higher error correction allows for more damage/obscuring:

```php
// Low (7% correction)
$qrCode = QrCode::errorCorrection('L')->generate('Low');

// Medium (15% correction) - Default
$qrCode = QrCode::errorCorrection('M')->generate('Medium');

// Quartile (25% correction)
$qrCode = QrCode::errorCorrection('Q')->generate('Quartile');

// High (30% correction) - Best for logos
$qrCode = QrCode::errorCorrection('H')->generate('High');
```

## Image Merging

Merge a logo or image with the QR code:

### Merge from File

```php
$qrCode = QrCode::format('png')
    ->size(500)
    ->errorCorrection('H')                           // High correction needed
    ->merge(public_path('images/logo.png'), 0.2)     // 20% of QR size
    ->generate('QR with Logo');
```

### Merge from String

```php
$imageContent = file_get_contents(public_path('images/logo.png'));

$qrCode = QrCode::format('png')
    ->errorCorrection('H')
    ->mergeString($imageContent, 0.3)                // 30% of QR size
    ->generate('QR with Logo');
```

### Absolute Size

```php
// Use absolute pixel size for logo
$qrCode = QrCode::format('png')
    ->size(500)
    ->errorCorrection('H')
    ->merge(public_path('images/logo.png'), 100, true)  // 100px, absolute
    ->generate('QR with Logo');
```

**Important:** Image merging only works with PNG format.

### Best Practices for Image Merging

1. Use error correction level 'H' (30%)
2. Logo should be 15-30% of QR code size
3. Logo should have transparent background
4. Use simple, high-contrast logos
5. Test scanning from various distances

## Output Formats

### SVG (Vector)

```php
$qrCode = QrCode::format('svg')
    ->size(400)
    ->generate('SVG QR');
```

**Advantages:**
- Scalable without quality loss
- Smaller file size
- No GD extension required

### PNG (Raster)

```php
$qrCode = QrCode::format('png')
    ->size(400)
    ->generate('PNG QR');
```

**Advantages:**
- Universal compatibility
- Supports image merging
- Direct image embedding

**Requirements:** ext-gd extension

### EPS (PostScript)

```php
$qrCode = QrCode::format('eps')
    ->size(400)
    ->generate('EPS QR');
```

**Advantages:**
- Professional printing
- Vector format
- Design software compatible

## Combined Customization

### Corporate Branding

```php
$qrCode = QrCode::size(500)
    ->margin(10)
    ->color(0, 102, 204)                           // Corporate blue
    ->backgroundColor(255, 255, 255)
    ->errorCorrection('H')
    ->style('round', 0.5)
    ->eye('circle')
    ->format('png')
    ->merge(public_path('images/logo.png'), 0.2)
    ->generate('https://company.com');
```

### Artistic Design

```php
$qrCode = QrCode::size(600)
    ->margin(20)
    ->gradient(255, 0, 128, 128, 0, 255, 'RADIAL')
    ->style('dot', 0.8)
    ->eye('circle')
    ->eyeColor(0, 255, 255, 0)                     // Yellow
    ->eyeColor(1, 0, 255, 255)                     // Cyan
    ->eyeColor(2, 255, 0, 255)                     // Magenta
    ->format('svg')
    ->generate('Artistic QR');
```

### Minimalist Design

```php
$qrCode = QrCode::size(300)
    ->margin(15)
    ->color(50, 50, 50)
    ->backgroundColor(250, 250, 250)
    ->errorCorrection('M')
    ->format('svg')
    ->generate('Minimalist QR');
```

### High-Contrast Print

```php
$qrCode = QrCode::size(800)
    ->margin(40)
    ->color(0, 0, 0)
    ->backgroundColor(255, 255, 255)
    ->errorCorrection('H')
    ->format('png')
    ->generate('Print QR');
```

## Encoding

Set character encoding for special characters:

```php
// UTF-8 (default)
$qrCode = QrCode::encoding('UTF-8')->generate('UTF-8 Text');

// ISO-8859-1 (Latin)
$qrCode = QrCode::encoding('ISO-8859-1')->generate('Latin Text');

// Shift_JIS (Japanese)
$qrCode = QrCode::encoding('Shift_JIS')->generate('日本語');
```

## Color Presets

Common color combinations:

```php
// Dark mode
$qrCode = QrCode::color(255, 255, 255)
    ->backgroundColor(30, 30, 30)
    ->generate('Dark Mode');

// Material Design
$qrCode = QrCode::color(33, 150, 243)
    ->backgroundColor(255, 255, 255)
    ->generate('Material Design');

// Monochrome
$qrCode = QrCode::color(0, 0, 0)
    ->backgroundColor(255, 255, 255)
    ->generate('Monochrome');

// Sepia
$qrCode = QrCode::color(101, 67, 33)
    ->backgroundColor(255, 249, 242)
    ->generate('Sepia');
```

## Responsive Design

For responsive QR codes in web pages:

```blade
<div class="qr-wrapper" style="max-width: 300px; width: 100%;">
    {!! QrCode::format('svg')->size(300)->generate($url) !!}
</div>

<style>
.qr-wrapper svg {
    width: 100%;
    height: auto;
    display: block;
}
</style>
```

## Performance Tips

1. **Use SVG for web** - Faster rendering, smaller size
2. **Use PNG for emails** - Better compatibility
3. **Cache styled QR codes** - Expensive to generate
4. **Optimize logo images** - Compress before merging
5. **Limit gradient complexity** - For better scanning

## Best Practices

### Contrast Guidelines

- Minimum contrast ratio: 3:1
- Recommended contrast ratio: 7:1
- Test with color blindness simulators

### Size Guidelines

- Web display: 200-400px
- Print: 600-1000px  
- Business cards: 400-600px
- Posters: 1000-2000px

### Margin Guidelines

- Minimum: 0 (may affect scanning)
- Standard: 4 (quiet zone)
- Print: 10-20 (extra safety)

## Troubleshooting

**QR Code Not Scanning with Custom Colors:**
- Increase contrast
- Use darker foreground
- Test with multiple scanners

**Logo Makes QR Code Unscannable:**
- Reduce logo size
- Increase error correction to H
- Use simpler logo design

**Gradient QR Codes Not Scanning:**
- Reduce gradient range
- Ensure sufficient contrast throughout
- Test with multiple devices

## Next Steps

- [Advanced Features](07-advanced-features.md) - Image merging and custom types
- [Examples](08-examples.md) - Real-world customization examples
- [API Reference](10-api-reference.md) - Complete method reference

**Previous:** [Data Types](05-data-types.md) | **Next:** [Advanced Features](07-advanced-features.md)
