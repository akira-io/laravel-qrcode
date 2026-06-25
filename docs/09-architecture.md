# Architecture

## Overview

The Akira QR Code package follows the Action pattern with strict separation of concerns, leveraging Laravel's IoC container for dependency injection. The architecture emphasizes:

- Single Responsibility Principle
- Immutability with `readonly` value objects
- Type safety with PHP 8.4+ features
- Dependency injection over manual instantiation
- PHPStan level 9 with 100% type coverage

The public namespace is `Akira\QrCode`. The package is registered through `QrCodeServiceProvider` and exposed via the `QrCode` facade (`Akira\QrCode\Facades\QrCode`) and the `qrcode()` helper.

## Architectural Layers

### 1. Value Objects

Immutable, validated data containers with no rendering logic. Validation happens in the constructor (and the `create()` factory delegates to it), so an instance can never hold invalid state.

**Characteristics:**
- `final readonly` classes
- Validation in the constructor
- Immutable after creation
- May expose small derived helpers (for example `WiFiData::hasPassword()`)

**Example (`src/ValueObjects/WiFiData.php`):**

```php
final readonly class WiFiData
{
    private const array ENCRYPTION_TYPES = ['WPA', 'WEP', 'nopass'];

    public string $encryption;

    public function __construct(
        public string $ssid,
        public ?string $password = null,
        public bool $hidden = false,
        string $encryption = 'WPA'
    ) {
        throw_if($ssid === '' || $ssid === '0', InvalidArgumentException::class, 'SSID cannot be empty');

        $normalizedEncryption = mb_strtolower($encryption) === 'nopass'
            ? 'nopass'
            : mb_strtoupper($encryption);

        throw_unless(
            in_array($normalizedEncryption, self::ENCRYPTION_TYPES, true),
            InvalidArgumentException::class,
            "Encryption type must be WPA, WEP, or nopass, got {$encryption}"
        );

        $this->encryption = $normalizedEncryption;
    }

    public static function create(string $ssid, ?string $password = null, bool $hidden = false, string $encryption = 'WPA'): self
    {
        return new self($ssid, $password, $hidden, $encryption);
    }
}
```

**Available Value Objects (`src/ValueObjects/`):**

| Value Object | Purpose |
| --- | --- |
| `WiFiData` | WiFi network credentials (SSID, password, encryption, hidden) |
| `EmailData` | Email address with subject, body, cc, bcc |
| `VCardData` | vCard contact (name, organization, phone, email, address, ...) |
| `CalendarEventData` | Calendar event (summary, start/end, location, description) |
| `PhoneNumber` | Phone number |
| `SMSData` | SMS recipient and message |
| `GeoLocation` | Geographic coordinates with optional label |
| `BitcoinData` | Bitcoin payment (address, amount, label, message, return address) |
| `EthereumData` | Ethereum payment (address, value, chain id, label, message) |
| `LitecoinData` | Litecoin payment (address, amount, label, message) |
| `Color` | RGB/RGBA color value |
| `QrCodeSize` | Validated QR code size |
| `QrCodeMargin` | Validated QR code margin |
| `ImageMergeConfig` | Logo merge configuration (path, percentage, absolute) |

### 2. Actions

Single-responsibility classes containing one unit of business logic. Each action exposes a `handle()` method (per the project standard — never `execute()`).

**Characteristics:**
- Single `handle()` method
- Stateless
- Dependencies injected via constructor when needed
- Focused on one task

**Example (`src/Actions/BuildWiFiStringAction.php`):**

```php
final class BuildWiFiStringAction
{
    private const string PREFIX = 'WIFI:';

    private const string SEPARATOR = ';';

    public function handle(WiFiData $data): string
    {
        $wifi = self::PREFIX.'T:'.$data->encryptionType().self::SEPARATOR;

        if ($data->ssid !== '' && $data->ssid !== '0') {
            $wifi .= 'S:'.$this->escape($data->ssid).self::SEPARATOR;
        }

        if ($data->hasPassword()) {
            $wifi .= 'P:'.$this->escape((string) $data->password).self::SEPARATOR;
        }

        if ($data->hidden) {
            $wifi .= 'H:true'.self::SEPARATOR;
        }

        return $wifi.self::SEPARATOR;
    }

    private function escape(string $value): string
    {
        return strtr($value, ['\\' => '\\\\', ';' => '\;', ',' => '\,', ':' => '\:']);
    }
}
```

