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
    string $type
): self
```

**Parameters:**
- `$startRed` (int): Start color red (0-255)
- `$startGreen` (int): Start color green (0-255)
- `$startBlue` (int): Start color blue (0-255)
- `$endRed` (int): End color red (0-255)
- `$endGreen` (int): End color green (0-255)
- `$endBlue` (int): End color blue (0-255)
- `$type` (string): Gradient type - required. One of 'VERTICAL', 'HORIZONTAL', 'DIAGONAL', 'RADIAL' (case-insensitive; normalized to uppercase internally)

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
    int $eyeNumber,
    int $innerRed,
    int $innerGreen,
    int $innerBlue,
    int $outterRed = 0,
    int $outterGreen = 0,
    int $outterBlue = 0
): self
```

**Parameters:**
- `$eyeNumber` (int): Eye index (0, 1, or 2)
- `$innerRed` (int): Inner square red (0-255)
- `$innerGreen` (int): Inner square green (0-255)
- `$innerBlue` (int): Inner square blue (0-255)
- `$outterRed` (int, default 0): Outer square red (0-255)
- `$outterGreen` (int, default 0): Outer square green (0-255)
- `$outterBlue` (int, default 0): Outer square blue (0-255)

> Note: the outer-color parameters are spelled `outter*` in the current implementation.

**Returns:** Self for method chaining

**Example:**
```php
QrCode::eyeColor(0, 255, 0, 0)->generate('red eye 0');
QrCode::eyeColor(1, 255, 0, 0, 0, 0, 255)->generate('red inner, blue outer');
```

---

#### createColor()

Build a Bacon color instance from RGBA components. Useful when constructing colors for lower-level rendering or tests.

```php
public function createColor(int $red, int $green, int $blue, ?int $alpha = null): \BaconQrCode\Renderer\Color\ColorInterface
```

**Parameters:**
- `$red` (int): Red component (0-255)
- `$green` (int): Green component (0-255)
- `$blue` (int): Blue component (0-255)
- `$alpha` (int|null, optional): Alpha transparency (0-127)

**Returns:** A `BaconQrCode\Renderer\Color\ColorInterface` (an `Rgb` or `Alpha` color)

**Example:**
```php
$color = qrcode()->createColor(255, 0, 0);
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
- `$format` (string): Output format - 'png', 'svg', 'eps', 'webp', 'pdf'

**Returns:** Self for method chaining

**Example:**
```php
QrCode::format('png')->generate('png output');
```

---

#### merge()

Merge an image (logo) with the QR code.

```php
public function merge(string $filepath, ?float $percentage = null, bool $absolute = false): self
```

**Parameters:**
- `$filepath` (string): Path to image file
- `$percentage` (float|null): Logo size as a percentage from 0.0 to 1.0. Uses config when null
- `$absolute` (bool): Whether the image path is already absolute

**Returns:** Self for method chaining

**Requirements:** PNG format for merging, ext-gd for image merging, and ext-imagick for PNG output

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
public function mergeString(string $content, ?float $percentage = null): self
```

**Parameters:**
- `$content` (string): Image file content
- `$percentage` (float|null): Logo size as a percentage. Uses config when null

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
public function generate(string $text, ?string $filename = null): HtmlString|string|null
```

**Parameters:**
- `$text` (string): Text/data to encode
- `$filename` (string, optional): Path to save file

**Returns:**
- `HtmlString`: Display-ready output for inline formats (SVG)
- `string`: Raw output for binary formats (PNG, WebP, PDF, EPS)
- `null`: When saving to a file

**Example:**
```php
// Return for display
$qrCode = QrCode::generate('text');

