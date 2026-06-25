# Testing

Guide for testing QR code generation in your Laravel applications.

## Testing Setup

The package is built with testability in mind using dependency injection and the Action Pattern.

### Test Environment

```php
namespace Tests\Feature;

use Tests\TestCase;
use Akira\QrCode\Facades\QrCode;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QrCodeTest extends TestCase
{
    use RefreshDatabase;
    
    // Your tests here
}
```

## Unit Testing

### Testing QR Code Generation

```php
use Akira\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\HtmlString;

test('generates qr code from text', function () {
    $qrCode = QrCode::generate('Hello World');
    
    expect($qrCode)->toBeInstanceOf(HtmlString::class);
});

test('generates qr code with custom size', function () {
    $qrCode = QrCode::size(400)->generate('Test');
    
    expect($qrCode)->toBeInstanceOf(HtmlString::class);
    expect($qrCode)->toContain('400');
});

test('generates png format qr code', function () {
    $qrCode = QrCode::format('png')->generateRaw('Test');
    
    expect($qrCode)->toBeString();
    expect(strlen($qrCode))->toBeGreaterThan(0);
});
```

### Testing Value Objects

```php
use Akira\QrCode\ValueObjects\WiFiData;
use InvalidArgumentException;

test('creates valid wifi data', function () {
    $wifiData = WiFiData::create(
        ssid: 'TestNetwork',
        password: 'password123',
        encryption: 'WPA'
    );
    
    expect($wifiData->ssid)->toBe('TestNetwork');
    expect($wifiData->password)->toBe('password123');
    expect($wifiData->encryption)->toBe('WPA');
});

test('validates wifi ssid is required', function () {
    expect(fn() => WiFiData::create(
        ssid: '',
        password: 'password'
    ))->toThrow(InvalidArgumentException::class);
});

test('validates wifi encryption type', function () {
    expect(fn() => WiFiData::create(
        ssid: 'Network',
        password: 'password',
        encryption: 'INVALID'
    ))->toThrow(InvalidArgumentException::class);
});
```

### Testing Actions

```php
use Akira\QrCode\Actions\BuildWiFiStringAction;
use Akira\QrCode\ValueObjects\WiFiData;

test('builds wifi qr string correctly', function () {
    $action = new BuildWiFiStringAction();
    $data = WiFiData::create('TestNet', 'pass123', 'WPA', false);
    
    $result = $action->handle($data);
    
    expect($result)->toContain('WIFI:');
    expect($result)->toContain('T:WPA');
    expect($result)->toContain('S:TestNet');
    expect($result)->toContain('P:pass123');
});

test('escapes special characters in wifi string', function () {
    $action = new BuildWiFiStringAction();
    $data = WiFiData::create('Test;Net', 'pass:123', 'WPA');
    
    $result = $action->handle($data);
    
    expect($result)->toContain('S:Test\\;Net');
    expect($result)->toContain('P:pass\\:123');
});
```

### Testing DataTypes

```php
use Akira\QrCode\DataTypes\WiFiDataType;
use Akira\QrCode\ValueObjects\WiFiData;

test('wifi data type converts to string', function () {
    $wifiData = WiFiData::create('Network', 'password', 'WPA');
    $dataType = WiFiDataType::fromValueObject($wifiData);
    
    $string = (string) $dataType;
    
    expect($string)->toBeString();
    expect($string)->toContain('WIFI:');
});

test('data type uses dependency injection', function () {
    $wifiData = WiFiData::create('Network', 'password');
    
    // Laravel IoC should resolve dependencies
    $dataType = WiFiDataType::fromValueObject($wifiData);
    
    expect($dataType)->toBeInstanceOf(WiFiDataType::class);
});
```

### Testing Payloads

Use `QrCodePayloadAssertions` when you want to test the payload before rendering it, or when your application provides a QR decoder.

```php
use Akira\QrCode\Testing\QrCodePayloadAssertions;

test('wifi payload contains network name', function () {
    $payload = 'WIFI:S:Network;P:secret;';

    QrCodePayloadAssertions::assertPayloadStartsWith('WIFI:', $payload);
    QrCodePayloadAssertions::assertPayloadContains('S:Network', $payload);
});

test('decoded qr image matches expected payload', function () {
    $decoder = fn (string $path): string => app(MyQrDecoder::class)->decode($path);

    QrCodePayloadAssertions::assertDecodedPayload(
        storage_path('qrcodes/example.png'),
        $decoder,
        'https://example.com'
    );
});
```

The package does not install a decoder by default. Use a decoder in your application test suite when you need image-level scannability checks.

## Integration Testing

### Testing with Controllers

```php
use Akira\QrCode\Facades\QrCode;

test('generates qr code endpoint returns image', function () {
    $response = $this->postJson('/api/qrcode', [
        'text' => 'Test QR Code'
    ]);
    
    $response->assertOk();
    $response->assertHeader('Content-Type', 'image/png');
});

test('validates qr code generation request', function () {
    $response = $this->postJson('/api/qrcode', [
        'text' => '' // Empty text
    ]);
    
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['text']);
});
```

