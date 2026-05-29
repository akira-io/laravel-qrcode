# Data Types

The package supports various specialized QR code data types through convenient Facade methods. All Value Objects and validations are handled internally.

## WiFi Networks

Generate QR codes for WiFi network credentials.

### Basic Usage

```php
use Akira\QrCode\Facades\QrCode;

// Simple WiFi QR Code
$qrCode = QrCode::wifi([
    'ssid' => 'MyNetwork',
    'password' => 'SecurePassword123'
]);

// With size customization
$qrCode = QrCode::size(300)->wifi([
    'ssid' => 'MyNetwork',
    'password' => 'password123',
    'hidden' => false
]);
```

### Parameters

Array with the following keys:
- `ssid` (string, required): Network name
- `password` (string, optional): Network password
- `encryption` (string, optional): Encryption type - 'WPA', 'WEP', or 'nopass' (default: 'WPA')
- `hidden` (bool, optional): Whether network is hidden (default: false)

### Examples

**Standard WPA Network:**
```php
$qrCode = QrCode::wifi([
    'ssid' => 'HomeWiFi',
    'password' => 'MyPassword123'
]);
```

**Hidden Network:**
```php
$qrCode = QrCode::wifi([
    'ssid' => 'SecretNetwork',
    'password' => 'secret123',
    'hidden' => true
]);
```

**Open Network (No Password):**
```php
$qrCode = QrCode::wifi([
    'ssid' => 'PublicWiFi'
]);
```

**With Styling:**
```php
$qrCode = QrCode::size(400)
    ->color(0, 102, 204)
    ->wifi([
        'ssid' => 'CoffeeShop',
        'password' => 'freewifi123'
    ]);
```

## Email Addresses

Generate QR codes for email with optional subject, body, cc, and bcc.

### Basic Usage

```php
use Akira\QrCode\Facades\QrCode;

// Simple email
$qrCode = QrCode::email('contact@example.com');

// With subject and body
$qrCode = QrCode::email(
    'support@example.com',
    'Help Request',
    'I need assistance with...'
);
```

### Parameters

```php
email(string $email, ?string $subject = null, ?string $body = null, ?string $cc = null, ?string $bcc = null)
```

- `$email` (string, required): Email address
- `$subject` (string, optional): Email subject
- `$body` (string, optional): Email body
- `$cc` (string, optional): CC email address
- `$bcc` (string, optional): BCC email address

### Examples

**Email Only:**
```php
$qrCode = QrCode::email('support@example.com');
```

**With Subject:**
```php
$qrCode = QrCode::email(
    'sales@example.com',
    'Product Inquiry'
);
```

**Complete Email with CC and BCC:**
```php
$qrCode = QrCode::email(
    'info@example.com',
    'Meeting',
    'Tomorrow at 10am',
    'cc@example.com',
    'bcc@example.com'
);
```

**With Styling:**
```php
$qrCode = QrCode::size(350)
    ->color(231, 76, 60)
    ->email('contact@example.com', 'Subject', 'Body');
```

## Phone Numbers

Generate QR codes for phone numbers that can be called directly.

### Basic Usage

```php
use Akira\QrCode\Facades\QrCode;

// Simple phone number
$qrCode = QrCode::phone('+1234567890');

// With styling
$qrCode = QrCode::size(300)->phone('+1234567890');
```

### Parameters

```php
phone(string $number)
```

- `$number` (string, required): Phone number in international format

### Examples

**US Number:**
```php
$qrCode = QrCode::phone('+1-555-123-4567');
```

**International Number:**
```php
$qrCode = QrCode::phone('+44 20 7946 0958');
```

**With Styling:**
```php
$qrCode = QrCode::size(300)
    ->color(46, 204, 113)
    ->phone('+5511998887777');
```

## SMS Messages

Generate QR codes for SMS with predefined message.

### Basic Usage

```php
use Akira\QrCode\Facades\QrCode;

// Simple SMS
$qrCode = QrCode::sms('+1234567890', 'Hello from QR Code');

// With styling
$qrCode = QrCode::size(300)->sms('+1234567890', 'Hello!');
```

