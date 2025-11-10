<?php

namespace Akira\QrCode\Actions;

use Akira\QrCode\DataTypes\BitcoinDataType;
use Akira\QrCode\ValueObjects\BitcoinData;

class CreateBTCQrCodeAction
{
    public function handle(BitcoinData $data): string
    {
        $dataType = BitcoinDataType::fromValueObject($data);

        return (string) $dataType;
    }
}