### Testing with Models

```php
use App\Models\Product;

test('product generates qr code', function () {
    $product = Product::factory()->create([
        'url' => 'https://example.com/products/1'
    ]);
    
    $qrCode = $product->generateQrCode();
    
    expect($qrCode)->toBeString();
    expect(strlen($qrCode))->toBeGreaterThan(0);
});

test('product qr code is cached', function () {
    $product = Product::factory()->create();
    
    $first = $product->getQrCode();
    $second = $product->getQrCode();
    
    expect($first)->toBe($second);
});
```

## Testing Without Mocks

This package - and its own test suite - does not use mocks or spies. Generation is fast, deterministic, and has no external dependencies, so tests run against the real Laravel container and the real renderer. Assert on the actual output or, for logic-level checks, on the encoded payload.

### Assert on real output

```php
use Akira\QrCode\Facades\QrCode;
use Illuminate\Support\HtmlString;

test('generates real svg output', function () {
    $result = QrCode::format('svg')->size(300)->generate('Test');

    expect($result)->toBeInstanceOf(HtmlString::class)
        ->and((string) $result)->toContain('<svg');
});

test('generates real png bytes', function () {
    $png = QrCode::format('png')->generateRaw('Test');

    expect($png)->toBeString()->not->toBe('');
});
```

### Assert on the payload, not the image

When you only care about the encoded data, build the action directly (it is a stateless `handle()` call) or use `QrCodePayloadAssertions` (see [Testing Payloads](#testing-payloads)):

```php
use Akira\QrCode\Actions\BuildWiFiStringAction;
use Akira\QrCode\ValueObjects\WiFiData;

test('builds the expected wifi payload', function () {
    $payload = (new BuildWiFiStringAction)->handle(
        WiFiData::create('Test', 'pass')
    );

    expect($payload)->toBe('WIFI:T:WPA;S:Test;P:pass;;');
});
```

### Swap collaborators via the container

If a test genuinely needs a substitute (for example a fake that records calls), bind a real fake implementation through the container rather than a mock object:

```php
$this->app->instance(BuildWiFiStringAction::class, new class extends BuildWiFiStringAction {
    // override handle() with a deterministic fake if needed
});
```

## Feature Testing

### Testing QR Code Storage

```php
use Illuminate\Support\Facades\Storage;
use Akira\QrCode\Facades\QrCode;

test('stores qr code to disk', function () {
    Storage::fake('public');
    
    $qrCode = QrCode::format('png')
        ->size(300)
        ->generate('Test');
    
    Storage::disk('public')->put('qrcodes/test.png', $qrCode);
    
    Storage::disk('public')->assertExists('qrcodes/test.png');
});

test('qr code file has correct size', function () {
    Storage::fake('local');
    
    $qrCode = QrCode::format('png')
        ->size(500)
        ->generate('Large QR');
    
    Storage::disk('local')->put('qr.png', $qrCode);
    
    $size = Storage::disk('local')->size('qr.png');
    expect($size)->toBeGreaterThan(0);
});
```

### Testing Email with QR Code

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketMail;

test('sends email with qr code attachment', function () {
    Mail::fake();
    
    $ticket = Ticket::factory()->create();
    
    Mail::to('user@example.com')->send(new TicketMail($ticket));
    
    Mail::assertSent(TicketMail::class, function ($mail) {
        return $mail->hasAttachment('qrcode.png');
    });
});
```

### Testing API Responses

```php
test('api returns qr code as base64', function () {
    $response = $this->postJson('/api/qrcode/generate', [
        'text' => 'API Test',
        'size' => 300,
        'format' => 'png'
    ]);
    
    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'qrcode',
        'format'
    ]);
    
    $data = $response->json();
    expect($data['success'])->toBeTrue();
    expect($data['format'])->toBe('png');
    expect(base64_decode($data['qrcode']))->toBeString();
});
```

## Testing Custom Data Types

```php
use App\ValueObjects\VCardData;
use App\DataTypes\VCardDataType;
use App\Actions\BuildVCardStringAction;

test('custom vcard data type generates correct string', function () {
    $vcard = VCardData::create(
        firstName: 'John',
        lastName: 'Doe',
        email: 'john@example.com',
        phone: '+1234567890'
    );
    
    $dataType = VCardDataType::fromValueObject($vcard);
    $string = (string) $dataType;
    
    expect($string)->toContain('BEGIN:VCARD');
    expect($string)->toContain('FN:John Doe');
    expect($string)->toContain('EMAIL:john@example.com');
    expect($string)->toContain('END:VCARD');
});

