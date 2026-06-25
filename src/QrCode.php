<?php

declare(strict_types=1);

namespace Akira\QrCode;

use Akira\QrCode\Actions\CreateColorAction;
use Akira\QrCode\Actions\GenerateQrCodeAction;
use Akira\QrCode\Actions\MergeImageAction;
use Akira\QrCode\Concerns\BatchesQrCodes;
use Akira\QrCode\Concerns\ConfiguresQrCode;
use Akira\QrCode\Support\DataTypeMapper;
use Akira\QrCode\ValueObjects\ImageMergeConfig;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Renderer\Color\ColorInterface;
use BaconQrCode\Renderer\RendererStyle\EyeFill;
use BaconQrCode\Renderer\RendererStyle\Gradient;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\HtmlString;
use InvalidArgumentException;
use Throwable;

/**
 * @method $this text(string $text)
 * @method $this email(string $address, ?string $subject = null, ?string $body = null, ?string $cc = null, ?string $bcc = null)
 * @method $this wifi(array<string, mixed> $config)
 * @method $this sms(string $phoneNumber, ?string $message = null)
 * @method $this phone(string $phoneNumber)
 * @method $this phoneNumber(string $phoneNumber)
 * @method $this geo(float $latitude, float $longitude, ?string $name = null)
 * @method $this bitcoin(string $address, ?float $amount = null, array<string, mixed> $options = [])
 * @method $this btc(string $address, ?float $amount = null, array<string, mixed> $options = [])
 * @method $this ethereum(string $address, int|float|string|null $value = null, array<string, mixed> $options = [])
 * @method $this eth(string $address, int|float|string|null $value = null, array<string, mixed> $options = [])
 * @method $this litecoin(string $address, float $amount = 0.0, array<string, mixed> $options = [])
 * @method $this ltc(string $address, float $amount = 0.0, array<string, mixed> $options = [])
 * @method $this vcard(array<string, mixed> $contact)
 * @method $this contact(array<string, mixed> $contact)
 * @method $this calendar(array<string, mixed> $event)
 * @method $this ical(array<string, mixed> $event)
 * @method $this event(array<string, mixed> $event)
 */
final class QrCode
{
    use BatchesQrCodes;
    use ConfiguresQrCode;

    private string $format = 'svg';

    private int $size = 100;

    private int $margin = 0;

    private ?ErrorCorrectionLevel $errorCorrection = null;

    private string $encoding = Encoder::DEFAULT_BYTE_MODE_ECODING;

    private string $style = 'square';

    private float $styleSize = 0.5;

    private ?string $eyeStyle = null;

    private ?ColorInterface $color = null;

    private ?ColorInterface $backgroundColor = null;

    /** @var array<int, EyeFill> */
    private array $eyeColors = [];

    private ?Gradient $gradient = null;

    private ?string $imageMerge = null;

    private float $imagePercentage = 0.2;

    private bool $cacheEnabled = false;

    private int $cacheTtl = 3600;

    private string $cachePrefix = 'qrcode';

    public function __construct(
        private readonly GenerateQrCodeAction $generateAction,
        private readonly CreateColorAction $colorAction,
        private readonly MergeImageAction $mergeImageAction
    ) {
        $this->applyConfigDefaults();
    }

    /**
     * @param  array<int, mixed>  $arguments
     * @return HtmlString|string|null
     */
    public function __call(string $method, array $arguments)
    {
        $dataTypeString = DataTypeMapper::createFromMethod($method, $arguments);

        return $this->generate($dataTypeString);
    }

    public function generate(string $text, ?string $filename = null): HtmlString|string|null
    {
        return $this->generateQrCode($text, $filename, true);
    }

    public function generateRaw(string $text, ?string $filename = null): ?string
    {
        $qrCode = $this->generateQrCode($text, $filename, false);

        return is_string($qrCode) ? $qrCode : null;
    }

    public function cache(?int $ttl = null, ?string $prefix = null): self
    {
        throw_if($ttl !== null && $ttl < 1, InvalidArgumentException::class, 'Cache TTL must be greater than 0 seconds.');

        $this->cacheEnabled = true;
        $this->cacheTtl = $ttl ?? $this->cacheTtl;
        $this->cachePrefix = $prefix ?? $this->cachePrefix;

        return $this;
    }

    public function withoutCache(): self
    {
        $this->cacheEnabled = false;

        return $this;
    }

    public function cacheKeyFor(string $text): string
    {
        $cachePayload = [
            'text' => $text,
            'format' => $this->format,
            'size' => $this->size,
            'margin' => $this->margin,
            'encoding' => $this->encoding,
            'error_correction' => $this->errorCorrection?->getBits(),
            'style' => $this->style,
            'style_size' => $this->styleSize,
            'eye_style' => $this->eyeStyle,
            'color' => $this->color instanceof ColorInterface ? serialize($this->color) : null,
            'background_color' => $this->backgroundColor instanceof ColorInterface ? serialize($this->backgroundColor) : null,
            'eye_colors' => array_map(serialize(...), $this->eyeColors),
            'gradient' => $this->gradient instanceof Gradient ? serialize($this->gradient) : null,
            'image_merge' => $this->imageMerge === null ? null : hash('sha256', $this->imageMerge),
            'image_percentage' => $this->imagePercentage,
        ];

        return $this->cachePrefix.':'.hash('sha256', serialize($cachePayload));
    }

    public function merge(string $filepath, ?float $percentage = null, bool $absolute = false): self
    {
        $percentage ??= $this->getConfiguredMergePercentage();

        $config = new ImageMergeConfig($filepath, $percentage, $absolute);
        $this->imageMerge = $this->mergeImageAction->handle($config);
        $this->imagePercentage = $percentage;

        return $this;
    }

    public function mergeString(string $content, ?float $percentage = null): self
    {
        $percentage ??= $this->getConfiguredMergePercentage();

        $this->imageMerge = $content;
        $this->imagePercentage = $percentage;

        return $this;
    }

    private function generateQrCode(string $text, ?string $filename, bool $asHtml): HtmlString|string|null
    {
        $cacheRepository = $this->cacheRepository();

        if (! $this->shouldCache($filename) || ! $cacheRepository instanceof CacheRepository) {
            return $this->writeQrCode($text, $filename, $asHtml);
        }

        $qrCode = $cacheRepository->remember(
            $this->cacheKeyFor($text),
            $this->cacheTtl,
            fn (): HtmlString|string|null => $this->writeQrCode($text, $filename, $asHtml)
        );

        return $qrCode instanceof HtmlString || is_string($qrCode) ? $qrCode : null;
    }

    private function writeQrCode(string $text, ?string $filename, bool $asHtml): HtmlString|string|null
    {
        return $this->generateAction->handle(
            $text,
            $this->getWriter($this->getRenderer()),
            $this->encoding,
            $this->errorCorrection,
            $this->imageMerge,
            $this->imagePercentage,
            $this->format,
            $filename,
            $asHtml
        );
    }

    private function shouldCache(?string $filename): bool
    {
        return $filename === null && $this->cacheEnabled;
    }

    private function cacheRepository(): ?CacheRepository
    {
        if (! function_exists('app')) {
            return null;
        }

        try {
            $cacheRepository = resolve(CacheRepository::class);
        } catch (Throwable) {
            return null;
        }

        return $cacheRepository;
    }
}
