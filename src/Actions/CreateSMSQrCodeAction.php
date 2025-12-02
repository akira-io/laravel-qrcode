<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\DataTypes\SMSDataType;
use Akira\QrCode\ValueObjects\SMSData;

final class CreateSMSQrCodeAction
{
    public function handle(SMSData $data): string
    {
        $dataType = SMSDataType::fromValueObject($data);

        return (string) $dataType;
    }
}
