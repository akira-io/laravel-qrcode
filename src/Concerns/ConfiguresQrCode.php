<?php

declare(strict_types=1);

namespace Akira\QrCode\Concerns;

use Akira\QrCode\Support\QrCodeConfigDefaults;
use Akira\QrCode\ValueObjects\Color;
use Akira\QrCode\ValueObjects\QrCodeMargin;
use Akira\QrCode\ValueObjects\QrCodeSize;
use BaconQrCode\Common\ErrorCorrectionLevel;
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
use InvalidArgumentException;

trait ConfiguresQrCode
{
    public function size(int $size): self
    {
        $this->size = QrCodeSize::fromInt($size)->toInt();

        return $this;
    }

    public function format(string $format): self
    {
        throw_unless(in_array($format, ['svg', 'eps', 'png', 'webp', 'pdf'], true), InvalidArgumentException::class, "\$format must be svg, eps, png, webp, or pdf. {$format} is not a valid.");

        $this->format = $format;

        return $this;
    }

    public function color(int $red, int $green, int $blue, ?int $alpha = null): self
    {
        $color = new Color($red, $green, $blue, $alpha);
        $this->color = $this->colorAction->handle($color);

        return $this;
    }

    public function backgroundColor(int $red, int $green, int $blue, ?int $alpha = null): self
    {
        $color = new Color($red, $green, $blue, $alpha);
        $this->backgroundColor = $this->colorAction->handle($color);

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
        $this->margin = QrCodeMargin::fromInt($margin)->toInt();

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
        if (in_array($this->format, ['png', 'webp', 'pdf'], true)) {
            return new ImagickImageBackEnd($this->format);
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
        $color = new Color($red, $green, $blue, $alpha);

        return $this->colorAction->handle($color);
    }

    private function applyConfigDefaults(): void
    {
        QrCodeConfigDefaults::apply($this, $this->format, $this->size, $this->margin, $this->encoding);
    }

    private function getConfiguredMergePercentage(): float
    {
        return QrCodeConfigDefaults::mergePercentage($this->imagePercentage);
    }
}
