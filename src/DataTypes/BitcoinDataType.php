<?php

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

    public static function fromValueObject(BitcoinData $data): self
    {
        return app(self::class, ["data" => $data]);
    }


    public function __toString(): string
    {
        return $this->action->handle($this->data);
    }
}
