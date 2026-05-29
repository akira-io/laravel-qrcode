<?php

declare(strict_types=1);

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class QrCodeMargin
{
    public function __construct(
        public int $value
    ) {
        throw_if($value < 0, InvalidArgumentException::class, 'Margin must be greater than or equal to 0');

        throw_if($value > 50, InvalidArgumentException::class, 'Margin must be less than or equal to 50');
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
