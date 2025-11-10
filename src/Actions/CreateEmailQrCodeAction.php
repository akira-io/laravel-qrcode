<?php

namespace Akira\QrCode\Actions;

use Akira\QrCode\DataTypes\EmailDataType;
use Akira\QrCode\ValueObjects\EmailData;

class CreateEmailQrCodeAction
{
    public function handle(EmailData $data): string
    {
        $dataType = EmailDataType::fromValueObject($data);
        return (string) $dataType;
    }
}
