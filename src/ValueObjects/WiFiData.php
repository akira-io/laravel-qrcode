<?php

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class WiFiData
{
    public function __construct(
        public string $ssid,
        public ?string $password = null,
        public bool $hidden = false
    ) {
        if (empty($ssid)) {
            throw new InvalidArgumentException('SSID cannot be empty');
        }
    }

    public static function create(string $ssid, ?string $password = null, bool $hidden = false): self
    {
        return new self($ssid, $password, $hidden);
    }

    public function hasPassword(): bool
    {
        return $this->password !== null && $this->password !== '';
    }
}
