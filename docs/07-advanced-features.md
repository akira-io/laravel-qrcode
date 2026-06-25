# Advanced Features

This guide covers advanced usage patterns, custom data types, and complex integrations.

## Creating Custom Data Types

Extend the package with your own data types following the Akira Action Pattern.

### Step 1: Create a Value Object

Create an immutable, validated data container:

```php
namespace App\ValueObjects;

use InvalidArgumentException;

final readonly class VCardData
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $phone,
        public ?string $organization = null,
        public ?string $title = null,
        public ?string $url = null
    ) {}
    
    public static function create(
        string $firstName,
        string $lastName,
        string $email,
        string $phone,
        ?string $organization = null,
        ?string $title = null,
        ?string $url = null
    ): self {
        // Validation
        if (empty($firstName) || empty($lastName)) {
            throw new InvalidArgumentException('First and last name are required');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email address');
        }
        
        if (empty($phone)) {
            throw new InvalidArgumentException('Phone number is required');
        }
        
        return new self($firstName, $lastName, $email, $phone, $organization, $title, $url);
    }
}
```

### Step 2: Create an Action

Create a single-responsibility action to build the QR code string:

```php
namespace App\Actions;

use App\ValueObjects\VCardData;

final class BuildVCardStringAction
{
    public function handle(VCardData $data): string
    {
        $vcard = "BEGIN:VCARD\n";
        $vcard .= "VERSION:3.0\n";
        $vcard .= sprintf("FN:%s %s\n", $data->firstName, $data->lastName);
        $vcard .= sprintf("N:%s;%s;;;\n", $data->lastName, $data->firstName);
        $vcard .= sprintf("EMAIL:%s\n", $data->email);
        $vcard .= sprintf("TEL:%s\n", $data->phone);
        
        if ($data->organization) {
            $vcard .= sprintf("ORG:%s\n", $data->organization);
        }
        
        if ($data->title) {
            $vcard .= sprintf("TITLE:%s\n", $data->title);
        }
        
        if ($data->url) {
            $vcard .= sprintf("URL:%s\n", $data->url);
        }
        
        $vcard .= "END:VCARD";
        
        return $vcard;
    }
}
```

### Step 3: Create a DataType

Create a DataType that orchestrates the Value Object and Action:

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
        // Laravel IoC resolves dependencies automatically
        return app(self::class, ['data' => $data]);
    }
    
    public function __toString(): string
    {
        return $this->action->handle($this->data);
    }
}
```

### Step 4: Use Your Custom Type

```php
use App\ValueObjects\VCardData;
use App\DataTypes\VCardDataType;
use Akira\QrCode\Facades\QrCode;

$vcard = VCardData::create(
    firstName: 'John',
    lastName: 'Doe',
    email: 'john.doe@example.com',
    phone: '+1234567890',
    organization: 'Acme Corp',
    title: 'Software Engineer',
    url: 'https://johndoe.com'
);

$dataType = VCardDataType::fromValueObject($vcard);

$qrCode = QrCode::size(400)
    ->errorCorrection('H')
    ->generate((string) $dataType);
```

## Advanced Image Merging

### Multiple Image Layers

```php
use Akira\QrCode\Facades\QrCode;

// Generate base QR code with logo
$qrCode = QrCode::format('png')
    ->size(600)
    ->errorCorrection('H')
    ->merge(public_path('images/logo.png'), 0.2)
    ->generate('https://example.com');

// Save for further processing
$path = storage_path('qrcodes/branded.png');
file_put_contents($path, $qrCode);
```

### Dynamic Logo Selection

```php
class QrCodeService
{
    public function generateWithBrandLogo(string $text, string $brand): string
    {
        $logoPath = public_path("images/brands/{$brand}.png");
        
        if (!file_exists($logoPath)) {
            $logoPath = public_path('images/default-logo.png');
        }
        
        return QrCode::format('png')
            ->size(500)
            ->errorCorrection('H')
            ->merge($logoPath, 0.2)
            ->generate($text);
    }
}
```

### Logo with Border

Create a logo with border before merging:

```php
use Intervention\Image\Facades\Image;

// Create logo with border
$logo = Image::make(public_path('images/logo.png'))
    ->resize(100, 100)
    ->background('#ffffff')
    ->border(5, '#ffffff');

$tempPath = storage_path('temp/logo-bordered.png');
$logo->save($tempPath);

// Merge with QR code
$qrCode = QrCode::format('png')
    ->size(500)
    ->errorCorrection('H')
    ->merge($tempPath, 0.25)
    ->generate('https://example.com');

// Clean up
unlink($tempPath);
```

## Batch Generation

### Generate Multiple QR Codes

```php
use Akira\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class BatchQrCodeGenerator
{
    public function generateBatch(array $items): array
    {
        return QrCode::format('png')
            ->size(400)
            ->batchRaw(collect($items)->pluck('url', 'id'))
            ->map(function ($qrCode, int|string $id): array {
                $filename = "qrcodes/{$id}.png";
                Storage::disk('public')->put($filename, $qrCode);

                return [
                    'id' => (string) $id,
                    'path' => Storage::disk('public')->url($filename),
                ];
            })
            ->values()
            ->all();
    }
}
```

### Async Batch Processing

```php
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Akira\QrCode\Facades\QrCode;

class GenerateQrCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;
    
    public function __construct(
        private string $text,
        private string $filename
    ) {}
    
    public function handle(): void
    {
        $qrCode = QrCode::format('png')
            ->size(600)
            ->errorCorrection('H')
            ->generate($this->text);
        
        Storage::disk('public')->put($this->filename, $qrCode);
    }
}

// Dispatch jobs
foreach ($urls as $index => $url) {
    GenerateQrCodeJob::dispatch($url, "qrcodes/qr-{$index}.png");
}
```

## Caching Strategies

### Simple Caching

```php
use Akira\QrCode\Facades\QrCode;

function getCachedQrCode(string $text, int $size = 300): string
{
    return QrCode::format('png')
        ->size($size)
        ->cache(ttl: 3600, prefix: 'qrcode')
        ->generateRaw($text);
}
```

The cache key includes the payload and generation options. Size, format, colors, merge data, and error correction produce separate cache entries.

### Cache Key Inspection

```php
use Illuminate\Support\Facades\Cache;
use Akira\QrCode\Facades\QrCode;

class QrCodeCacheInvalidator
{
    public function forget(string $text): void
    {
        $key = QrCode::format('png')
            ->size(300)
            ->cache(prefix: 'qrcode')
            ->cacheKeyFor($text);

        Cache::forget($key);
    }
}
```

### File-Based Caching

```php
use Illuminate\Support\Facades\Storage;

class QrCodeFileCache
{
    public function getOrGenerate(string $text, string $identifier): string
    {
        $filename = "qrcodes/cache/{$identifier}.png";
        
        if (Storage::disk('local')->exists($filename)) {
            return Storage::disk('local')->get($filename);
        }
        
        $qrCode = QrCode::format('png')
            ->size(400)
            ->generate($text);
        
        Storage::disk('local')->put($filename, $qrCode);
        
        return $qrCode;
    }
}
```

## API Integration

### RESTful API Endpoint

```php
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Akira\QrCode\Facades\QrCode;

