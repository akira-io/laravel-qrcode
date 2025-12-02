<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\DataTypes\WiFiDataType;
use Akira\QrCode\ValueObjects\WiFiData;

final class CreateWiFiQrCodeAction
{
    public function handle(WiFiData $data): string
    {
        $dataType = WiFiDataType::fromValueObject($data);

        return (string) $dataType;
    }
}
