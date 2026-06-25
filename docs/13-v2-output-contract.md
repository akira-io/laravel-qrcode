# v2 Output Contract

This contract defines the planned v2 behavior for QR code output and renderer dependencies. It does not change the 1.x runtime API.

## Decisions

### Raw Output

In v2, `generate()` should return raw output for every format:

- `svg`: SVG XML string
- `png`: PNG binary string
- `eps`: EPS string
- `webp`: WebP binary string when WebP support is available
- `pdf`: PDF binary string when PDF support is available

Writing to a filename should continue to return `null` after the file is written.

### Display Helpers

HTML display should move to explicit helpers:

```php
QrCode::format('png')->toHtml('Ticket payload');
QrCode::format('svg')->toHtml('Ticket payload');
```

The helper should return `HtmlString` and should keep the existing data URI behavior for raster formats.

### Raw 1.x Compatibility

`generateRaw()` should stay during the first v2 release as an alias for `generate()`. It can be deprecated after downstream consumers have a release cycle to migrate.

### File and Memory Output

Output behavior should be consistent across formats:

- `generate($text)`: returns raw in-memory output
- `generate($text, $filename)`: writes output and returns `null`
- `toHtml($text)`: returns display HTML and never writes files

## Renderer Dependencies

### Required

SVG should remain available without image extensions.

### Optional

Raster and document formats should fail only when used without the needed extension or package:

- PNG: `ext-imagick` or a supported GD backend
- WebP: Imagick or GD with WebP support
- PDF: a PDF backend package chosen by the v2 implementation

Composer should avoid requiring optional renderer dependencies unless the selected v2 backend cannot fail cleanly at runtime.

## Migration Notes

### 1.x HTML Output

Before:

```php
$html = QrCode::format('png')->generate('Ticket payload');
```

After:

```php
$html = QrCode::format('png')->toHtml('Ticket payload');
```

### 1.x Raw Output

Before:

```php
$bytes = QrCode::format('png')->generateRaw('Ticket payload');
```

After:

```php
$bytes = QrCode::format('png')->generate('Ticket payload');
```

### File Output

File output should not require migration:

```php
QrCode::format('png')->generate('Ticket payload', storage_path('ticket.png'));
```

## Implementation Checklist

- Add `toHtml()` before changing `generate()` behavior.
- Keep `generateRaw()` as a v2 compatibility alias.
- Add runtime checks for optional renderer dependencies.
- Add tests for in-memory output and file output for each supported format.
- Add upgrade guide entries before tagging v2.0.0.
