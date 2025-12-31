# Examples

Real-world examples demonstrating common use cases for the QR Code package.

## E-Commerce

### Product QR Codes

```php
use Akira\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    public function generateProductQrCode(): string
    {
        $productUrl = route('products.show', $this->slug);
        
        $qrCode = QrCode::format('png')
            ->size(400)
            ->errorCorrection('H')
            ->color(0, 102, 204)
            ->backgroundColor(255, 255, 255)
            ->margin(10)
            ->generate($productUrl);
        
        $filename = "products/{$this->id}/qrcode.png";
        Storage::disk('public')->put($filename, $qrCode);
        
        return Storage::disk('public')->url($filename);
    }
}
```

### Invoice Payment QR Code

```php
class Invoice extends Model
{
    public function getPaymentQrCode(): string
    {
        $paymentUrl = route('invoices.pay', [
            'invoice' => $this->id,
            'token' => $this->payment_token
        ]);
        
        return QrCode::size(300)
            ->errorCorrection('H')
            ->format('png')
            ->generate($paymentUrl);
    }
}

// In Blade template
{!! $invoice->getPaymentQrCode() !!}
```

### Digital Receipt

```php
use Akira\QrCode\Facades\QrCode;

class ReceiptController extends Controller
{
    public function show(Order $order)
    {
        $receiptUrl = route('receipts.view', $order->receipt_code);
        
        $qrCode = QrCode::format('png')
            ->size(250)
            ->errorCorrection('M')
            ->generate($receiptUrl);
        
        return view('receipts.show', [
            'order' => $order,
            'qrCode' => base64_encode($qrCode)
        ]);
    }
}
```

## Event Management

### Event Ticket

```php
use Akira\QrCode\Facades\QrCode;

class Ticket extends Model
{
    public function generateTicketQrCode(): void
    {
        $verificationData = json_encode([
            'ticket_id' => $this->id,
            'event_id' => $this->event_id,
            'holder' => $this->holder_name,
            'seat' => $this->seat_number,
            'timestamp' => now()->timestamp,
        ]);
        
        $qrCode = QrCode::format('png')
            ->size(400)
            ->errorCorrection('H')
            ->merge(public_path('images/event-logo.png'), 0.2)
            ->generate($verificationData);
        
        $this->qrcode = base64_encode($qrCode);
        $this->save();
    }
}
```

### Check-in System

```php
class CheckInController extends Controller
{
    public function scan(Request $request)
    {
        $data = json_decode($request->input('qr_data'), true);
        
        $ticket = Ticket::find($data['ticket_id']);
        
        if (!$ticket || $ticket->checked_in) {
            return response()->json(['error' => 'Invalid ticket'], 400);
        }
        
        $ticket->update([
            'checked_in' => true,
            'checked_in_at' => now()
        ]);
        
        return response()->json(['success' => true]);
    }
}
```

## Restaurant & Hospitality

### Digital Menu

```php
use Akira\QrCode\Facades\QrCode;

class RestaurantController extends Controller
{
    public function tableQrCode(Table $table)
    {
        $menuUrl = route('menu.table', [
            'restaurant' => $table->restaurant_id,
            'table' => $table->number
        ]);
        
        $qrCode = QrCode::size(500)
            ->errorCorrection('H')
            ->color(231, 76, 60)
            ->backgroundColor(255, 255, 255)
            ->margin(20)
            ->format('png')
            ->merge(public_path('images/restaurant-logo.png'), 0.2)
            ->generate($menuUrl);
        
        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="table-' . $table->number . '.png"');
    }
}
```

### WiFi Access for Guests

```php
use Akira\QrCode\Facades\QrCode;

class HotelController extends Controller
{
    public function guestWiFiQrCode(Room $room)
    {
        $qrCode = QrCode::size(400)
            ->errorCorrection('H')
            ->margin(10)
            ->format('png')
            ->wifi([
                'ssid' => config('hotel.guest_wifi_ssid'),
                'password' => $room->wifi_password,
                'encryption' => 'WPA'
            ]);
        
        return response($qrCode)
            ->header('Content-Type', 'image/png');
    }
}
```

## Business Cards

### Digital Business Card

```php
use Illuminate\Support\Facades\Cache;
use Akira\QrCode\Facades\QrCode;

class BusinessCard
{
    public function __construct(
        public string $name,
        public string $title,
        public string $email,
        public string $phone,
        public string $company,
        public string $website
    ) {}
    
    public function toVCard(): string
    {
        return implode("\n", [
            "BEGIN:VCARD",
            "VERSION:3.0",
            "FN:{$this->name}",
            "TITLE:{$this->title}",
            "ORG:{$this->company}",
            "TEL:{$this->phone}",
            "EMAIL:{$this->email}",
            "URL:{$this->website}",
            "END:VCARD"
        ]);
    }
    
    public function generateQrCode(): string
    {
        $cacheKey = 'business-card:' . md5($this->email);
        
        return Cache::remember($cacheKey, 86400, function () {
            return QrCode::format('png')
                ->size(400)
                ->errorCorrection('M')
                ->generate($this->toVCard());
        });
    }
}

// Usage
$card = new BusinessCard(
    name: 'John Doe',
    title: 'CEO',
    email: 'john@example.com',
    phone: '+1234567890',
    company: 'Acme Corp',
    website: 'https://acme.com'
);

$qrCode = $card->generateQrCode();
```

