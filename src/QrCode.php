<?php

// Comentário de teste adicionado temporariamente

declare(strict_types=1);

namespace Akira\QrCode;

use Akira\QrCode\Actions\CreateColorAction;
use Akira\QrCode\Actions\GenerateQrCodeAction;
use Akira\QrCode\Actions\MergeImageAction;
use Akira\QrCode\Support\DataTypeMapper;
use Akira\QrCode\ValueObjects\Color;
use Akira\QrCode\ValueObjects\ImageMergeConfig;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Renderer\Color\ColorInterface;
use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Eye\EyeInterface;
use BaconQrCode\Renderer\Eye\ModuleEye;
use BaconQrCode\Renderer\Eye\SimpleCircleEye;
use BaconQrCode\Renderer\Eye\SquareEye;
use BaconQrCode\Renderer\Image\EpsImageBackEnd;
use BaconQrCode\Renderer\Image\ImageBackEndInterface;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Module\DotsModule;
use BaconQrCode\Renderer\Module\ModuleInterface;
use BaconQrCode\Renderer\Module\RoundnessModule;
use BaconQrCode\Renderer\Module\SquareModule;
use BaconQrCode\Renderer\RendererStyle\EyeFill;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\Gradient;
use BaconQrCode\Renderer\RendererStyle\GradientType;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\HtmlString;
use InvalidArgumentException;

/**
 * @method $this text(string $text)
 * @method $this email(string $address, ?string $subject = null, ?string $body = null, ?string $cc = null, ?string $bcc = null)
 * @method $this wifi(array<string, mixed> $config)
 * @method $this sms(string $phoneNumber, ?string $message = null)
 * @method $this phone(string $phoneNumber)
 * @method $this phoneNumber(string $phoneNumber)
 * @method $this geo(float $latitude, float $longitude, ?string $name = null)
 * @method $this bitcoin(string $address, float $amount = 0.0, array<string, mixed> $options = [])
 * @method $this btc(string $address, float $amount = 0.0, array<string, mixed> $options = [])
 */
final class QrCode
{
    /**
     * The output format.
     */
    private string $format = 'svg';

    /**
     * The size of the QR code in pixels.
     */
    private int $size = 100;

    /**
     * The margin around the QR code.
     */
    private int $margin = 0;

    /**
     * The error correction level.
     * L: 7% loss.
     * M: 15% loss.
     * Q: 25% loss.
     * H: 30% loss.
     */
    private ?ErrorCorrectionLevel $errorCorrection = null;

    /**
     * The encoding mode. Possible values are
     * ISO-8859-2, ISO-8859-3, ISO-8859-4, ISO-8859-5, ISO-8859-6,
     * ISO-8859-7, ISO-8859-8, ISO-8859-9, ISO-8859-10, ISO-8859-11,
     * ISO-8859-12, ISO-8859-13, ISO-8859-14, ISO-8859-15, ISO-8859-16,
     * SHIFT-JIS, WINDOWS-1250, WINDOWS-1251, WINDOWS-1252, WINDOWS-1256,
     * UTF-16BE, UTF-8, ASCII, GBK, EUC-KR.
     */
    private string $encoding = Encoder::DEFAULT_BYTE_MODE_ECODING;

    /**
     * The style of the blocks within the QrCode.
     * Possible values are 'square', 'dot' and 'round'.
     */
    private string $style = 'square';

    /**
     * The size of the selected style between 0 and 1.
     * Only applicable to 'dot' and 'round' styles.
     */
    private float $styleSize = 0.5;

    /**
     * The style to apply to the eyes of the QR code.
     * Possible values are circle and square.
     */
    private ?string $eyeStyle = null;

    /**
     * The foreground color of the QR code.
     */
    private ?ColorInterface $color = null;

    /**
     * The background color of the QR code.
     */
    private ?ColorInterface $backgroundColor = null;

    /**
     * An array that holds EyeFills of the color of the eyes.
     *
     * @var array<int, EyeFill>
     */
    private array $eyeColors = [];

    /**
     * The gradient to apply to the QrCode.
     */
    private ?Gradient $gradient = null;

    /**
     * Holds an image string that will be merged with the QrCode.
     */
    private ?string $imageMerge = null;

    /**
     * The percentage that a merged image should take over the source image.
     */
    private float $imagePercentage = 0.2;

    public function __construct(
        private readonly GenerateQrCodeAction $generateAction,
        private readonly CreateColorAction $colorAction,
        private readonly MergeImageAction $mergeImageAction
    ) {}

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

    public function merge(string $filepath, float $percentage = .2, bool $absolute = false): self
    {
        $config = new ImageMergeConfig($filepath, $percentage, $absolute);
        $this->imageMerge = $this->mergeImageAction->handle($config);
        $this->imagePercentage = $percentage;

        return $this;
    }

    public function mergeString(string $content, float $percentage = .2): self
    {
        $this->imageMerge = $content;
        $this->imagePercentage = $percentage;

        return $this;
    }