**Available Actions (`src/Actions/`):**

Payload builders (value object to encoded string):

- `BuildWiFiStringAction`
- `BuildEmailStringAction`
- `BuildVCardStringAction`
- `BuildCalendarEventStringAction`
- `BuildPhoneNumberStringAction`
- `BuildSMSStringAction`
- `BuildGeoStringAction`
- `BuildBitcoinStringAction`
- `BuildEthereumStringAction`
- `BuildLitecoinStringAction`

Convenience QR creators (build payload and generate in one step):

- `CreateBTCQrCodeAction`
- `CreateEmailQrCodeAction`
- `CreateGeoQrCodeAction`
- `CreatePhoneNumberQrCodeAction`
- `CreateSMSQrCodeAction`
- `CreateWiFiQrCodeAction`

Rendering and styling:

- `GenerateQrCodeAction` - renders the QR code to the requested format and optionally writes to disk
- `CreateColorAction` - converts a `Color` value object into a Bacon color
- `MergeImageAction` - merges a logo image into the QR code

### 3. DataTypes

Orchestration layer that combines a value object with its builder action. DataTypes implement `QrCodeDataTypeContract` (which extends `Stringable`) and resolve their dependencies through Laravel's container.

**Characteristics:**
- Implements `QrCodeDataTypeContract`
- `final readonly`
- Resolves dependencies via `resolve()`
- Casts to the encoded payload string via `__toString()`

**Example (`src/DataTypes/WiFiDataType.php`):**

```php
final readonly class WiFiDataType implements QrCodeDataTypeContract
{
    public function __construct(
        private WiFiData $data,
        private BuildWiFiStringAction $action
    ) {}

    public function __toString(): string
    {
        return $this->action->handle($this->data);
    }

    public static function fromValueObject(WiFiData $data): self
    {
        return resolve(self::class, ['data' => $data]);
    }
}
```

**Available DataTypes (`src/DataTypes/`):**

- `WiFiDataType`
- `EmailDataType`
- `VCardDataType`
- `CalendarEventDataType`
- `PhoneNumberDataType`
- `SMSDataType`
- `GeoDataType`
- `BitcoinDataType`
- `EthereumDataType`
- `LitecoinDataType`

### 4. Support Mappers

The fluent and magic-method entry points (for example `QrCode::wifi([...])` or `QrCode::eth(...)`) are routed by the mappers in `src/Support/`:

- `DataTypeMapper` - maps a method name (`text`, `email`, `wifi`, `sms`, `geo`, `phone`/`phoneNumber`, `btc`/`bitcoin`) and its arguments to an encoded payload string
- `PaymentDataTypeMapper` - handles `eth`/`ethereum` and `ltc`/`litecoin`
- `ContactEventDataTypeMapper` - handles `vcard`/`contact` and `ical`/`calendar`/`event`
- `QrCodeConfigDefaults` - applies `config/qrcode.php` defaults to a new `QrCode` instance

`DataTypeMapper::createFromMethod()` is the single dispatch point; unknown methods throw `BadMethodCallException`.

### 5. QrCode Class

The main fluent builder. It composes the `BatchesQrCodes` and `ConfiguresQrCode` traits and receives its actions through constructor injection.

```php
final class QrCode
{
    use BatchesQrCodes;
    use ConfiguresQrCode;

    public function __construct(
        private readonly GenerateQrCodeAction $generateAction,
        private readonly CreateColorAction $colorAction,
        private readonly MergeImageAction $mergeImageAction
    ) {
        $this->applyConfigDefaults();
    }

    public function generate(string $text, ?string $filename = null): HtmlString|string|null
    {
        return $this->generateQrCode($text, $filename, true);
    }
}
```

`generate()` returns an `HtmlString` for inline-renderable formats (SVG), a raw `string` for binary formats, or `null` when writing to a file. `generateRaw()` always returns the raw `string` (or `null`). See [API Reference](10-api-reference.md) and the [v2 Output Contract](13-v2-output-contract.md) for the full return-type rules.