### Parameters

```php
sms(string $phoneNumber, string $message)
```

- `$phoneNumber` (string, required): Recipient phone number
- `$message` (string, required): SMS message text

### Examples

**Verification Code:**
```php
$qrCode = QrCode::sms(
    '+5511998887777',
    'Your verification code is: 123456'
);
```

**Marketing Message:**
```php
$qrCode = QrCode::sms(
    '+1234567890',
    'Use code SAVE20 for 20% off your next purchase!'
);
```

**With Styling:**
```php
$qrCode = QrCode::size(350)
    ->color(155, 89, 182)
    ->sms('+1234567890', 'Hello from QR!');
```

## Geographic Locations

Generate QR codes for GPS coordinates.

### Basic Usage

```php
use Akira\QrCode\Facades\QrCode;

// Simple location
$qrCode = QrCode::geo(37.7749, -74.0060);

// With optional label
$qrCode = QrCode::geo(40.7128, -74.0060, 'New York City');
```

### Parameters

```php
geo(float $latitude, float $longitude, ?string $label = null)
```

- `$latitude` (float, required): Latitude coordinate
- `$longitude` (float, required): Longitude coordinate
- `$label` (string, optional): Location label

### Examples

**City Location (San Francisco):**
```php
$qrCode = QrCode::geo(37.7749, -122.4194, 'San Francisco');
```

**Precise Location (Statue of Liberty):**
```php
$qrCode = QrCode::geo(40.689247, -74.044502, 'Statue of Liberty');
```

**Negative Coordinates (Sydney):**
```php
$qrCode = QrCode::geo(-33.8688, 151.2093, 'Sydney, Australia');
```

**With Styling:**
```php
$qrCode = QrCode::size(400)
    ->color(52, 152, 219)
    ->geo(40.7128, -74.0060, 'NYC');
```

## Bitcoin Addresses

Generate QR codes for Bitcoin payments.

### Basic Usage

```php
use Akira\QrCode\Facades\QrCode;

// Simple bitcoin payment
$qrCode = QrCode::bitcoin('1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa', 0.001);

// With optional parameters
$qrCode = QrCode::bitcoin(
    '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa',
    0.001,
    ['label' => 'Donation', 'message' => 'Thank you!']
);
```

### Parameters

```php
bitcoin(string $address, ?float $amount = null, array $options = [])
```

- `$address` (string, required): Bitcoin address
- `$amount` (float|null, optional): Amount in BTC
- `$options` (array, optional):
  - `label` (string): Payment label
  - `message` (string): Message to recipient
  - `return` or `returnAddress` (string): Return callback URL

### Examples

**Simple Payment:**
```php
$qrCode = QrCode::bitcoin(
    '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa',
    0.001
);
```

**With Label:**
```php
$qrCode = QrCode::bitcoin(
    '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa',
    0.001,
    ['label' => 'Donation']
);
```

**Complete Payment:**
```php
$qrCode = QrCode::bitcoin(
    '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa',
    0.001,
    [
        'label' => 'Product Purchase',
        'message' => 'Order #12345',
        'return' => 'https://myshop.com/callback'
    ]
);
```

**With Styling:**
```php
$qrCode = QrCode::size(400)
    ->color(242, 169, 0)
    ->errorCorrection('H')
    ->bitcoin('1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa', 0.001);
```

## Complete Examples from Playground

### Basic Text QR Codes

```php
use Akira\QrCode\Facades\QrCode;

// Simple text
$qrCode = QrCode::text('Hello World');

// Text with size
$qrCode = QrCode::size(300)->text('Large QR Code');

// Text with margin
$qrCode = QrCode::margin(5)->text('QR with Margin');

// Text with color
$qrCode = QrCode::color(255, 0, 0)->text('Red QR Code');

// Text with background
$qrCode = QrCode::backgroundColor(255, 255, 0)->text('Yellow Background');
```

### Data Type Examples

