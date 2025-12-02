<?php

declare(strict_types=1);

use Akira\QrCode\Image;

beforeEach(function (): void {
    $this->imagePath = __DIR__.'/images/akira.png';
    if (! file_exists($this->imagePath)) {
        throw new RuntimeException('Image not found at '.$this->imagePath);
    }
    $this->image = new Image(file_get_contents($this->imagePath));
});

it('loads an image string into a resource', function (): void {
    $expected = imagecreatefrompng($this->imagePath);
    $actual = $this->image->getImageResource();

    $w = imagesx($expected);
    $h = imagesy($expected);
    expect(imagesx($actual))->toBe($w);
    expect(imagesy($actual))->toBe($h);

    // Sample a grid of pixels for equality
    $xStep = max(1, intdiv($w, 10));
    $yStep = max(1, intdiv($h, 10));
    for ($y = 0; $y < $h; $y += $yStep) {
        for ($x = 0; $x < $w; $x += $xStep) {
            expect(imagecolorat($actual, $x, $y))
                ->toBe(imagecolorat($expected, $x, $y));
        }
    }
    imagedestroy($expected);
});

it('gets the width of the image', function (): void {
    $expected = imagecreatefrompng($this->imagePath);
    expect($this->image->getWidth())->toBe(imagesx($expected));
    imagedestroy($expected);
});

it('gets the height of the image', function (): void {
    $expected = imagecreatefrompng($this->imagePath);
    expect($this->image->getHeight())->toBe(imagesy($expected));
    imagedestroy($expected);
});

it('throws exception for invalid image data', function (): void {
    expect(fn (): Image => new Image('invalid data'))
        ->toThrow(InvalidArgumentException::class, 'Invalid image data provided to Image.');
});

it('can replace the image resource', function (): void {
    $newImg = imagecreate(100, 100);
    $this->image->setImageResource($newImg);

    expect($this->image->getWidth())->toBe(100);
    expect($this->image->getHeight())->toBe(100);
    expect($this->image->getImageResource())->toBe($newImg);
});