// Save to file
QrCode::generate('text', storage_path('qr.png'));
```

---

#### generateRaw()

Generate raw QR code output for responses, downloads, storage, and base64 encoding.

```php
public function generateRaw(string $text, ?string $filename = null): ?string
```

**Parameters:**
- `$text` (string): Text/data to encode
- `$filename` (string, optional): Path to save file

**Returns:**
- `string`: Raw SVG, PNG, WebP, PDF, or EPS output
- `null`: When saving to a file

**Example:**
```php
$png = QrCode::format('png')->generateRaw('text');
```

---

#### cache()

Enable cache-backed generation for this builder. Output is stored in Laravel's cache keyed by the full configuration.

```php
public function cache(?int $ttl = null, ?string $prefix = null): self
```

**Parameters:**
- `$ttl` (int|null, optional): Cache lifetime in seconds. Falls back to the configured TTL when null. Must be greater than 0 or an `InvalidArgumentException` is thrown
- `$prefix` (string|null, optional): Cache key prefix. Falls back to the configured prefix when null

**Returns:** Self for method chaining

**Example:**
```php
QrCode::cache(ttl: 3600, prefix: 'reports')->generate('text');
```

---

#### withoutCache()

Disable caching for this builder, even when caching is enabled globally in config.

```php
public function withoutCache(): self
```

**Returns:** Self for method chaining

**Example:**
```php
QrCode::cache()->withoutCache()->generate('always fresh');
```

---

#### cacheKeyFor()

Return the cache key that would be used for the given text under the current configuration.

```php
public function cacheKeyFor(string $text): string
```

**Parameters:**
- `$text` (string): Text/data that would be encoded

**Returns:** The fully-qualified cache key string (`{prefix}:{sha256}`)

**Example:**
```php
$key = QrCode::size(300)->cacheKeyFor('text');
```

---

#### batch()

Generate display-ready output for many payloads using the same configured builder.

```php
public function batch(iterable $texts): \Illuminate\Support\Collection
```

**Parameters:**
- `$texts` (iterable<int|string, string>): The payloads to encode

**Returns:** `Collection<int|string, HtmlString|string|null>`, preserving input keys

**Example:**
```php
$codes = QrCode::format('svg')->size(200)->batch([
    'home' => 'https://example.com',
    'docs' => 'https://example.com/docs',
]);
```

---

#### batchRaw()

Same as `batch()` but returns raw string output for each payload.

```php
public function batchRaw(iterable $texts): \Illuminate\Support\Collection
```

**Parameters:**
- `$texts` (iterable<int|string, string>): The payloads to encode

**Returns:** `Collection<int|string, string|null>`

**Example:**
```php
$pngs = QrCode::format('png')->batchRaw(['a', 'b', 'c']);
```

---

## Value Objects

### WiFiData

```php
WiFiData::create(
    string $ssid,
    ?string $password = null,
    bool $hidden = false,
    string $encryption = 'WPA'
): self
```

**Properties:**
- `ssid` (string): Network name (cannot be empty)
- `password` (string|null): Network password
- `hidden` (bool): Whether network is hidden
- `encryption` (string): 'WPA', 'WEP', or 'nopass' (normalized at construction)

---

### EmailData

```php
EmailData::create(
    string $address,
    ?string $subject = null,
    ?string $body = null,
    ?string $cc = null,
    ?string $bcc = null
): self
```

**Properties:**
- `address` (string): Email address
- `subject` (string|null): Email subject
- `body` (string|null): Email body
- `cc` (string|null): Carbon copy address
- `bcc` (string|null): Blind carbon copy address

---

### PhoneNumber

```php
PhoneNumber::fromString(string $number): self
```

**Properties:**
- `number` (string): Phone number in international format

---

### SMSData

```php
SMSData::create(
    string $phoneNumber,
    ?string $message = null
): self
```

**Properties:**
- `phoneNumber` (string): Recipient phone number
- `message` (string|null): SMS text

---

### GeoLocation

```php
GeoLocation::create(
    float $latitude,
    float $longitude,
    ?string $name = null
): self
```

**Properties:**
- `latitude` (float): Latitude coordinate
- `longitude` (float): Longitude coordinate
- `name` (string|null): Optional location label

---

### BitcoinData

```php
BitcoinData::create(
    string $address,
    ?float $amount = null,
    ?string $label = null,
    ?string $message = null,
    ?string $returnAddress = null
): self
```

**Properties:**
- `address` (string): Bitcoin address
- `amount` (float|null): Amount in BTC
- `label` (string|null): Payment label
- `message` (string|null): Payment message
- `returnAddress` (string|null): Return address

---

### EthereumData

```php
EthereumData::create(
    string $address,
    ?string $value = null,
    ?int $chainId = null,
    ?string $label = null,
    ?string $message = null
): self
```

**Properties:**
- `address` (string): Ethereum address
- `value` (string|null): Amount in wei (string to preserve precision)
- `chainId` (int|null): EIP-155 chain id
- `label` (string|null): Payment label
- `message` (string|null): Payment message

---

### LitecoinData

```php
LitecoinData::create(
    string $address,
    float $amount = 0.0,
    ?string $label = null,
    ?string $message = null
): self
```

**Properties:**
- `address` (string): Litecoin address
- `amount` (float): Amount in LTC
- `label` (string|null): Payment label
- `message` (string|null): Payment message

---

### VCardData

```php
VCardData::create(
    string $fullName,
    ?string $firstName = null,
    ?string $lastName = null,
    ?string $organization = null,
    ?string $title = null,
    ?string $phone = null,
    ?string $email = null,
    ?string $url = null,
    ?string $address = null,
    ?string $note = null
): self
```

**Properties:** `fullName` is required; all other fields are optional. `email` and `url` are validated when provided.

---

### CalendarEventData

```php
CalendarEventData::create(
    string $summary,
    DateTimeInterface $startsAt,
    DateTimeInterface $endsAt,
    ?string $location = null,
    ?string $description = null,
    ?string $uniqueId = null,
    ?DateTimeInterface $timestamp = null
): self
```

**Properties:** `summary` is required and `endsAt` must be after `startsAt`. `timestamp` defaults to the current time when null.

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

### EthereumDataType

```php
EthereumDataType::fromValueObject(EthereumData $data): self
```

**Methods:**
- `__toString()`: Returns ethereum: URI

---

### LitecoinDataType

```php
LitecoinDataType::fromValueObject(LitecoinData $data): self
```

**Methods:**
- `__toString()`: Returns litecoin: URI

---

### VCardDataType

```php
VCardDataType::fromValueObject(VCardData $data): self
```

**Methods:**
- `__toString()`: Returns the vCard text block

---

### CalendarEventDataType

```php
CalendarEventDataType::fromValueObject(CalendarEventData $data): self
```

**Methods:**
- `__toString()`: Returns the iCalendar (VEVENT) text block

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
qrcode(?string $text = null): QrCode|HtmlString|string|null
```

