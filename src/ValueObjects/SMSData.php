<?php

declare(strict_types=1);

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class SMSData
{
    public function __construct(
        public string $phoneNumber,
        public ?string $message = null
    ) {
        throw_if($phoneNumber === '' || $phoneNumber === '0', InvalidArgumentException::class, 'Phone number cannot be empty');

        throw_unless(preg_match('/^[\d\s\+\-\(\)]+$/', $phoneNumber), InvalidArgumentException::class, "Invalid phone number format: {$phoneNumber}");
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
