<?php

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class SMSData
{
    public function __construct(
        public string $phoneNumber,
        public ?string $message = null
    ) {
        if (empty($phoneNumber)) {
            throw new InvalidArgumentException('Phone number cannot be empty');
        }

        if (! preg_match('/^[\d\s\+\-\(\)]+$/', $phoneNumber)) {
            throw new InvalidArgumentException("Invalid phone number format: {$phoneNumber}");
        }
    }

    public static function create(string $phoneNumber, ?string $message = null): self
    {
        return new self($phoneNumber, $message);
    }

    public function hasMessage(): bool
    {
        return $this->message !== null && $this->message !== '';
    }
}
