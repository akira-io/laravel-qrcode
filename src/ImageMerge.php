<?php

declare(strict_types=1);

namespace Akira\QrCode;

use InvalidArgumentException;

final class ImageMerge
{
    private int $sourceImageHeight;

    private int $sourceImageWidth;

    private int $mergeImageHeight;

    private int $mergeImageWidth;

    private float $mergeRatio;

    private int $postMergeImageHeight;

    private int $postMergeImageWidth;

    private int $centerY;

    private int $centerX;

    public function __construct(private readonly Image $sourceImage, private readonly Image $mergeImage) {}

    public function merge(float $percentage): string
    {
        $this->setProperties($percentage);

        $width = max(1, $this->sourceImage->getWidth());
        $height = max(1, $this->sourceImage->getHeight());
        $img = imagecreatetruecolor($width, $height);
        imagealphablending($img, true);
        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127) ?: 1;
        imagefill($img, 0, 0, $transparent);

        imagecopy(
            $img,
            $this->sourceImage->getImageResource(),
            0,
            0,
            0,
            0,
            $this->sourceImage->getWidth(),
            $this->sourceImage->getHeight()
        );

        imagecopyresampled(
            $img,
            $this->mergeImage->getImageResource(),
            $this->centerX,
            $this->centerY,
            0,
            0,
            $this->postMergeImageWidth,
            $this->postMergeImageHeight,
            $this->mergeImageWidth,
            $this->mergeImageHeight
        );

        $this->sourceImage->setImageResource($img);

        return $this->createImage();
    }

    private function createImage(): string
    {
        ob_start();
        imagepng($this->sourceImage->getImageResource());

        return ob_get_clean() ?: '';
    }

    private function setProperties(float $percentage): void
    {
        throw_if($percentage > 1, InvalidArgumentException::class, '$percentage must be less than 1');

        $this->sourceImageHeight = $this->sourceImage->getHeight();
        $this->sourceImageWidth = $this->sourceImage->getWidth();

        $this->mergeImageHeight = $this->mergeImage->getHeight();
        $this->mergeImageWidth = $this->mergeImage->getWidth();

        $this->calculateOverlap($percentage);
        $this->calculateCenter();
    }

    private function calculateCenter(): void
    {
        $this->centerX = (int) (($this->sourceImageWidth / 2) - ($this->postMergeImageWidth / 2));
        $this->centerY = (int) (($this->sourceImageHeight / 2) - ($this->postMergeImageHeight / 2));
    }

    private function calculateOverlap(float $percentage): void
    {
        $this->mergeRatio = round($this->mergeImageWidth / $this->mergeImageHeight, 2);
        $this->postMergeImageWidth = (int) ($this->sourceImageWidth * $percentage);
        $this->postMergeImageHeight = (int) ($this->postMergeImageWidth / $this->mergeRatio);
    }
}
