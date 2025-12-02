<?php

declare(strict_types=1);

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class PhoneNumber
{
    public function __construct(
        public string $number
    ) {
        throw_if($number === '' || $number === '0', InvalidArgumentException::class, 'Phone number cannot be empty');

        throw_unless(preg_match('/^[\d\s\+\-\(\)]+$/', $number), InvalidArgumentException::class, "Invalid phone number format: {$number}");
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
