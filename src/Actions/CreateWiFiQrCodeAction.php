<?php

namespace Akira\QrCode\Actions;

use Akira\QrCode\DataTypes\WiFiDataType;
use Akira\QrCode\ValueObjects\WiFiData;

class CreateWiFiQrCodeAction
{
    public function handle(WiFiData $data): string
    {
        $dataType = WiFiDataType::fromValueObject($data);
        return (string) $dataType;
    }
}
