<?php

declare(strict_types=1);

namespace Akira\QrCode\DataTypes;

use Akira\QrCode\Actions\BuildBitcoinStringAction;
use Akira\QrCode\Contracts\QrCodeDataTypeContract;
use Akira\QrCode\ValueObjects\BitcoinData;

final readonly class BitcoinDataType implements QrCodeDataTypeContract
{
    public function __construct(
        private BitcoinData $data,
        private BuildBitcoinStringAction $action
    ) {}

    public function __toString(): string
    {
        return $this->action->handle($this->data);
    }

    public static function fromValueObject(BitcoinData $data): self
    {
        return resolve(self::class, ['data' => $data]);
    }
}
