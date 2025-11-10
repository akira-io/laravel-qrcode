<?php

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class BitcoinData
{
    public function __construct(
        public string $address,
        public float $amount,
        public ?string $label = null,
        public ?string $message = null,
        public ?string $returnAddress = null
    ) {
        if (empty($address)) {
            throw new InvalidArgumentException('Bitcoin address cannot be empty');
        }

        if ($amount <= 0) {
            throw new InvalidArgumentException('Bitcoin amount must be greater than 0');
        }
    }

    public static function create(
        string $address,
        float $amount,
        ?string $label = null,
        ?string $message = null,
        ?string $returnAddress = null
    ): self {
        return new self($address, $amount, $label, $message, $returnAddress);
    }

    public function toArray(): array
    {
        return array_filter([
            'amount' => $this->amount,
            'label' => $this->label,
            'message' => $this->message,
            'r' => $this->returnAddress,
        ], fn($value) => $value !== null);
    }
}
