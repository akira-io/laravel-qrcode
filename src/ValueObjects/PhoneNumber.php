<?php

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class PhoneNumber
{
    public function __construct(
        public string $number
    ) {
        if (empty($number)) {
            throw new InvalidArgumentException('Phone number cannot be empty');
        }

        if (!preg_match('/^[\d\s\+\-\(\)]+$/', $number)) {
            throw new InvalidArgumentException("Invalid phone number format: {$number}");
        }
    }

    public static function fromString(string $number): self
    {
        return new self($number);
    }

    public function toString(): string
    {
        return $this->number;
    }
}