**Parameters:**
- `$text` (string|null): Optional text to generate immediately

**Returns:**
- `QrCode`: Instance when no text is provided
- `HtmlString|string|null`: The generated QR code when text is provided (an `HtmlString` for SVG, a raw `string` for binary formats)

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
interface QrCodeDataTypeContract extends Stringable {}
```

The contract adds nothing beyond PHP's `Stringable`, so implementations only need a `__toString()` method that returns the encoded payload.

**Example Implementation:**
```php
final readonly class CustomDataType implements QrCodeDataTypeContract
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

- `default_data` (string): Fallback payload when none is provided
- `format` (string): Default format ('png', 'svg', 'eps', 'webp', 'pdf')
- `size` (int): Default size in pixels
- `margin` (int): Default margin
- `color` (array): Default foreground color [R, G, B, A]
- `background_color` (array): Default background color [R, G, B, A]
- `error_correction` (string): Default error correction level
- `encoding` (string): Default character encoding
- `merge.percentage` (float): Default logo size percentage
- `merge.absolute` (bool): Use absolute logo path
- `cache.enabled` (bool): Cache generation output by default
- `cache.ttl` (int): Cache lifetime in seconds
- `cache.prefix` (string): Cache key prefix

---

## Service Provider

### QrCodeServiceProvider

Registers the package services.

**Published Resources:**
- Configuration: `php artisan vendor:publish --tag="qrcode-config"`

**Registered:**
- Container binding: `qrcode` resolves a fresh `QrCode` instance
- Facade: `QrCode` (accessor clears the resolved instance on each call)
- Artisan command: `qrcode:generate` (see [CLI](14-cli.md))

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
- `png` - Portable Network Graphics (requires ext-imagick)
- `eps` - Encapsulated PostScript
- `webp` - WebP image (requires Imagick WebP support)
- `pdf` - PDF document (requires Imagick PDF support)

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

- [Examples](08-examples.md) - See these methods in action
- [Testing](11-testing.md) - Testing strategies
- [Advanced Features](07-advanced-features.md) - Complex usage

**Previous:** [Architecture](09-architecture.md) | **Next:** [Testing](11-testing.md)
