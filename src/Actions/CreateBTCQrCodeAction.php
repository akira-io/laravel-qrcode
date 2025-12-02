<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\DataTypes\BitcoinDataType;
use Akira\QrCode\ValueObjects\BitcoinData;

final class CreateBTCQrCodeAction
{
    public function handle(BitcoinData $data): string
    {
        $dataType = BitcoinDataType::fromValueObject($data);

        return (string) $dataType;
    }
}
