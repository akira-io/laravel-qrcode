<?php

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class EmailData
{
    public function __construct(
        public string $address,
        public ?string $subject = null,
        public ?string $body = null,
        public ?string $cc = null,
        public ?string $bcc = null
    ) {
        if (! filter_var($address, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email address: {$address}");
        }

        if ($cc !== null && ! filter_var($cc, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid CC email address: {$cc}");
        }

        if ($bcc !== null && ! filter_var($bcc, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid BCC email address: {$bcc}");
        }
    }

    public static function create(
        string $address,
        ?string $subject = null,
        ?string $body = null,
        ?string $cc = null,
        ?string $bcc = null
    ): self {
        return new self($address, $subject, $body, $cc, $bcc);
    }
}
