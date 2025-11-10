<?php

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class QrCodeMargin
{
    public function __construct(
        public int $value
    ) {
        if ($value < 0) {
            throw new InvalidArgumentException('Margin must be greater than or equal to 0');
        }
    }

    public static function fromInt(int $margin): self
    {
        return new self($margin);
    }

    public static function none(): self
    {
        return new self(0);
    }

    public static function default(): self
    {
        return new self(4);
    }

    public function toInt(): int
    {
        return $this->value;
    }
}
