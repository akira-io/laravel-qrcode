<?php

declare(strict_types=1);

namespace Akira\QrCode;

use Akira\QrCode\Actions\CreateColorAction;
use Akira\QrCode\Actions\GenerateQrCodeAction;
use Akira\QrCode\Actions\MergeImageAction;
use Akira\QrCode\Concerns\AppliesConfiguredOptions;
use Akira\QrCode\Concerns\ConfiguresQrCode;
use Akira\QrCode\Support\DataTypeMapper;
use Akira\QrCode\ValueObjects\ImageMergeConfig;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Renderer\Color\ColorInterface;
use BaconQrCode\Renderer\RendererStyle\EyeFill;
use BaconQrCode\Renderer\RendererStyle\Gradient;
use Illuminate\Support\HtmlString;

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
 */
final class QrCode
{
    use AppliesConfiguredOptions;
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
        return $this->generateAction->handle(
            $text,
            $this->getWriter($this->getRenderer()),
            $this->encoding,
            $this->errorCorrection,
            $this->imageMerge,
            $this->imagePercentage,
            $this->format,
            $filename
        );
    }

    public function generateRaw(string $text, ?string $filename = null): ?string
    {
        $qrCode = $this->generateAction->handle(
            $text,
            $this->getWriter($this->getRenderer()),
            $this->encoding,
            $this->errorCorrection,
            $this->imageMerge,
            $this->imagePercentage,
            $this->format,
            $filename,
            false
        );

        return is_string($qrCode) ? $qrCode : null;
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
}