class QrCodeApiController extends Controller
{
    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'text' => 'required|string|max:1000',
            'size' => 'integer|min:100|max:1000',
            'format' => 'in:png,svg,eps',
            'color' => 'array|size:3',
            'margin' => 'integer|min:0|max:50',
        ]);
        
        $qr = QrCode::format($validated['format'] ?? 'png')
            ->size($validated['size'] ?? 300)
            ->margin($validated['margin'] ?? 4);
        
        if (isset($validated['color'])) {
            $qr->color(...$validated['color']);
        }
        
        $qrCode = $qr->generate($validated['text']);
        
        return response()->json([
            'success' => true,
            'qrcode' => base64_encode($qrCode),
            'format' => $validated['format'] ?? 'png',
        ]);
    }
    
    public function download(Request $request): Response
    {
        $validated = $request->validate([
            'text' => 'required|string|max:1000',
            'size' => 'integer|min:100|max:1000',
        ]);
        
        $qrCode = QrCode::format('png')
            ->size($validated['size'] ?? 500)
            ->generate($validated['text']);
        
        $filename = 'qrcode-' . time() . '.png';
        
        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
```

### GraphQL Integration

```php
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Akira\QrCode\Facades\QrCode;

class GenerateQrCodeMutation extends Mutation
{
    protected $attributes = [
        'name' => 'generateQrCode',
    ];
    
    public function type(): Type
    {
        return Type::nonNull(Type::string());
    }
    
    public function args(): array
    {
        return [
            'text' => ['type' => Type::nonNull(Type::string())],
            'size' => ['type' => Type::int()],
            'format' => ['type' => Type::string()],
        ];
    }
    
    public function resolve($root, $args)
    {
        $qrCode = QrCode::format($args['format'] ?? 'png')
            ->size($args['size'] ?? 300)
            ->generate($args['text']);
        
        return base64_encode($qrCode);
    }
}
```

## Database Storage

### Store with Models

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Akira\QrCode\Facades\QrCode;

class Product extends Model
{
    public function generateQrCode(): string
    {
        $qrCode = QrCode::format('png')
            ->size(400)
            ->errorCorrection('H')
            ->generate($this->url);
        
        $filename = "products/qrcodes/{$this->id}.png";
        Storage::disk('public')->put($filename, $qrCode);
        
        $this->update(['qrcode_path' => $filename]);
        
        return Storage::disk('public')->url($filename);
    }
    
    public function getQrCodeUrlAttribute(): ?string
    {
        if ($this->qrcode_path) {
            return Storage::disk('public')->url($this->qrcode_path);
        }
        
        return null;
    }
}
```

### Store as Base64 in Database

```php
class Ticket extends Model
{
    protected $casts = [
        'qrcode' => 'string',
    ];
    
    public function generateQrCode(): void
    {
        $qrCode = QrCode::format('png')
            ->size(300)
            ->generate($this->verification_code);
        
        $this->qrcode = base64_encode($qrCode);
        $this->save();
    }
    
    public function getQrCodeImageAttribute(): string
    {
        return "data:image/png;base64,{$this->qrcode}";
    }
}
```

## PDF Integration

### Add QR Code to PDF

```php
use Barryvdh\DomPDF\Facade\Pdf;
use Akira\QrCode\Facades\QrCode;

class InvoicePdfGenerator
{
    public function generate(Invoice $invoice): string
    {
        $qrCode = QrCode::format('png')
            ->size(200)
            ->generate($invoice->payment_url);
        
        $base64 = base64_encode($qrCode);
        
        $pdf = Pdf::loadView('invoices.pdf', [
            'invoice' => $invoice,
            'qrCodeImage' => "data:image/png;base64,{$base64}",
        ]);
        
        return $pdf->output();
    }
}
```

Blade template (invoices/pdf.blade.php):
```blade
<div class="invoice">
    <h1>Invoice #{{ $invoice->number }}</h1>
    
    <div class="qr-code">
        <img src="{{ $qrCodeImage }}" alt="Payment QR Code">
        <p>Scan to pay</p>
    </div>
</div>
```

## Email Integration

### Include QR Code in Email

```php
use Illuminate\Mail\Mailable;
use Akira\QrCode\Facades\QrCode;

class TicketMail extends Mailable
{
    public function __construct(
        private Ticket $ticket
    ) {}
    
    public function build()
    {
        $qrCode = QrCode::format('png')
            ->size(300)
            ->generate($this->ticket->code);
        
        $tempPath = storage_path("temp/qr-{$this->ticket->id}.png");
        file_put_contents($tempPath, $qrCode);
        
        return $this->view('emails.ticket')
            ->with(['ticket' => $this->ticket])
            ->attach($tempPath, [
                'as' => 'qrcode.png',
                'mime' => 'image/png',
            ]);
    }
}
```

### Embed in Email Body

```php
class EventInvitationMail extends Mailable
{
    public function build()
    {
        $qrCode = QrCode::format('png')
            ->size(250)
            ->generate($this->event->url);
        
        $base64 = base64_encode($qrCode);
        
        return $this->view('emails.event-invitation')
            ->with([
                'event' => $this->event,
                'qrCodeImage' => "data:image/png;base64,{$base64}",
            ]);
    }
}
```

## Testing Custom Data Types

```php
use Tests\TestCase;
use App\ValueObjects\VCardData;
use App\DataTypes\VCardDataType;
use App\Actions\BuildVCardStringAction;

class VCardDataTypeTest extends TestCase
{
    public function test_creates_valid_vcard_string()
    {
        $vcard = VCardData::create(
            firstName: 'John',
            lastName: 'Doe',
            email: 'john@example.com',
            phone: '+1234567890'
        );
        
        $dataType = VCardDataType::fromValueObject($vcard);
        $string = (string) $dataType;
        
        $this->assertStringContainsString('BEGIN:VCARD', $string);
        $this->assertStringContainsString('FN:John Doe', $string);
        $this->assertStringContainsString('EMAIL:john@example.com', $string);
        $this->assertStringContainsString('END:VCARD', $string);
    }
    
    public function test_validates_required_fields()
    {
        $this->expectException(InvalidArgumentException::class);
        
        VCardData::create(
            firstName: '',
            lastName: 'Doe',
            email: 'john@example.com',
            phone: '+1234567890'
        );
    }
}
```

## Performance Optimization

### Lazy Loading

```php
class QrCodeService
{
    private ?string $cachedQrCode = null;
    
    public function __construct(
        private string $text
    ) {}
    
    public function getQrCode(): string
    {
        if ($this->cachedQrCode === null) {
            $this->cachedQrCode = QrCode::format('png')
                ->size(300)
                ->generate($this->text);
        }
        
        return $this->cachedQrCode;
    }
}
```

### Streaming Large QR Codes

```php
use Symfony\Component\HttpFoundation\StreamedResponse;

public function streamQrCode(Request $request): StreamedResponse
{
    return new StreamedResponse(function () use ($request) {
        $qrCode = QrCode::format('png')
            ->size(2000)  // Large size
            ->generate($request->input('text'));
        
        echo $qrCode;
    }, 200, [
        'Content-Type' => 'image/png',
        'Content-Disposition' => 'inline; filename="qrcode.png"',
    ]);
}
```

## Next Steps

- [Examples](08-examples.md) - Real-world usage examples
- [Testing](11-testing.md) - Testing strategies
- [API Reference](10-api-reference.md) - Complete method reference

**Previous:** [Customization](06-customization.md) | **Next:** [Examples](08-examples.md)
