# Architecture

## Overview

The Akira QR Code package follows the Action Pattern with strict separation of concerns, leveraging Laravel's IoC container for dependency injection. The architecture emphasizes:

- Single Responsibility Principle
- Immutability with readonly classes
- Type safety with PHP 8.4+ features
- Dependency Injection over manual instantiation
- PHPStan Level 9 compliance

## Architectural Layers

### 1. Value Objects

Immutable, validated data containers with no business logic. Value Objects ensure data integrity through validation at creation time.

**Characteristics:**
- `readonly` classes
- No business logic
- Validation in factory methods
- Immutable after creation

**Example:**

```php
final readonly class WiFiData
{
    public function __construct(
        public string $ssid,
        public string $password,
        public string $encryption,
        public bool $hidden
    ) {}
    
    public static function create(
        string $ssid,
        string $password,
        string $encryption = 'WPA',
        bool $hidden = false
    ): self {
        // Validation happens here
        if (empty($ssid)) {
            throw new InvalidArgumentException('SSID cannot be empty');
        }
        
        if (!in_array($encryption, ['WPA', 'WEP', 'nopass'])) {
            throw new InvalidArgumentException('Invalid encryption type');
        }
        
        return new self($ssid, $password, $encryption, $hidden);
    }
}
```

**Available Value Objects:**
- `WiFiData` - WiFi network credentials
- `EmailData` - Email address with subject and body
- `PhoneNumber` - Phone number
- `SMSData` - SMS message data
- `GeoLocation` - Geographic coordinates
- `BitcoinData` - Bitcoin payment information
- `Color` - RGB/RGBA color values
- `QrCodeSize` - QR code dimensions
- `QrCodeMargin` - QR code margin
- `ImageMergeConfig` - Image merge configuration

### 2. Actions

Single-responsibility classes containing business logic. Each action has a `handle()` method that performs one specific task.

**Characteristics:**
- Single `handle()` method
- No state (stateless)
- Dependency injection via constructor
- Focused on one task

**Example:**

```php
final class BuildWiFiStringAction
{
    public function handle(WiFiData $data): string
    {
        return sprintf(
            'WIFI:T:%s;S:%s;P:%s;H:%s;;',
            $data->encryption,
            $this->escapeValue($data->ssid),
            $this->escapeValue($data->password),
            $data->hidden ? 'true' : 'false'
        );
    }
    
    private function escapeValue(string $value): string
    {
        return str_replace(
            ['\\', ';', ',', ':', '"'],
            ['\\\\', '\\;', '\\,', '\\:', '\\"'],
            $value
        );
    }
}
```

**Available Actions:**
- `GenerateQrCodeAction` - Generates QR code output
- `CreateColorAction` - Creates color objects
- `MergeImageAction` - Merges logo with QR code
- `BuildWiFiStringAction` - Builds WiFi QR string
- `BuildEmailStringAction` - Builds email QR string
- `BuildPhoneNumberStringAction` - Builds phone QR string
- `BuildBitcoinStringAction` - Builds Bitcoin QR string

### 3. DataTypes

Orchestration layer that combines Value Objects and Actions. DataTypes leverage Laravel's IoC container for automatic dependency injection.

**Characteristics:**
- Implements `QrCodeDataTypeContract`
- Orchestrates Value Objects and Actions
- Uses Laravel IoC for dependency resolution
- Converts to string for QR code generation

**Example:**

```php
final readonly class WiFiDataType implements QrCodeDataTypeContract
{
    // Actions injected via IoC - no manual instantiation!
    public function __construct(
        private WiFiData $data,
        private BuildWiFiStringAction $action
    ) {}
    
    public static function fromValueObject(WiFiData $data): self
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

**Available DataTypes:**
- `WiFiDataType`
- `EmailDataType`
- `PhoneNumberDataType`
- `SMSDataType`
- `GeoDataType`
- `BitcoinDataType`

### 4. QrCode Class

Main class for generating QR codes. Uses dependency injection for all actions.

```php
class QrCode
{
    public function __construct(
        protected GenerateQrCodeAction $generateAction,
        protected CreateColorAction $colorAction,
        protected MergeImageAction $mergeImageAction
    ) {}
    
    public function generate(string $text, ?string $filename = null): HtmlString|string
    {
        // Uses injected action - no "new" keyword!
        return $this->generateAction->handle(
            $text,
            $this->getWriter($this->getRenderer()),
            $this->encoding,
            $this->errorCorrection,
            $this->size,
            $this->margin
        );
    }
}
```

## Data Flow

```
User Input
    |
    v