```php
// Email
$qrCode = QrCode::email('test@example.com', 'Subject', 'Body');

// Email with CC/BCC
$qrCode = QrCode::email(
    'test@example.com',
    'Meeting',
    'Tomorrow at 10am',
    'cc@example.com',
    'bcc@example.com'
);

// WiFi
$qrCode = QrCode::wifi([
    'ssid' => 'MyNetwork',
    'password' => 'password123',
    'hidden' => false
]);

// WiFi Hidden
$qrCode = QrCode::wifi([
    'ssid' => 'SecretNetwork',
    'password' => 'secret123',
    'hidden' => true
]);

// SMS
$qrCode = QrCode::sms('+1234567890', 'Hello from QR Code');

// Phone
$qrCode = QrCode::phone('+1234567890');

// Geo Location
$qrCode = QrCode::geo(40.7128, -74.0060, 'New York City');

// Bitcoin
$qrCode = QrCode::bitcoin(
    '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa',
    0.001,
    ['label' => 'Donation']
);
```

### Gradient Examples

```php
// Vertical Gradient
$qrCode = QrCode::gradient(255, 0, 0, 0, 0, 255, 'vertical')
    ->text('Vertical Gradient');

// Horizontal Gradient
$qrCode = QrCode::gradient(255, 0, 0, 0, 255, 0, 'horizontal')
    ->text('Horizontal Gradient');

// Diagonal Gradient
$qrCode = QrCode::gradient(255, 0, 255, 255, 255, 0, 'diagonal')
    ->text('Diagonal Gradient');

// Radial Gradient
$qrCode = QrCode::gradient(0, 0, 255, 255, 255, 255, 'radial')
    ->text('Radial Gradient');
```

### Style Examples

```php
// Square Style (Default)
$qrCode = QrCode::style('square')->text('Square Style');

// Dot Style variations
$qrCode = QrCode::style('dot', 0.5)->text('Dot Style 0.5');
$qrCode = QrCode::style('dot', 0.7)->text('Dot Style 0.7');

// Round Style variations
$qrCode = QrCode::style('round', 0.5)->text('Round Style 0.5');
$qrCode = QrCode::style('round', 0.8)->text('Round Style 0.8');
```

### Eye Style Examples

```php
// Square Eye (Default)
$qrCode = QrCode::eye('square')->text('Square Eye Style');

// Circle Eye
$qrCode = QrCode::eye('circle')->text('Circle Eye Style');

// Individual Eye Colors
$qrCode = QrCode::eyeColor(0, 255, 0, 0, 0, 0, 0)
    ->text('Red Eye 0');

$qrCode = QrCode::eyeColor(1, 0, 255, 0, 0, 0, 0)
    ->text('Green Eye 1');

$qrCode = QrCode::eyeColor(2, 0, 0, 255, 0, 0, 0)
    ->text('Blue Eye 2');

// All Eyes with Different Colors
$qrCode = QrCode::eyeColor(0, 255, 0, 0, 0, 0, 0)
    ->eyeColor(1, 0, 255, 0, 0, 0, 0)
    ->eyeColor(2, 0, 0, 255, 0, 0, 0)
    ->text('All Eyes Different Colors');
```

### Error Correction Examples

```php
// Low (7% correction)
$qrCode = QrCode::errorCorrection('L')->text('Low Error Correction');

// Medium (15% correction) - Default
$qrCode = QrCode::errorCorrection('M')->text('Medium Error Correction');

// Quartile (25% correction)
$qrCode = QrCode::errorCorrection('Q')->text('Quartile Error Correction');

// High (30% correction)
$qrCode = QrCode::errorCorrection('H')->text('High Error Correction');
```

### Combined Styles