## Marketing & Promotions

### Coupon QR Code

```php
class Coupon extends Model
{
    public function generateQrCode(): string
    {
        $couponUrl = route('coupons.redeem', $this->code);
        
        return QrCode::size(350)
            ->errorCorrection('H')
            ->color(46, 204, 113)
            ->backgroundColor(255, 255, 255)
            ->style('round', 0.5)
            ->eye('circle')
            ->format('png')
            ->generate($couponUrl);
    }
}
```

### Campaign Tracking

```php
class Campaign extends Model
{
    public function generateTrackingQrCode(string $medium = 'qrcode'): string
    {
        $trackingUrl = $this->url . '?' . http_build_query([
            'utm_source' => 'qrcode',
            'utm_medium' => $medium,
            'utm_campaign' => $this->slug,
        ]);
        
        return QrCode::size(400)
            ->errorCorrection('M')
            ->gradient(
                startRed: 255, startGreen: 0, startBlue: 128,
                endRed: 128, endGreen: 0, endBlue: 255,
                type: 'DIAGONAL'
            )
            ->format('svg')
            ->generate($trackingUrl);
    }
}
```

## Shipping & Logistics

### Shipping Label

```php
class Shipment extends Model
{
    public function generateTrackingQrCode(): string
    {
        $trackingData = json_encode([
            'tracking_number' => $this->tracking_number,
            'carrier' => $this->carrier,
            'destination' => $this->destination_zip,
            'weight' => $this->weight,
        ]);
        
        $qrCode = QrCode::format('png')
            ->size(300)
            ->errorCorrection('H')
            ->color(0, 0, 0)
            ->backgroundColor(255, 255, 255)
            ->margin(5)
            ->generate($trackingData);
        
        Storage::disk('local')->put(
            "shipping/labels/{$this->tracking_number}.png",
            $qrCode
        );
        
        return storage_path("shipping/labels/{$this->tracking_number}.png");
    }
}
```

### Warehouse Inventory

```php
class InventoryItem extends Model
{
    public function generateLocationQrCode(): string
    {
        $locationData = sprintf(
            "LOC:%s|SKU:%s|BIN:%s",
            $this->warehouse_location,
            $this->sku,
            $this->bin_number
        );
        
        return QrCode::size(200)
            ->errorCorrection('H')
            ->margin(2)
            ->format('png')
            ->generate($locationData);
    }
}
```

## Healthcare

### Patient Wristband

```php
class Patient extends Model
{
    public function generateWristbandQrCode(): string
    {
        $patientData = encrypt(json_encode([
            'patient_id' => $this->id,
            'mrn' => $this->medical_record_number,
            'dob' => $this->date_of_birth->format('Y-m-d'),
            'blood_type' => $this->blood_type,
        ]));
        
        return QrCode::format('png')
            ->size(200)
            ->errorCorrection('H')
            ->color(220, 53, 69)
            ->backgroundColor(255, 255, 255)
            ->generate($patientData);
    }
}
```

### Prescription

```php
class Prescription extends Model
{
    public function generateVerificationQrCode(): string
    {
        $verificationUrl = route('prescriptions.verify', [
            'id' => $this->id,
            'hash' => hash('sha256', $this->id . $this->patient_id . $this->created_at)
        ]);
        
        return QrCode::size(250)
            ->errorCorrection('H')
            ->generate($verificationUrl);
    }
}
```

## Education

### Student ID Card

```php
class Student extends Model
{
    public function generateIdQrCode(): string
    {
        $studentData = json_encode([
            'student_id' => $this->student_number,
            'name' => $this->full_name,
            'program' => $this->program,
            'year' => $this->enrollment_year,
            'expires' => $this->card_expiry->format('Y-m-d'),
        ]);
        
        return QrCode::format('png')
            ->size(300)
            ->errorCorrection('H')
            ->color(13, 110, 253)
            ->merge(public_path('images/university-logo.png'), 0.2)
            ->generate($studentData);
    }
}
```

### Course Materials

```php
class Course extends Model
{
    public function generateMaterialsQrCode(): string
    {
        $materialsUrl = route('courses.materials', [
            'course' => $this->code,
            'semester' => $this->semester
        ]);
        
        return QrCode::size(400)
            ->errorCorrection('M')
            ->format('svg')
            ->generate($materialsUrl);
    }
}
```