Value Object (validated, immutable)
    |
    v
DataType (orchestration via Laravel IoC)
    |
    v
Action (business logic via handle() method)
    |
    v
String Output
    |
    v
QrCode Generator (with injected actions)
    |
    v
PNG/SVG/EPS Output
```

## Dependency Injection

### Container Resolution

All classes use constructor injection resolved by Laravel's IoC container:

```php
// Automatic resolution
$qrCode = app(QrCode::class);

// Facade wrapper
use Akira\QrCode\Facades\QrCode;
$result = QrCode::generate('text');

// Controller injection
class MyController extends Controller
{
    public function __construct(
        private QrCode $qrCode
    ) {}
    
    public function show()
    {
        return $this->qrCode->generate('Hello');
    }
}
```

### Benefits of IoC

1. **No manual instantiation** - Container handles object creation
2. **Easy testing** - Mock dependencies in tests
3. **Loose coupling** - Depend on abstractions
4. **Maintainability** - Change implementations without affecting consumers

## SOLID Principles

### Single Responsibility Principle

Each class has one reason to change:
- Value Objects: Data structure changes
- Actions: Algorithm changes
- DataTypes: Orchestration changes
- QrCode: QR generation changes

### Open/Closed Principle

Open for extension, closed for modification:
- New DataTypes can be added without modifying existing code
- New Actions can be created without changing the core

### Liskov Substitution Principle

All DataTypes implement `QrCodeDataTypeContract`:

```php
interface QrCodeDataTypeContract
{
    public function __toString(): string;
}
```

Any DataType can be used interchangeably.

### Interface Segregation Principle

Minimal, focused interfaces:
- `QrCodeDataTypeContract` - Single method interface
- No fat interfaces with unnecessary methods

### Dependency Inversion Principle

Depend on abstractions, not concretions:
- QrCode depends on Action abstractions
- IoC container provides concrete implementations
- Easy to swap implementations

## Type Safety

### PHP 8.4 Features

**Readonly Classes:**
```php
final readonly class WiFiData
{
    public function __construct(
        public string $ssid,
        public string $password,
    ) {}
}
```

**Typed Properties:**
```php
class QrCode
{
    protected int $size = 200;
    protected string $format = 'svg';
    protected ?string $encoding = null;
}
```

**Union Types:**
```php
public function generate(string $text, ?string $filename = null): HtmlString|string
{
    // ...
}
```

### PHPStan Level 9

The package maintains PHPStan Level 9 compliance:

```bash
composer analyse
```

This ensures:
- No mixed types
- No undefined properties
- No undefined methods
- Complete type coverage

## Testing Architecture

### Unit Tests

Test individual actions in isolation:

```php
test('builds wifi string correctly', function () {
    $action = new BuildWiFiStringAction();
    $data = WiFiData::create('TestNet', 'pass123');
    
    $result = $action->handle($data);
    
    expect($result)->toBe('WIFI:T:WPA;S:TestNet;P:pass123;H:false;;');
});
```

### Integration Tests

Test with Laravel IoC:

```php
test('generates qrcode with dependency injection', function () {
    $qrCode = app(QrCode::class);
    
    $result = $qrCode->size(300)->generate('test');
    
    expect($result)->toBeInstanceOf(HtmlString::class);
});
```

### Mocking

Easy to mock due to dependency injection:

```php
test('uses injected action', function () {
    $mockAction = Mockery::mock(BuildWiFiStringAction::class);
    $mockAction->shouldReceive('handle')->once()->andReturn('WIFI:...');
    
    $this->app->instance(BuildWiFiStringAction::class, $mockAction);
    
    // Test code
});
```

## Performance Considerations

### Readonly Classes

Zero runtime overhead - no defensive copying needed.

### Type Safety

No runtime type checking - validated at compile time.

### Laravel IoC

Efficient singleton/scoped resolution.

### Immutability

Results are cacheable and thread-safe.

## Extension Points

### Creating Custom DataTypes

1. Create Value Object
2. Create Action
3. Create DataType implementing `QrCodeDataTypeContract`
4. Use with QrCode

See [Advanced Features](advanced-features.md) for detailed examples.

## Next Steps

- [Basic Usage](basic-usage.md) - Learn how to use the package
- [Data Types](data-types.md) - Explore built-in data types
- [Advanced Features](advanced-features.md) - Create custom types
- [Testing](testing.md) - Testing strategies
