<?php

declare(strict_types=1);

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class LitecoinData
{
    public function __construct(
        public string $address,
        public float $amount,
        public ?string $label = null,
        public ?string $message = null
    ) {
        throw_if($address === '' || $address === '0', InvalidArgumentException::class, 'Litecoin address cannot be empty');

        throw_if($amount <= 0, InvalidArgumentException::class, 'Litecoin amount must be greater than 0');
    }

    public static function create(string $address, float $amount, ?string $label = null, ?string $message = null): self
    {
        return new self($address, $amount, $label, $message);
    }

    /**
     * @return array<string, float|string>
     */
    public function toArray(): array
    {
        return array_filter([
            'amount' => $this->amount,
            'label' => $this->label,
            'message' => $this->message,
        ], fn (float|string|null $parameterValue): bool => $parameterValue !== null);
    }
}
