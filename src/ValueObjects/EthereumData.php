<?php

declare(strict_types=1);

namespace Akira\QrCode\ValueObjects;

use InvalidArgumentException;

final readonly class EthereumData
{
    public function __construct(
        public string $address,
        public ?string $value = null,
        public ?int $chainId = null,
        public ?string $label = null,
        public ?string $message = null
    ) {
        throw_unless(preg_match('/^0x[a-fA-F0-9]{40}$/', $address), InvalidArgumentException::class, "Invalid Ethereum address: {$address}");

        throw_if($value !== null && (! is_numeric($value) || (float) $value <= 0), InvalidArgumentException::class, 'Ethereum value must be greater than 0');

        throw_if($chainId !== null && $chainId <= 0, InvalidArgumentException::class, 'Ethereum chain ID must be greater than 0');
    }

    public static function create(
        string $address,
        ?string $value = null,
        ?int $chainId = null,
        ?string $label = null,
        ?string $message = null
    ): self {
        return new self($address, $value, $chainId, $label, $message);
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return array_filter([
            'value' => $this->value,
            'label' => $this->label,
            'message' => $this->message,
        ], fn (?string $parameterValue): bool => $parameterValue !== null);
    }
}
