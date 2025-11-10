# API Reference

Complete reference for all methods and classes in the Akira QR Code package.

## QrCode Class

Main class for generating QR codes.

### Methods

#### size()

Set the size of the QR code in pixels.

```php
public function size(int $size): self
```

**Parameters:**
- `$size` (int): Size in pixels (10-2000)

**Returns:** Self for method chaining

**Example:**
```php
QrCode::size(300)->generate('text');
```

---

#### margin()

Set the margin (quiet zone) around the QR code.

```php
public function margin(int $margin): self
```

**Parameters:**
- `$margin` (int): Margin in pixels (0-50)

**Returns:** Self for method chaining

**Example:**
```php
QrCode::margin(10)->generate('text');
```

---

#### color()

Set the foreground color of QR code modules.

```php
public function color(int $red, int $green, int $blue, int $alpha = 0): self
```

**Parameters:**
- `$red` (int): Red component (0-255)
- `$green` (int): Green component (0-255)
- `$blue` (int): Blue component (0-255)
- `$alpha` (int, optional): Alpha transparency (0-127, 0 = opaque)

**Returns:** Self for method chaining

**Example:**
```php
QrCode::color(255, 0, 0)->generate('red');
QrCode::color(255, 0, 0, 50)->generate('semi-transparent');
```

---

#### backgroundColor()

Set the background color.

```php
public function backgroundColor(int $red, int $green, int $blue, int $alpha = 0): self
```

**Parameters:**
- `$red` (int): Red component (0-255)
- `$green` (int): Green component (0-255)
- `$blue` (int): Blue component (0-255)
- `$alpha` (int, optional): Alpha transparency (0-127)

**Returns:** Self for method chaining

**Example:**
```php
QrCode::backgroundColor(255, 255, 255)->generate('white bg');
```

---

#### gradient()

Apply gradient effect to QR code.

```php
public function gradient(
    int $startRed,
    int $startGreen,
    int $startBlue,
    int $endRed,
    int $endGreen,
    int $endBlue,
    string $type = 'VERTICAL'
): self
```

**Parameters:**
- `$startRed` (int): Start color red (0-255)
- `$startGreen` (int): Start color green (0-255)
- `$startBlue` (int): Start color blue (0-255)
- `$endRed` (int): End color red (0-255)
- `$endGreen` (int): End color green (0-255)
- `$endBlue` (int): End color blue (0-255)
- `$type` (string): Gradient type - 'VERTICAL', 'HORIZONTAL', 'DIAGONAL', 'RADIAL'

**Returns:** Self for method chaining

**Example:**
```php
QrCode::gradient(255, 0, 0, 0, 0, 255, 'VERTICAL')->generate('gradient');
```

---

#### style()

Set the module style.

```php
public function style(string $style, float $size = 0.5): self
```

**Parameters:**
- `$style` (string): Style type - 'square', 'dot', 'round'
- `$size` (float): Size factor (0.0-1.0)

**Returns:** Self for method chaining

**Example:**
```php
QrCode::style('dot', 0.7)->generate('dotted');
QrCode::style('round', 0.5)->generate('rounded');
```

---

#### eye()

Set the eye (position detection pattern) style.

```php
public function eye(string $style): self
```

**Parameters:**
- `$style` (string): Eye style - 'square', 'circle'

**Returns:** Self for method chaining

**Example:**
```php
QrCode::eye('circle')->generate('circle eyes');
```

---

#### eyeColor()

Set individual eye colors.

```php
public function eyeColor(
    int $eye,
    int $innerRed,
    int $innerGreen,
    int $innerBlue,
    ?int $outerRed = null,
    ?int $outerGreen = null,
    ?int $outerBlue = null
): self
```

**Parameters:**
- `$eye` (int): Eye index (0, 1, or 2)
- `$innerRed` (int): Inner square red (0-255)
- `$innerGreen` (int): Inner square green (0-255)
- `$innerBlue` (int): Inner square blue (0-255)
- `$outerRed` (int, optional): Outer square red (0-255)
- `$outerGreen` (int, optional): Outer square green (0-255)
- `$outerBlue` (int, optional): Outer square blue (0-255)

**Returns:** Self for method chaining