## Authentication & Security

### Two-Factor Authentication

```php
use PragmaRX\Google2FA\Google2FA;
use Akira\QrCode\Facades\QrCode;

class TwoFactorController extends Controller
{
    public function enable(Request $request)
    {
        $google2fa = new Google2FA();
        $secretKey = $google2fa->generateSecretKey();
        
        $user = $request->user();
        $user->two_factor_secret = encrypt($secretKey);
        $user->save();
        
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secretKey
        );
        
        $qrCode = QrCode::format('png')
            ->size(300)
            ->generate($qrCodeUrl);
        
        return view('auth.two-factor', [
            'qrCode' => base64_encode($qrCode),
            'secretKey' => $secretKey
        ]);
    }
}
```

### Secure Document Verification

```php
class Document extends Model
{
    public function generateVerificationQrCode(): string
    {
        $signature = hash_hmac('sha256', $this->content, config('app.key'));
        
        $verificationUrl = route('documents.verify', [
            'id' => $this->id,
            'signature' => $signature
        ]);
        
        return QrCode::format('png')
            ->size(200)
            ->errorCorrection('H')
            ->generate($verificationUrl);
    }
}
```

## Social Media

### Social Media Profile

```php
class SocialProfile
{
    public function generateQrCode(string $platform, string $username): string
    {
        $urls = [
            'instagram' => "https://instagram.com/{$username}",
            'twitter' => "https://twitter.com/{$username}",
            'linkedin' => "https://linkedin.com/in/{$username}",
            'facebook' => "https://facebook.com/{$username}",
        ];
        
        $url = $urls[$platform] ?? '';
        
        $colors = [
            'instagram' => [225, 48, 108],
            'twitter' => [29, 161, 242],
            'linkedin' => [0, 119, 181],
            'facebook' => [24, 119, 242],
        ];
        
        $color = $colors[$platform] ?? [0, 0, 0];
        
        return QrCode::size(400)
            ->color(...$color)
            ->backgroundColor(255, 255, 255)
            ->style('round', 0.6)
            ->eye('circle')
            ->format('png')
            ->generate($url);
    }
}
```

## Real Estate

### Property Listing

```php
class Property extends Model
{
    public function generateListingQrCode(): string
    {
        $listingUrl = route('properties.show', $this->slug);
        
        return QrCode::size(500)
            ->errorCorrection('H')
            ->color(0, 102, 204)
            ->backgroundColor(255, 255, 255)
            ->margin(15)
            ->format('png')
            ->merge(public_path('images/agency-logo.png'), 0.25)
            ->generate($listingUrl);
    }
}
```

### Virtual Tour

```php
class VirtualTour extends Model
{
    public function generateAccessQrCode(): string
    {
        $tourUrl = $this->url . '?' . http_build_query([
            'property' => $this->property_id,
            'token' => $this->access_token,
        ]);
        
        return QrCode::size(400)
            ->errorCorrection('M')
            ->gradient(
                startRed: 0, startGreen: 102, startBlue: 204,
                endRed: 0, endGreen: 204, endBlue: 204,
                type: 'VERTICAL'
            )
            ->format('svg')
            ->generate($tourUrl);
    }
}
```

## File Downloads

### App Download Links

```php
class AppDownloadController extends Controller
{
    public function qrCode(Request $request)
    {
        $userAgent = $request->userAgent();
        
        $url = str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')
            ? 'https://apps.apple.com/app/id123456789'
            : 'https://play.google.com/store/apps/details?id=com.example.app';
        
        return QrCode::size(400)
            ->errorCorrection('M')
            ->format('png')
            ->generate($url);
    }
}
```

## Batch Processing

### Generate QR Codes for Products

```php
use Illuminate\Console\Command;
use Akira\QrCode\Facades\QrCode;

class GenerateProductQrCodesCommand extends Command
{
    protected $signature = 'qrcodes:generate-products';
    
    public function handle()
    {
        $products = Product::whereNull('qrcode_path')->get();
        
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();
        
        foreach ($products as $product) {
            $qrCode = QrCode::format('png')
                ->size(400)
                ->generate(route('products.show', $product->slug));
            
            $filename = "products/{$product->id}/qrcode.png";
            Storage::disk('public')->put($filename, $qrCode);
            
            $product->update(['qrcode_path' => $filename]);
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->info("\nGenerated QR codes for {$products->count()} products.");
    }
}
```

## Next Steps

- [Testing](11-testing.md) - Testing strategies
- [API Reference](10-api-reference.md) - Complete method reference
- [Advanced Features](07-advanced-features.md) - Complex integrations

**Previous:** [Advanced Features](07-advanced-features.md) | **Next:** [Architecture](09-architecture.md)
