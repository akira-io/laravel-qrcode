<?php

declare(strict_types=1);

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class WiFiData
{
    private const array ENCRYPTION_TYPES = ['WPA', 'WEP', 'nopass'];

    public string $encryption;

    public function __construct(
        public string $ssid,
        public ?string $password = null,
        public bool $hidden = false,
        string $encryption = 'WPA'
    ) {
        throw_if($ssid === '' || $ssid === '0', InvalidArgumentException::class, 'SSID cannot be empty');

        $normalizedEncryption = mb_strtolower($encryption) === 'nopass'
            ? 'nopass'
            : mb_strtoupper($encryption);

        throw_unless(
            in_array($normalizedEncryption, self::ENCRYPTION_TYPES, true),
            InvalidArgumentException::class,
            "Encryption type must be WPA, WEP, or nopass, got {$encryption}"
        );

        $this->encryption = $normalizedEncryption;
    }

    public static function create(string $ssid, ?string $password = null, bool $hidden = false, string $encryption = 'WPA'): self
    {
        return new self($ssid, $password, $hidden, $encryption);
    }

    public function hasPassword(): bool
    {
        return $this->encryption !== 'nopass' && $this->password !== null && $this->password !== '';
    }

    public function encryptionType(): string
    {
        if (! $this->hasPassword()) {
            return 'nopass';
        }

        return $this->encryption;
    }
}