```php
// Color + Gradient + Style
$qrCode = QrCode::gradient(255, 0, 0, 255, 255, 0, 'diagonal')
    ->backgroundColor(0, 0, 0)
    ->style('round', 0.7)
    ->text('Combined Styles');

// Eye + Color + Size
$qrCode = QrCode::size(300)
    ->eye('circle')
    ->eyeColor(0, 255, 0, 0, 0, 0, 0)
    ->eyeColor(1, 0, 255, 0, 0, 0, 0)
    ->eyeColor(2, 0, 0, 255, 0, 0, 0)
    ->color(100, 100, 100)
    ->text('Complex Design');

// Full Customization
$qrCode = QrCode::size(350)
    ->margin(2)
    ->gradient(138, 43, 226, 75, 0, 130, 'radial')
    ->backgroundColor(255, 255, 255)
    ->eye('circle')
    ->style('dot', 0.6)
    ->errorCorrection('H')
    ->text('Full Customization');
```

### Format Examples

```php
// SVG Format (Vector)
$qrCode = QrCode::format('svg')->text('SVG Format');

// PNG Format (Raster)
$qrCode = QrCode::format('png')->text('PNG Format');

// EPS Format (Print)
$qrCode = QrCode::format('eps')->text('EPS Format');
```

## Using in Controllers

```php
use Akira\QrCode\Facades\QrCode;
use Akira\QrCode\ValueObjects\EmailData;
use Akira\QrCode\DataTypes\EmailDataType;
use Illuminate\Http\Response;

class ContactController extends Controller
{
    public function qrcode(): Response
    {
        $emailData = EmailData::create(
            email: 'contact@example.com',
            subject: 'Contact Request'
        );
        
        $dataType = EmailDataType::fromValueObject($emailData);
        
        $qrCode = QrCode::format('png')
            ->size(300)
            ->generate((string) $dataType);
        
        return response($qrCode)
            ->header('Content-Type', 'image/png');
    }
}
```

## Using in Blade

```blade
@php
use Akira\QrCode\Facades\QrCode;
use Akira\QrCode\ValueObjects\PhoneNumber;
use Akira\QrCode\DataTypes\PhoneNumberDataType;

$phoneNumber = PhoneNumber::create('+1234567890');
$dataType = PhoneNumberDataType::fromValueObject($phoneNumber);
@endphp

<div class="contact-card">
    <h3>Call Us</h3>
    {!! QrCode::size(200)->generate((string) $dataType) !!}
    <p>Scan to call directly</p>
</div>
```

## Validation

All Value Objects validate data at creation:

```php
// This will throw InvalidArgumentException
try {
    $wifiData = WiFiData::create(
        ssid: '',  // Empty SSID
        password: 'password'
    );
} catch (InvalidArgumentException $e) {
    // Handle validation error
}

// Invalid encryption type
try {
    $wifiData = WiFiData::create(
        ssid: 'Network',
        password: 'password',
        encryption: 'INVALID'  // Not WPA, WEP, or nopass
    );
} catch (InvalidArgumentException $e) {
    // Handle validation error
}
```

## Creating Custom Data Types

See [Advanced Features](07-advanced-features.md) for detailed guide on creating custom data types.

## Data Type Format Specifications

### WiFi Format
```
WIFI:T:<encryption>;S:<ssid>;P:<password>;H:<hidden>;;
```

### Email Format
```
mailto:<email>?subject=<subject>&body=<body>
```

### Phone Format
```
tel:<number>
```

### SMS Format
```
sms:<number>:<message>
```

### Geo Format
```
geo:<latitude>,<longitude>
```

### Bitcoin Format
```
bitcoin:<address>?amount=<amount>&label=<label>&message=<message>
```

## Best Practices

1. **Use high error correction** (H) for data types with logos or complex styling
2. **Test with multiple scanners** to ensure compatibility across devices
3. **Consider QR code size** based on data complexity and scanning distance
4. **Validate user input** before generating QR codes from form data
5. **Cache generated QR codes** for better performance in production
6. **Use appropriate sizing** - minimum 200px for standard use cases

## Next Steps

- [Customization](06-customization.md) - Style your QR codes
- [Advanced Features](07-advanced-features.md) - Create custom data types
- [Examples](08-examples.md) - Real-world usage examples

**Previous:** [Basic Usage](04-basic-usage.md) | **Next:** [Customization](06-customization.md)
