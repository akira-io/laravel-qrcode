<?php

declare(strict_types=1);

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
        throw_unless(filter_var($address, FILTER_VALIDATE_EMAIL), InvalidArgumentException::class, "Invalid email address: {$address}");

        throw_if($cc !== null && ! filter_var($cc, FILTER_VALIDATE_EMAIL), InvalidArgumentException::class, "Invalid CC email address: {$cc}");

        throw_if($bcc !== null && ! filter_var($bcc, FILTER_VALIDATE_EMAIL), InvalidArgumentException::class, "Invalid BCC email address: {$bcc}");
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