**Traits:**
- `ConfiguresQrCode` (`src/Concerns/ConfiguresQrCode.php`) - all fluent styling methods (`size`, `margin`, `format`, `color`, `backgroundColor`, `eyeColor`, `gradient`, `eye`, `style`, `encoding`, `errorCorrection`, `createColor`) and the Bacon renderer assembly
- `BatchesQrCodes` (`src/Concerns/BatchesQrCodes.php`) - `batch()` and `batchRaw()` for generating many codes from one configured instance

## Data Flow

```
User input (fluent method / magic method / helper)
    |
    v
Support mapper (DataTypeMapper / PaymentDataTypeMapper / ContactEventDataTypeMapper)
    |
    v
Value Object (validated, immutable)
    |
    v
DataType (orchestration, resolved via container)
    |
    v
Action handle() (builds encoded payload string)
    |
    v
QrCode renderer (ConfiguresQrCode assembles the Bacon writer)
    |
    v
GenerateQrCodeAction (optional cache, optional logo merge, optional file write)
    |
    v
SVG / PNG / EPS / WEBP / PDF output (HtmlString | string | null)
```

## Dependency Injection

All classes use constructor injection resolved by Laravel's container:

```php
// Automatic resolution
$qrCode = app(QrCode::class);

// Facade wrapper (resolves a fresh instance per call)
use Akira\QrCode\Facades\QrCode;
$result = QrCode::generate('text');

// Controller injection
final class MyController
{
    public function __construct(private QrCode $qrCode) {}

    public function show(): string
    {
        return (string) $this->qrCode->generate('Hello');
    }
}
```

The facade clears the resolved instance on each access (`getFacadeAccessor()` calls `clearResolvedInstance`), so chained facade calls always start from a clean, config-defaulted builder.

## SOLID Principles

### Single Responsibility

Each class has one reason to change: value objects own data shape, actions own one algorithm, data types own orchestration, the `QrCode` builder owns rendering configuration.

### Open/Closed

New data types are added by creating a value object, a builder action, and a data type, then wiring a mapper case — without modifying existing types.

### Liskov Substitution

Every data type implements `QrCodeDataTypeContract`:

```php
interface QrCodeDataTypeContract extends Stringable {}
```

Any data type can be cast to its payload string interchangeably.

### Interface Segregation

`QrCodeDataTypeContract` adds nothing beyond `Stringable` - a minimal, focused contract.

### Dependency Inversion

The `QrCode` builder depends on action types resolved by the container; concrete wiring lives in `QrCodeServiceProvider`.

## Type Safety

The package targets PHP 8.4+ and uses `final readonly` classes, constructor property promotion, union return types (`HtmlString|string|null`), and typed properties throughout.

```bash
composer test:types          # PHPStan
composer test:type-coverage  # 100% type coverage required
```

## Testing Architecture

Tests use the real Laravel container and database-free rendering - no mocking. External-style collaborators are exercised through the container, not Mockery. See [Testing](11-testing.md) for the full strategy and the `QrCodePayloadAssertions` helper.

```php
test('builds wifi string correctly', function () {
    $action = new BuildWiFiStringAction;
    $data = WiFiData::create('TestNet', 'pass123');

    expect($action->handle($data))->toBe('WIFI:T:WPA;S:TestNet;P:pass123;;');
});

test('generates an svg qr code through the container', function () {
    $result = app(QrCode::class)->size(300)->generate('test');

    expect($result)->toBeInstanceOf(HtmlString::class);
});
```

The architecture test (`tests/ArchitectureTest.php`) enforces these conventions (strict types, no debugging statements, final classes).

## Extension Points

### Creating a custom data type

1. Create a `final readonly` value object with constructor validation.
2. Create a `Build...StringAction` with a `handle()` method that returns the encoded payload.
3. Create a data type implementing `QrCodeDataTypeContract`, resolving via `resolve(self::class, ...)`.
4. Add a case to the relevant support mapper if you want fluent/magic-method access.

See [Advanced Features](07-advanced-features.md) for a complete worked example.

## Next Steps

- [Basic Usage](04-basic-usage.md) - Learn how to use the package
- [Data Types](05-data-types.md) - Explore built-in data types
- [Advanced Features](07-advanced-features.md) - Create custom types
- [Testing](11-testing.md) - Testing strategies

**Previous:** [Examples](08-examples.md) | **Next:** [API Reference](10-api-reference.md)
</content>
</invoke>