    public function size(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function format(string $format): self
    {
        throw_unless(in_array($format, ['svg', 'eps', 'png']), InvalidArgumentException::class, "\$format must be svg, eps, or png. {$format} is not a valid.");

        $this->format = $format;

        return $this;
    }

    public function color(int $red, int $green, int $blue, ?int $alpha = null): self
    {
        $colorVO = new Color($red, $green, $blue, $alpha);
        $this->color = $this->colorAction->handle($colorVO);

        return $this;
    }

    public function backgroundColor(int $red, int $green, int $blue, ?int $alpha = null): self
    {
        $colorVO = new Color($red, $green, $blue, $alpha);
        $this->backgroundColor = $this->colorAction->handle($colorVO);

        return $this;
    }

    public function eyeColor(int $eyeNumber, int $innerRed, int $innerGreen, int $innerBlue, int $outterRed = 0, int $outterGreen = 0, int $outterBlue = 0): self
    {
        throw_if($eyeNumber < 0 || $eyeNumber > 2, InvalidArgumentException::class, "\$eyeNumber must be 0, 1, or 2.  {$eyeNumber} is not valid.");

        $innerColor = new Color($innerRed, $innerGreen, $innerBlue);
        $outterColor = new Color($outterRed, $outterGreen, $outterBlue);

        $this->eyeColors[$eyeNumber] = new EyeFill(
            $this->colorAction->handle($innerColor),
            $this->colorAction->handle($outterColor)
        );

        return $this;
    }

    public function gradient(int $startRed, int $startGreen, int $startBlue, int $endRed, int $endGreen, int $endBlue, string $type): self
    {
        $type = mb_strtoupper($type);

        $startColor = new Color($startRed, $startGreen, $startBlue);
        $endColor = new Color($endRed, $endGreen, $endBlue);

        $this->gradient = new Gradient(
            $this->colorAction->handle($startColor),
            $this->colorAction->handle($endColor),
            GradientType::$type()
        );

        return $this;
    }

    public function eye(string $style): self
    {
        throw_unless(in_array($style, ['square', 'circle']), InvalidArgumentException::class, "\$style must be square or circle. {$style} is not a valid eye style.");

        $this->eyeStyle = $style;

        return $this;
    }

    public function style(string $style, float $size = 0.5): self
    {
        throw_unless(in_array($style, ['square', 'dot', 'round']), InvalidArgumentException::class, "\$style must be square, dot, or round. {$style} is not a valid.");

        throw_if($size < 0 || $size >= 1, InvalidArgumentException::class, "\$size must be between 0 and 1.  {$size} is not valid.");

        $this->style = $style;
        $this->styleSize = $size;

        return $this;
    }

    public function encoding(string $encoding): self
    {
        $this->encoding = mb_strtoupper($encoding);

        return $this;
    }

    public function errorCorrection(string $errorCorrection): self
    {
        $errorCorrection = mb_strtoupper($errorCorrection);
        $this->errorCorrection = ErrorCorrectionLevel::$errorCorrection();

        return $this;
    }

    public function margin(int $margin): self
    {
        $this->margin = $margin;

        return $this;
    }

    public function getWriter(ImageRenderer $renderer): Writer
    {
        return new Writer($renderer);
    }

    public function getRenderer(): ImageRenderer
    {

        return new ImageRenderer(
            $this->getRendererStyle(),
            $this->getFormatter()
        );
    }

    public function getRendererStyle(): RendererStyle
    {
        return new RendererStyle($this->size, $this->margin, $this->getModule(), $this->getEye(), $this->getFill());
    }

    public function getFormatter(): ImageBackEndInterface
    {
        if ($this->format === 'png') {
            return new ImagickImageBackEnd('png');
        }

        if ($this->format === 'eps') {
            return new EpsImageBackEnd;
        }

        return new SvgImageBackEnd;
    }

    public function getModule(): ModuleInterface
    {
        if ($this->style === 'dot') {
            return new DotsModule($this->styleSize);
        }

        if ($this->style === 'round') {
            return new RoundnessModule($this->styleSize);
        }

        return SquareModule::instance();
    }

    public function getEye(): EyeInterface
    {
        if ($this->eyeStyle === 'square') {
            return SquareEye::instance();
        }

        if ($this->eyeStyle === 'circle') {
            return SimpleCircleEye::instance();
        }

        return new ModuleEye($this->getModule());
    }

    public function getFill(): Fill
    {
        $foregroundColor = $this->color ?? new Rgb(0, 0, 0);
        $backgroundColor = $this->backgroundColor ?? new Rgb(255, 255, 255);
        $eye0 = $this->eyeColors[0] ?? EyeFill::inherit();
        $eye1 = $this->eyeColors[1] ?? EyeFill::inherit();
        $eye2 = $this->eyeColors[2] ?? EyeFill::inherit();

        if ($this->gradient instanceof Gradient) {
            return Fill::withForegroundGradient($backgroundColor, $this->gradient, $eye0, $eye1, $eye2);
        }

        return Fill::withForegroundColor($backgroundColor, $foregroundColor, $eye0, $eye1, $eye2);
    }

    public function createColor(int $red, int $green, int $blue, ?int $alpha = null): ColorInterface
    {
        $colorVO = new Color($red, $green, $blue, $alpha);

        return $this->colorAction->handle($colorVO);
    }
}
