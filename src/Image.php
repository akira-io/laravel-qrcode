<?php

declare(strict_types=1);

namespace Akira\QrCode;

use GdImage;
use InvalidArgumentException;

final class Image
{
    private GdImage $image;

    public function __construct(string $image)
    {
        $img = @imagecreatefromstring($image);

        throw_if($img === false, InvalidArgumentException::class, 'Invalid image data provided to Image.');

        $this->image = $img;
    }

    public function __destruct()
    {
        imagedestroy($this->image);
    }

    public function getWidth(): int
    {
        return imagesx($this->image);
    }

    public function getHeight(): int
    {
        return imagesy($this->image);
    }

    public function getImageResource(): GdImage
    {
        return $this->image;
    }

    public function setImageResource(GdImage $image): void
    {
        imagedestroy($this->image);
        $this->image = $image;
    }
}