**Example:**
```php
QrCode::eyeColor(0, 255, 0, 0)->generate('red eye 0');
QrCode::eyeColor(1, 255, 0, 0, 0, 0, 255)->generate('red inner, blue outer');
```

---

#### errorCorrection()

Set error correction level.

```php
public function errorCorrection(string $level): self
```

**Parameters:**
- `$level` (string): Correction level - 'L', 'M', 'Q', 'H'
  - L: Low (7%)
  - M: Medium (15%)
  - Q: Quartile (25%)
  - H: High (30%)

**Returns:** Self for method chaining

**Example:**
```php
QrCode::errorCorrection('H')->generate('high correction');
```

---

#### encoding()

Set character encoding.

```php
public function encoding(string $encoding): self
```

**Parameters:**
- `$encoding` (string): Character encoding (e.g., 'UTF-8', 'ISO-8859-1')

**Returns:** Self for method chaining

**Example:**
```php
QrCode::encoding('UTF-8')->generate('text');
```

---

#### format()

Set output format.

```php
public function format(string $format): self
```

**Parameters:**
- `$format` (string): Output format - 'png', 'svg', 'eps'

**Returns:** Self for method chaining

**Example:**
```php
QrCode::format('png')->generate('png output');
```

---

#### merge()

Merge an image (logo) with the QR code.

```php
public function merge(string $filepath, float|int $percentage, bool $absolute = false): self
```

**Parameters:**
- `$filepath` (string): Path to image file
- `$percentage` (float|int): Size as percentage (0.0-0.5) or absolute pixels
- `$absolute` (bool): Whether to use absolute pixel size

**Returns:** Self for method chaining

**Requirements:** PNG format only, ext-gd extension

**Example:**
```php
QrCode::format('png')
    ->merge(public_path('logo.png'), 0.2)
    ->generate('with logo');
```

---

#### mergeString()

Merge an image from string content.

```php
public function mergeString(string $content, float|int $percentage, bool $absolute = false): self
```

**Parameters:**
- `$content` (string): Image file content
- `$percentage` (float|int): Size as percentage or absolute pixels
- `$absolute` (bool): Whether to use absolute pixel size

**Returns:** Self for method chaining

**Example:**
```php
$imageContent = file_get_contents('logo.png');
QrCode::format('png')
    ->mergeString($imageContent, 0.3)
    ->generate('with logo');
```

---

#### generate()

Generate the QR code.

```php
public function generate(string $text, ?string $filename = null): HtmlString|string
```

**Parameters:**
- `$text` (string): Text/data to encode
- `$filename` (string, optional): Path to save file

**Returns:**
- `HtmlString`: For SVG format (can be output in Blade)
- `string`: Raw binary data for PNG/EPS

**Example:**
```php
// Return for display
$qrCode = QrCode::generate('text');

// Save to file
QrCode::generate('text', storage_path('qr.png'));
```

---

## Value Objects

### WiFiData

```php
WiFiData::create(
    string $ssid,
    string $password,
    string $encryption = 'WPA',
    bool $hidden = false
): self
```

**Properties:**
- `ssid` (string): Network name
- `password` (string): Network password
- `encryption` (string): 'WPA', 'WEP', or 'nopass'
- `hidden` (bool): Whether network is hidden

---

### EmailData

```php
EmailData::create(
    string $email,
    ?string $subject = null,
    ?string $body = null
): self
```

**Properties:**
- `email` (string): Email address
- `subject` (string|null): Email subject
- `body` (string|null): Email body

---

### PhoneNumber

```php
PhoneNumber::create(string $number): self
```

**Properties:**
- `number` (string): Phone number in international format

---

### SMSData

```php
SMSData::create(
    string $phoneNumber,
    string $message
): self
```

**Properties:**
- `phoneNumber` (string): Recipient phone number
- `message` (string): SMS text

---

### GeoLocation

```php
GeoLocation::create(
    float $latitude,
    float $longitude
): self
```

**Properties:**
- `latitude` (float): Latitude coordinate
- `longitude` (float): Longitude coordinate

---

### BitcoinData

```php
BitcoinData::create(
    string $address,
    ?float $amount = null,
    ?string $label = null,
    ?string $message = null
): self
```

**Properties:**
- `address` (string): Bitcoin address
- `amount` (float|null): Amount in BTC
- `label` (string|null): Payment label
- `message` (string|null): Payment message

