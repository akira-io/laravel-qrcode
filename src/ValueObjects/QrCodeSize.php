<?php

declare(strict_types=1);

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class QrCodeSize
{
    public function __construct(
        public int $value
    ) {
        throw_if($value < 10 || $value > 2000, InvalidArgumentException::class, 'QR Code size must be between 10 and 2000 pixels');
    }

    public static function fromInt(int $size): self
    {
        return new self($size);
    }

    public function toInt(): int
    {
        return $this->value;
    }
}
