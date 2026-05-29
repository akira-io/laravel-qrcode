<?php

declare(strict_types=1);

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class VCardData
{
    public function __construct(
        public string $fullName,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $organization = null,
        public ?string $title = null,
        public ?string $phone = null,
        public ?string $email = null,
        public ?string $url = null,
        public ?string $address = null,
        public ?string $note = null
    ) {
        throw_if($fullName === '' || $fullName === '0', InvalidArgumentException::class, 'Full name cannot be empty');

        throw_if($email !== null && ! filter_var($email, FILTER_VALIDATE_EMAIL), InvalidArgumentException::class, "Invalid email address: {$email}");

        throw_if($url !== null && ! filter_var($url, FILTER_VALIDATE_URL), InvalidArgumentException::class, "Invalid URL: {$url}");
    }

    public static function create(
        string $fullName,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $organization = null,
        ?string $title = null,
        ?string $phone = null,
        ?string $email = null,
        ?string $url = null,
        ?string $address = null,
        ?string $note = null
    ): self {
        return new self($fullName, $firstName, $lastName, $organization, $title, $phone, $email, $url, $address, $note);
    }

    public function hasNameParts(): bool
    {
        return ($this->firstName !== null && $this->firstName !== '') || ($this->lastName !== null && $this->lastName !== '');
    }
}
