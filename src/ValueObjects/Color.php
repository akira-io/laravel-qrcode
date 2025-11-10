<?php

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class Color
{
    public function __construct(
        public int $red,
        public int $green,
        public int $blue,
        public ?int $alpha = null
    ) {
        $this->validateColorValue($red, 'red');
        $this->validateColorValue($green, 'green');
        $this->validateColorValue($blue, 'blue');

        if ($alpha !== null) {
            $this->validateAlphaValue($alpha);
        }
    }

    public static function fromRgb(int $red, int $green, int $blue): self
    {
        return new self($red, $green, $blue);
    }

    public static function fromRgba(int $red, int $green, int $blue, int $alpha): self
    {
        return new self($red, $green, $blue, $alpha);
    }

    public static function black(): self
    {
        return new self(0, 0, 0);
    }

    public static function white(): self
    {
        return new self(255, 255, 255);
    }

    public function hasAlpha(): bool
    {
        return $this->alpha !== null;
    }

    private function validateColorValue(int $value, string $component): void
    {
        if ($value < 0 || $value > 255) {
            throw new InvalidArgumentException(
                "Color {$component} must be between 0 and 255, got {$value}"
            );
        }
    }

    private function validateAlphaValue(int $value): void
    {
        if ($value < 0 || $value > 127) {
            throw new InvalidArgumentException(
                "Alpha value must be between 0 and 127, got {$value}"
            );
        }
    }
}