test('vcard action validates email', function () {
    expect(fn() => VCardData::create(
        firstName: 'John',
        lastName: 'Doe',
        email: 'invalid-email',
        phone: '+1234567890'
    ))->toThrow(InvalidArgumentException::class);
});
```

## Performance Testing

### Testing Generation Speed

```php
test('generates qr code within acceptable time', function () {
    $start = microtime(true);
    
    QrCode::size(500)->generate('Performance Test');
    
    $duration = microtime(true) - $start;
    
    expect($duration)->toBeLessThan(1.0); // Should complete within 1 second
});

test('batch generation completes efficiently', function () {
    $start = microtime(true);
    
    $items = collect(range(1, 100));
    
    $items->each(function ($i) {
        QrCode::size(300)->generate("Item {$i}");
    });
    
    $duration = microtime(true) - $start;
    
    expect($duration)->toBeLessThan(30.0); // 100 QR codes in 30 seconds
});
```

### Testing Memory Usage

```php
test('generates large qr code without excessive memory', function () {
    $memoryBefore = memory_get_usage();
    
    QrCode::size(2000)->generate('Large QR Code');
    
    $memoryAfter = memory_get_usage();
    $memoryUsed = ($memoryAfter - $memoryBefore) / 1024 / 1024; // MB
    
    expect($memoryUsed)->toBeLessThan(50); // Less than 50MB
});
```

## Testing with Different Formats

```php
test('generates svg format', function () {
    $qrCode = QrCode::format('svg')->generate('SVG Test');
    
    expect($qrCode)->toBeInstanceOf(HtmlString::class);
    expect($qrCode->toHtml())->toContain('<svg');
});

test('generates png format', function () {
    $qrCode = QrCode::format('png')->generateRaw('PNG Test');
    
    expect($qrCode)->toBeString();
    
    // Check PNG signature
    $signature = substr($qrCode, 0, 8);
    expect($signature)->toBe("\x89PNG\x0d\x0a\x1a\x0a");
});

test('generates eps format', function () {
    $qrCode = QrCode::format('eps')->generateRaw('EPS Test');
    
    expect($qrCode)->toBeString();
    expect($qrCode)->toContain('%!PS-Adobe');
});
```

## Testing Error Handling

```php
test('handles invalid size gracefully', function () {
    expect(fn() => QrCode::size(-1)->generate('Test'))
        ->toThrow(InvalidArgumentException::class);
});

test('handles invalid error correction level', function () {
    expect(fn() => QrCode::errorCorrection('X')->generate('Test'))
        ->toThrow(InvalidArgumentException::class);
});

test('handles invalid format', function () {
    expect(fn() => QrCode::format('invalid')->generate('Test'))
        ->toThrow(InvalidArgumentException::class);
});
```

## Testing Image Merging

```php
use Illuminate\Support\Facades\Storage;

test('merges logo with qr code', function () {
    Storage::fake('public');
    
    // Create a simple test image
    $img = imagecreatetruecolor(100, 100);
    imagepng($img, storage_path('test-logo.png'));
    imagedestroy($img);
    
    $qrCode = QrCode::format('png')
        ->size(500)
        ->errorCorrection('H')
        ->merge(storage_path('test-logo.png'), 0.2)
        ->generateRaw('With Logo');
    
    expect($qrCode)->toBeString();
    expect(strlen($qrCode))->toBeGreaterThan(0);
    
    unlink(storage_path('test-logo.png'));
});

test('requires png format for image merging', function () {
    expect(fn() => QrCode::format('svg')
        ->merge('logo.png', 0.2)
        ->generate('Test')
    )->toThrow(Exception::class);
});
```

## Browser Testing (Dusk)

```php
use Laravel\Dusk\Browser;

test('displays qr code on page', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/qrcode')
            ->assertSee('QR Code')
            ->assertPresent('img[alt="QR Code"]');
    });
});

test('downloads qr code', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/qrcode')
            ->click('@download-button')
            ->pause(1000)
            ->assertFileDownloaded('qrcode.png');
    });
});
```

## Continuous Integration

### GitHub Actions Example

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.4
          extensions: gd, mbstring
          
      - name: Install Dependencies
        run: composer install
        
      - name: Run Tests
        run: vendor/bin/pest
        
      - name: Run PHPStan
        run: vendor/bin/phpstan analyse
```

## Best Practices

1. **Test both success and failure cases**
2. **Use the real container - no mocks or spies** (use fakes for external services)
3. **Test all data types and their validations**
4. **Verify QR code format and structure**
5. **Test performance with large datasets**
6. **Test integration with storage and email**
7. **Use factories for model testing**
8. **Test caching behavior**
9. **Verify image merging functionality**
10. **Test responsive behavior in browsers**

## Running Tests

```bash
# Run all tests
composer test

# Run specific test
vendor/bin/pest tests/QrCodeTest.php

# Run with filter
vendor/bin/pest --filter=wifi
```

## Next Steps

- [Contributing](12-contributing.md) - Contributing guidelines
- [Examples](08-examples.md) - Real-world examples
- [API Reference](10-api-reference.md) - Complete API documentation

**Previous:** [API Reference](10-api-reference.md) | **Next:** [Contributing](12-contributing.md)
