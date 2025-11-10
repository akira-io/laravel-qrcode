<?php

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class QrCodeSize
{
    public function __construct(
        public int $value
    ) {
        if ($value <= 0) {
            throw new InvalidArgumentException('QR Code size must be greater than 0');
        }
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
