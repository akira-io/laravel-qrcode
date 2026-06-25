# Command Line (Artisan)

The package registers a single Artisan command, `qrcode:generate`, for producing QR code files from the terminal or scheduler. It is registered automatically by `QrCodeServiceProvider` - no extra setup is required.

## Signature

```bash
php artisan qrcode:generate
    {text?}                      # Text payload to encode
    {--output=}                  # Output file path
    {--format=png}               # Output format (png, svg, eps, webp, pdf)
    {--size=200}                 # QR code size in pixels
    {--margin=4}                 # QR code margin (quiet zone)
    {--error-correction=H}       # Error correction level (L, M, Q, H)
    {--batch=}                   # CSV file with "text,output" columns
```

The command always writes to a file - it does not print QR output to stdout. It exits with code `0` on success and `1` on failure (the error message is printed).

## Options

| Option | Default | Description |
| --- | --- | --- |
| `text` (argument) | - | Payload to encode. Required unless `--batch` is used |
| `--output` | - | Destination file path. Required in single mode. Its directory must already exist and be writable |
| `--format` | `png` | One of `png`, `svg`, `eps`, `webp`, `pdf` |
| `--size` | `200` | Size in pixels (must be numeric) |
| `--margin` | `4` | Margin in pixels (must be numeric) |
| `--error-correction` | `H` | One of `L`, `M`, `Q`, `H` |
| `--batch` | - | Path to a readable CSV file. When set, `text` and `--output` are ignored |

## Single QR code

```bash
php artisan qrcode:generate "https://example.com" \
    --output=storage/app/qrcodes/site.svg \
    --format=svg \
    --size=300 \
    --error-correction=H
```

On success:

```
QR code written to [storage/app/qrcodes/site.svg].
```

## Batch generation

Provide a CSV file where each row is `text,output`. Every row is rendered with the same `--format`, `--size`, `--margin`, and `--error-correction` options.

`qrcodes.csv`:

```csv
https://example.com,storage/app/qrcodes/home.svg
https://example.com/docs,storage/app/qrcodes/docs.svg
https://example.com/blog,storage/app/qrcodes/blog.svg
```

```bash
php artisan qrcode:generate --batch=qrcodes.csv --format=svg
```

On success:

```
Generated 3 QR code file(s).
```

## Errors and exit codes

The command returns exit code `1` and prints the message when:

- No `text` is given and `--batch` is not used (`Text is required unless --batch is used.`)
- `--output` is empty in single mode (`The --output option is required.`)
- The output directory does not exist or is not writable (`Output directory is not writable: {dir}`)
- The batch file is missing or unreadable (`Batch file is not readable: {path}`)
- A batch row is missing its text or output column (`Batch row {n} is missing text.` / `... is missing output.`)
- `--size`/`--margin` are not numeric (`The --size option must be numeric.`)

## Scheduling

Because it is a standard Artisan command, it can be scheduled in `routes/console.php`:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('qrcode:generate --batch=storage/app/qrcodes/daily.csv --format=png')
    ->daily();
```

## Next Steps

- [Advanced Features](07-advanced-features.md) - Programmatic batch generation and async processing
- [Configuration](02-configuration.md) - Defaults applied to every generated code
- [API Reference](10-api-reference.md) - The underlying `QrCode` methods

**Previous:** [Advanced Features](07-advanced-features.md) | **Next:** [API Reference](10-api-reference.md)
</content>