---

## DataTypes

### WiFiDataType

```php
WiFiDataType::fromValueObject(WiFiData $data): self
```

**Methods:**
- `__toString()`: Returns WiFi QR string

---

### EmailDataType

```php
EmailDataType::fromValueObject(EmailData $data): self
```

**Methods:**
- `__toString()`: Returns mailto: URL

---

### PhoneNumberDataType

```php
PhoneNumberDataType::fromValueObject(PhoneNumber $data): self
```

**Methods:**
- `__toString()`: Returns tel: URL

---

### SMSDataType

```php
SMSDataType::fromValueObject(SMSData $data): self
```

**Methods:**
- `__toString()`: Returns SMS URL

---

### GeoDataType

```php
GeoDataType::fromValueObject(GeoLocation $data): self
```

**Methods:**
- `__toString()`: Returns geo: URL

---

### BitcoinDataType

```php
BitcoinDataType::fromValueObject(BitcoinData $data): self
```

**Methods:**
- `__toString()`: Returns bitcoin: URL

---

## Facade

### QrCode Facade

```php
use Akira\QrCode\Facades\QrCode;
```

All methods from QrCode class are available through the facade.

**Example:**
```php
QrCode::size(300)->generate('text');
```

---

## Helper Function

### qrcode()

```php
qrcode(?string $text = null): QrCode|string
```

**Parameters:**
- `$text` (string|null): Optional text to generate immediately

**Returns:**
- `QrCode`: Instance if no text provided
- `string`: Generated QR code if text provided

**Examples:**
```php
// Get instance
$qr = qrcode();

// Generate directly
$qrCode = qrcode('Hello World');

// Chain methods
$qrCode = qrcode()->size(300)->generate('Hello');
```

---

## Contract

### QrCodeDataTypeContract

Interface for custom data types.

```php
interface QrCodeDataTypeContract
{
    public function __toString(): string;
}
```

**Methods:**
- `__toString()`: Convert data type to string for QR encoding

**Example Implementation:**
```php
class CustomDataType implements QrCodeDataTypeContract
{
    public function __toString(): string
    {
        return 'custom:data:string';
    }
}
```

---

## Configuration

### Config Keys

Access via `config('qrcode.key')`:

- `format` (string): Default format ('png', 'svg', 'eps')
- `size` (int): Default size in pixels
- `margin` (int): Default margin
- `color` (array): Default foreground color [R, G, B, A]
- `background_color` (array): Default background color [R, G, B, A]
- `error_correction` (string): Default error correction level
- `encoding` (string): Default character encoding
- `merge.percentage` (float): Default logo size percentage
- `merge.absolute` (bool): Use absolute logo size

---

## Service Provider

### QrCodeServiceProvider

Registers the package services.

**Published Resources:**
- Configuration: `php artisan vendor:publish --tag="qrcode-config"`

**Registered:**
- Singleton: `QrCode::class`
- Facade: `QrCode`

---

## Exceptions

### InvalidArgumentException

Thrown when invalid parameters are provided to Value Objects.

**Example:**
```php
try {
    WiFiData::create('', 'password'); // Empty SSID
} catch (InvalidArgumentException $e) {
    // Handle error
}
```

---

## Constants

### Error Correction Levels

- `ErrorCorrectionLevel::L` - Low (7%)
- `ErrorCorrectionLevel::M` - Medium (15%)
- `ErrorCorrectionLevel::Q` - Quartile (25%)
- `ErrorCorrectionLevel::H` - High (30%)

### Output Formats

- `svg` - Scalable Vector Graphics
- `png` - Portable Network Graphics (requires ext-gd)
- `eps` - Encapsulated PostScript

### Module Styles

- `square` - Square modules (default)
- `dot` - Circular dots
- `round` - Rounded squares

### Eye Styles

- `square` - Square eyes (default)
- `circle` - Circular eyes

### Gradient Types

- `VERTICAL` - Top to bottom
- `HORIZONTAL` - Left to right
- `DIAGONAL` - Corner to corner
- `RADIAL` - Center outward

---

## Next Steps

- [Examples](examples.md) - See these methods in action
- [Testing](testing.md) - Testing strategies
- [Advanced Features](advanced-features.md) - Complex usage
