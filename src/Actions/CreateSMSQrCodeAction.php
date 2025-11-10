<?php

namespace Akira\QrCode\Actions;

use Akira\QrCode\DataTypes\SMSDataType;
use Akira\QrCode\ValueObjects\SMSData;

class CreateSMSQrCodeAction
{
    public function handle(SMSData $data): string
    {
        $dataType = SMSDataType::fromValueObject($data);
        return (string) $dataType;
    }
}
